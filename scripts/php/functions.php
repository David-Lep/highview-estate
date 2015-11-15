<?php

require('pdo_connect.php');

class property {
	public function __construct($req, $type){
		$this->req = $req;
		$this->type = $type;
	}

	private function checkRequest($conn){
		$page_exists = 0;
		if($this->type === "house"){
			$sql = "SELECT id, url FROM sale_properties WHERE published = 1 AND url != ''";
		} else if($this->type === "land") {
			$sql = "SELECT url FROM sale_land";
		} else {
			return false;
		}
		if(!$conn->query($sql)){ //No results
			return false;
		} else {
			foreach($conn->query($sql) as $row) {
				$this->accessible_pages[] = $row['url']; //Build array of accessible pages
			}
			if(in_array($this->req, $this->accessible_pages)) { //Check if request is valid
				return true;
			} else {
				return false;
			}
		}
	}

	public function display() {
		$dbc = new dbc();
		$conn = $dbc->connect();

		if(!$this->checkRequest($conn)){
			$conn = null;
			return false;
		} else {
			$sth = $conn->prepare("SELECT * FROM sale_properties WHERE url = :req AND published = 1");
			$sth->bindParam(':req', $this->req);
			$sth->execute();
			$this->result = $sth->fetchAll(PDO::FETCH_ASSOC);

			foreach($this->result as $row){
				$this->prop_info = $row;
			}
			$conn = null;
			$this->result = ''; //Unset to prevent duplicating data, return for proper fix later
			return $this->req;
		}
	}
}

class preview {
	public function __construct($type){
		if($type === 'house' || $type === 'land'){
			$this->type = $type;
		} else {
			return false;
		}
	}
	public function properties(){
		$dbc = new dbc();
		$conn = $dbc->connect();
		if(isset($conn)){ //If connection was found, $this->type is valid
			if(isset($this->type)){
				$sth = $conn->prepare("SELECT url, name, feat_img, prop_head, prop_price, prop_size, prop_dual_occupy FROM sale_properties WHERE published = 1 AND type = :type");
				$sth->bindParam(':type', $this->type);
				$sth->execute();
				$this->rows = $sth->rowCount();
				if($this->rows <= 0) { //If no rows are found, exit
					$conn = null;
				} else {
					$this->result = $sth->fetchAll(PDO::FETCH_ASSOC); //Rows are found, return results
					$conn = null;
					return true;
				}
			}
		}
	}
}



class prepare {
	public function getConfig(){
		$dbc = new dbc();
		$conn = $dbc->connect();

		$sth = $conn->prepare('SELECT * FROM config');
		$this->config = array();
		if($sth->execute()){
			while($row = $sth->fetch(PDO::FETCH_ASSOC)){
				$this->config = $row;
			}
		}
		$conn = null;
		return $this->config;
	}

	public function footerMenu($string){
		$dbc = new dbc();
		$conn = $dbc->connect();

		$this->type = $string;
		$sth = $conn->prepare('SELECT url, name FROM sale_properties WHERE published = 1 AND type = :type');
		$this->sth->bindParam(':type', $this->type);

		if($sth->execute()){
			while($row = $sth->fetch(PDO::FETCH_ASSOC)){
				if($type === 'house'){
					echo '<li><a href="house.php?type=house&req=' . $row['url'] . '">' . $row['name'] . '</a></li>';
				} else if($type === "land"){
					echo '<li><a href="land.php?type=land&req=' . $row['url'] . '">' . $row['name'] . '</a></li>';
				}
			}
		}
		$conn = null;
	}
}



class menu {
	public function __construct(){
		//$this->type = $string;
	}

	public function footerMenu($type){
		$dbc = new dbc();
		$conn = $dbc->connect();
		$this->type = $type;

		$sth = $conn->prepare('SELECT url, name FROM sale_properties WHERE published = 1 AND type = :type');
		$sth->bindParam(':type', $this->type);
		if($sth->execute()){
			$this->count = $sth->rowcount();
			while($this->row = $sth->fetchAll(PDO::FETCH_ASSOC)){
				$this->result = $this->row;
			}
		}
		$conn = null;
	}
}






class form {
	public function __construct(){
		$this->form_fields = ['contact_subject', 'contact_name', 'contact_email', 'contact_phone', 'contact_message', 'submit_message'];
		$this->valid_fields = ['contact_email', 'contact_phone'];
		$this->errors = array();
	}

	public function validate($field, $value) {
		$this->field = $field;
		$this->value = $value;
		$this->emptyMsg = "Please fill out all fields before submitting.";
		if($this->value === '' && !in_array($this->emptyMsg, $this->errors)){ //If empty field is sent. Also prevent duplicate error messages
			$this->errors[] = $this->emptyMsg;
			return false;
		}
		if(in_array($this->field, $this->form_fields)) { //Check input name is valid
			if(in_array($this->field, $this->valid_fields)) { //Check input name is valid
				if(!empty($this->value)){
					switch($this->field){
						case 'contact_email':
							if(filter_var($this->value, FILTER_VALIDATE_EMAIL)) { //Basic email validation
								return true;
							} else {
								$this->errors[] = "Invalid email address.";
								return false;
							};

						case 'contact_phone':
							$this->value =  preg_replace('/\D/', '', $this->value); //Remove any non digit characers, whitespace, dashes etc.
							if(ctype_digit($this->value)){
								if(strlen($this->value) >= 8 && strlen($this->value) <= 12){
									return true;
								};
							};
							$this->errors[] = "Phone numbers must be 8-12 digits";
							return false;

						default:
							$this->errors[] = "Something went wrong, please try again later";
							return false;
					}
				};
			} else {
				return true; //Form fields that don't require validation
			};
		} else {
			$this->errors[] = "An invalid form element was found. Please reload the page and try again.";
			return false; //unknown field name was entered
		}
	}

	public function email(){
 		$prepare = new prepare(); //Get contact email from config
  		$config = $prepare->getConfig();
		$to = $config['contact_form_email'];
		//$to      = 'dleprevost89@gmail.com';
		$subject = "Highview Estate - " . $_POST['contact_subject'] . " enquiry";
		$message = "Name: " . $_POST['contact_name'] . "\r\nContact Number: " . $_POST['contact_phone'] . "\r\nMessage: \r\n" . $_POST['contact_message'];
		$headers = 'From:' . $_POST['contact_email'] . "\r\n" .
		    'Reply-To:' . $_POST['contact_email'] . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();

		return mail($to, $subject, $message, $headers);
	}

}





class content {
	public function __construct($filename){
		$this->page_name = $filename;
		$dbc = new dbc();
		$this->conn = $dbc->connect();
		$this->fetchContent();
	}

	private function fetchContent(){
		$sth = $this->conn->prepare('SELECT * FROM page_structure ps INNER JOIN article_content ac ON ps.page_content = ac.id WHERE ps.page_name = :name');
		$sth->bindParam(':name', $this->page_name);
		if($sth->execute()){
			while($this->row = $sth->fetch(PDO::FETCH_ASSOC)){
				$this->result = $this->row;
			}
		}
		$conn = null;
	}
};

?>
