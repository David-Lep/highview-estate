<?php

/*
	When included, the class contactForm is called.
	This will then call the mail function is submit has been pressed
	If not submitted - Form will be displayed
	If submitted and not accepted - Form will be displayed and populated with previous data
	If submitted and accepted - Data will be emailed as well as stored in the database
*/

class mail {
	public function __construct() {
		$this->errors = [];
		$this->validateInput();
		$this->handleErrors();
	}

	protected function validateInput() {
/* Check for Empty Inputs */
		foreach($_POST as $field => $data) {
			if(empty($data) && $field !== 'contact_phone'){ //Ignore contact_phone
				$this->errors[] = ucfirst(substr(strstr(str_replace('_', ' ', $field), ' '), 1)) . " is empty"; //Change contact_name to Name, contact_email to Email, etc.
			}
		}
/* Stop processing if errors are found */
		if(!empty($this->errors)) {
			return false;
		}

/* Check individual inputs */
/* Subject */
		$this->validSubjects = ['General', 'Investment', 'Financing']; //Only acceptable subjects
		if(in_array($_POST['contact_subject'], $this->validSubjects)){
			$this->valid['subject'] = $_POST['contact_subject'];
		} else {
			$this->errors[] = "Subject is invalid";
		}

/* Name */
		$cn = trim($_POST['contact_name']);
		if(preg_match("/^[a-zA-Z'-]/", $cn) && strlen($cn) < 36){
			$this->valid['name'] = $cn;
		} else {
			$this->errors[] = "Name is invalid";
		}

/* Email */
		$ce = trim($_POST['contact_email']);
		if(filter_var($ce, FILTER_VALIDATE_EMAIL) && strlen($ce < 100)) {
			$this->valid['email'] = $ce;
		} else {
			$this->errors[] = "Email is invalid";
		}

/* Phone is only checked for length - Allow users to add their own ()+- extensions, and so on */
		$ph = trim($_POST['contact_phone']);
		if(strlen($ph) < 25 || $ph = false){
			$this->valid['phone'] = $ph;
		} else {
			$this->errors[] = "Phone is invalid";
		}

/* Message - Only check if len > 10,000. Must fit in MySQL -TEXT- field */
		if(strlen($_POST['contact_message']) < 10000){
			$this->valid['message'] = trim($_POST['contact_message']);
		} else {
			$this->errors[] = "The message is too long, please shorten it.";
		}

/* CSFR Token */
		$_POST['token'] === $_SESSION['authToken'] ? $this->valid['token'] = $_SESSION['authToken'] : $this->errors[] = "Please refresh the page and resubmit the form.";
/* End error checking */

	}

	private function handleErrors() {
		if(empty($this->errors)){ //Continue of no errors found
			$this->storeMessage();
		} else {
			foreach($this->errors as $error){
				echo $error . "<br>";
			}
		}
	}


	private function storeMessage() {
		$dbc = new dbc();
		$conn = $dbc->connect();

		date_default_timezone_set('Australia/NSW');
		date_default_timezone_get();
		$date = date("Y-m-d H:i:s");

		$sth = $conn->prepare("INSERT INTO message(subject, name, email, phone, content, send_date) VALUES (:sub, :name, :email, :phone, :content, :senddate)");
		$sth->bindParam(':sub', $this->valid['subject']);
		$sth->bindParam(':name', $this->valid['name']);
		$sth->bindParam(':email', $this->valid['email']);
		$sth->bindParam(':phone', $this->valid['phone']);
		$sth->bindParam(':content', $this->valid['message']);
		$sth->bindParam(':senddate', $date);

		if($sth->execute()){ /* Message has been stored in Database. Time to email it. */
			$this->email($conn);
		} else { /* Failed to store the message in the database. Attempt to email it */
			echo "failed to add to database";
		}
	}

	protected function email($conn) {
		$sth = $conn->prepare('SELECT email, alt_email FROM email_recipient');
		$sth->execute();
		while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$this->recipients[] = $row['email'];
			if(!empty($row['alt_email'])) {
				$this->recipients[] = $row['alt_email'];
			}

		}
		foreach($this->recipients as $contact) {
			$to = $contact;
			$subject = "Highview Estate - " . $_POST['contact_subject'] . " enquiry";
			$message = "Name: " . $_POST['contact_name'] . "\r\nContact Number: " . $_POST['contact_phone'] . "\r\nMessage: \r\n" . $_POST['contact_message'];
			$headers = 'From:' . $_POST['contact_email'] . "\r\n" .
			    'Reply-To:' . $_POST['contact_email'] . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();


			return mail($to, $subject, $message, $headers);
		};
		$_SESSION['sent'] = 1;
		header('Location:contact.php');
	}

}


class contactForm {
	public function __construct() {
		if(isset($_POST['submit_message'])) {
			$mail = new mail();
		}
		$this->generateToken();
		$this->display();

	}

	protected function generateToken() {
		$this->token = md5(uniqid(rand(), true));//Generate CSRF Token
		$_SESSION['authToken'] = $this->token;
	}

	protected function display(){
?>
		<article class="type-system-slab">
			<h2>If you have any questions, please feel free to send us a message.</h2>
		</article>
		<form name="contact-form" id="contact_form" method="post">
			<label>Subject</label>
			<select name="contact_subject">
				<option>General</option>
				<option>Investment</option>
				<option>Financing</option>
			</select>
			<label>Name:</label>
			<input type="text" name="contact_name" id="contact_name" maxlength="36" value="<?php if(!empty($_POST['contact_name'])) { echo $_POST['contact_name']; } ?>" />
			<label>Email:</label>
			<input type="text" name="contact_email" id="contact_email" maxlength="100" value="<?php if(!empty($_POST['contact_email'])) { echo $_POST['contact_email']; } ?>" />
			<label>Phone:</label>
			<input type="text" name="contact_phone" id="contact_phone" maxlength="25" value="<?php if(!empty($_POST['contact_phone'])) { echo $_POST['contact_phone']; }  ?>" />
			<label>Message:</label>
			<textarea name="contact_message" id="contact_message"><?php if(!empty($_POST['contact_message'])) { echo $_POST['contact_message']; } ?></textarea>

			<input type="hidden" name="token" value="<?php echo $this->token; ?>">

			<input type="submit" name="submit_message" id="submit_message" value="Send" />
		</form>
		<?php
	}

}

?>
