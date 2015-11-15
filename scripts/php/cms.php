<?php
//include_once("functions.php");
//include_once('../../inc/highview/pdo_connect.php');
include_once("pdo_connect.php");


if(!class_exists('article')){

	class article {
		public function __construct(){
			//$this->type = $string;
			$dbc = new dbc();
			$this->conn = $dbc->connect();
		}

		public function listArticles(){
			$sth = $this->conn->prepare('SELECT ps.page_content, ps.page_name FROM page_structure ps INNER JOIN article_content ac ON ps.page_content = ac.id');
			if($sth->execute()){
				while($row = $sth->fetch()){
					$this->results[] = $row['page_name'];
				}
			}
		}


		public function validArticle($article){
			$this->article = $article;
			$this->listArticles();
			if(in_array($this->article, $this->results)){
				return true;
			} else {
				return false;
			}
		}


		public function getArticleContent(){
			$this->results = [];
			$sth = $this->conn->prepare('SELECT meta_desc, meta_keywords, content, page_title FROM page_structure ps INNER JOIN article_content ac ON ps.page_content = ac.id WHERE ps.page_name = :name');
			$sth->bindParam(':name', $this->article);
			if($sth->execute()){
				while($row = $sth->fetch(PDO::FETCH_ASSOC)){
					$this->result = $row;
				}
				$this->generateArticle();
			}
		}


		private function generateArticle(){
			?>
			<form name="editContent" id="editContent" method="post">
				<br>
				<label>Title</label><input type="text" name="editTitle" value="<?php echo $this->result['page_title'];?>"></input>
				<label>Keywords (comma, separated)</label><input type="text" name="editKeywords" value="<?php echo $this->result['meta_keywords'];?>"></input>
				<label>Page Description <span id="metaDescCount"></span></label><input type="text" name="editDesc" id="metaDesc" value="<?php echo $this->result['meta_desc'];?>"></input>
				<textarea name="editor1" id="editor1">
					<?php echo $this->result['content']; ?>
				</textarea>
				<input type="hidden" name="articleName" value="<?php echo $this->article;?>"></input>
				<input type="submit" name="updateArticle" value="Update Article"></input>
			</form>
          <?php
		}


		public function updateArticle(){
			//var_dump($_POST);
			if($this->validArticle($_POST['articleName'])){ //Ensure hidden field value is still valid
				$this->conn->beginTransaction(); //Begin Transaction
				try {
					$sth = $this->conn->prepare('SELECT page_content FROM page_structure WHERE page_name = :article');
					$sth->bindParam(":article", $_POST['articleName']);
					if($sth->execute()){
						$this->articleId = $sth->fetchColumn();
					};

					$sth = $this->conn->prepare('UPDATE page_structure SET meta_desc = ?, meta_keywords = ?, page_title = ? WHERE page_name = ?');
					$sth->bindParam(1, $_POST['editDesc'], PDO::PARAM_STR);
					$sth->bindParam(2, $_POST['editKeywords'], PDO::PARAM_STR);
					$sth->bindParam(3, $_POST['editTitle'], PDO::PARAM_STR);
					$sth->bindParam(4, $_POST['articleName'], PDO::PARAM_STR);
					$sth->execute();

					$sth = $this->conn->prepare('UPDATE article_content SET content = ? WHERE id = ?');
					$sth->bindParam(1, $_POST['editor1'], PDO::PARAM_STR);
					$sth->bindParam(2, $this->articleId, PDO::PARAM_STR);
					$sth->execute();
				$this->conn->commit();
			} catch (PDOException $e) {

				$this->conn->rollBack();
				exit;
			}


			} else {
				echo "Something went wrong. Your update could not be completed.";
			}
		}
	}
}
if(!class_exists('properties')){


	class properties {
		public function __construct(){
			$dbc = new dbc();
			$this->conn = $dbc->connect();
		}


		public function listProperties($type){
			$this->type = $type;
			$sth = $this->conn->prepare('SELECT name FROM sale_properties WHERE type = :type AND url IS NOT NULL');
			$sth->bindParam(':type', $type);

			if($sth->execute()){
				while($row = $sth->fetch()){
					$this->results[] = $row['name'];
				}
			}
		}

		public function validProperty($name, $type){
			$this->propertyName = $name;
			$this->propertyType = $type;
			$this->listProperties($this->propertyType);
			if(in_array($this->propertyName, $this->results)){
				return true;
			} else {
				return false;
			}
		}

		public function getPropertyInfo($new){ //Binary parameter. 1 for new properties
			$this->results = [];
			$this->new = $new;
			$sth = $this->conn->prepare('SELECT * FROM sale_properties WHERE name = :name');
			$sth->bindParam(':name', $this->propertyName);
			if($sth->execute()){
				while($row = $sth->fetch(PDO::FETCH_ASSOC)){
					$this->result = $row;
				}
				$this->generateProperty();
			}
		}



		private function generateProperty(){
			?>
			<form name="editContent" id="editContent" method="post" enctype="multipart/form-data">
				<br>
				<label>Name</label><input type="text" name="propName" value="<?php echo $this->result['name'];?>"></input>

				<?php
				if($this->propertyType === 'house'){ ?>
					<label>Headline</label><input type="text" name="propHead" value="<?php echo $this->result['prop_head'];?>"></input>
					<label>Intro</label><input type="text" name="propIntro" value="<?php echo $this->result['prop_intro'];?>"></input>
					<label>Bedrooms</label><input type="text" name="propBeds" value="<?php echo $this->result['prop_bedrooms'];?>"></input>
					<label>Bathrooms</label><input type="text" name="propBaths" value="<?php echo $this->result['prop_bathrooms'];?>"></input>
					<label>Price</label><input type="text" name="propPrice" value="<?php echo $this->result['prop_price'];?>"></input>
					<label>Featured Image</label><input type="text" name="propFeatImg" value="<?php echo $this->result['feat_img'];?>"></input>

				<?php } elseif($this->propertyType === 'land'){ ?>
					<label>Size (m²)</label><input type="text" name="propSize" value="<?php echo $this->result['prop_size'];?>"></input>
					<label>Dual Occupancy</label>

					<?php
					if($this->result['prop_dual_occupy'] == 1){ ?>
						<input type="radio" name="propDual" value="1" checked>Yes</input><br>
						<input type="radio" name="propDual" value="0">No</input>

					<?php
					} else { ?>
						<input type="radio" name="propDual" value="1">Yes</input><br>
						<input type="radio" name="propDual" value="0" checked>No</input>

					<?php } ?>
					<label>Starting Price</label><input type="text" name="propPrice" value="<?php echo $this->result['prop_price'];?>"></input>
					<label>Featured Image</label><input type="text" name="propFeatImg" value="<?php echo $this->result['feat_img'];?>"></input>
					<input type="button" id="uploadImage" value="Upload"></input>
				<?php } ?>

				<label>URL</label><input type="text" name="propURL" value="<?php echo $this->result['url'];?>"></input>
				<label>Published</label>


				<?php
				if($this->result['published'] == 1){ ?>
					<input type="radio" name="propPublish" value="1" checked>Published</input><br>
					<input type="radio" name="propPublish" value="0">Not Published</input>
				<?php
				} else { ?>
					<input type="radio" name="propPublish" value="1">Published</input><br>
					<input type="radio" name="propPublish" value="0" checked>Not Published</input>
				<?php
				}?>

				<textarea name="editor1" id="editor1">
				<?php
					if($this->propertyType === 'house'){
						echo $this->result['prop_overview'];
					} elseif($this->propertyType === 'land'){
						echo $this->result['prop_head'];
					}
				?>
				</textarea>
				<input type="hidden" name="propType" value="<?php echo $this->propertyType;?>"></input>
				<input type="hidden" name="propToUpdate" value="<?php echo $this->propertyName?>"></input>
				<?php
					if($this->new === 0){
				?>
						<input type="submit" name="updateProperty" value="Update Property"></input>
				<?php } elseif($this->new === 1){ ?>
						<input type="submit" name="createNewProperty" value="Create Property"></input>
				<?php } ?>
			</form>
          <?php
		}


		public function updateProperty(){
			$this->type = $_POST['propType'];

			if($this->validProperty($_POST['propToUpdate'], $_POST['propType'])){ //Ensure hidden field value is still valid
				$this->conn->beginTransaction(); //Begin Transaction
				try {

					$sth = $this->conn->prepare('SELECT id FROM sale_properties WHERE name = :name');
					$sth->bindParam(":name", $_POST['propToUpdate']);
					if($sth->execute()){
						$this->propId = $sth->fetchColumn();
					};


					if($this->type === 'house'){
						$sth = $this->conn->prepare('UPDATE sale_properties SET url = ?, name = ?, published = ?, prop_head = ?, prop_intro = ?, prop_overview = ?, prop_bedrooms = ?, prop_bathrooms = ?, prop_price = ?, feat_img = ? WHERE id = ?');
						$sth->bindParam(1, $_POST['propURL'], PDO::PARAM_STR);
						$sth->bindParam(2, $_POST['propName'], PDO::PARAM_STR);
						$sth->bindParam(3, $_POST['propPublish'], PDO::PARAM_STR);
						$sth->bindParam(4, $_POST['propHead'], PDO::PARAM_STR);
						$sth->bindParam(5, $_POST['propIntro'], PDO::PARAM_STR);
						$sth->bindParam(6, $_POST['editor1'], PDO::PARAM_STR);
						$sth->bindParam(7, $_POST['propBeds'], PDO::PARAM_STR);
						$sth->bindParam(8, $_POST['propBaths'], PDO::PARAM_STR);
						$sth->bindParam(9, $_POST['propPrice'], PDO::PARAM_STR);
						$sth->bindParam(10, $_POST['propFeatImg'], PDO::PARAM_STR);
						$sth->bindParam(11, $this->propId, PDO::PARAM_STR);
						$sth->execute();
					} elseif($this->type === 'land'){
						$sth = $this->conn->prepare('UPDATE sale_properties SET url = ?, name = ?, published = ?, prop_head = ?, prop_price = ?, prop_size = ?, prop_dual_occupy = ?, feat_img = ? WHERE id = ?');
						$sth->bindParam(1, $_POST['propURL'], PDO::PARAM_STR);
						$sth->bindParam(2, $_POST['propName'], PDO::PARAM_STR);
						$sth->bindParam(3, $_POST['propPublish'], PDO::PARAM_STR);
						$sth->bindParam(4, $_POST['editor1'], PDO::PARAM_STR); //For land, this is where all text goes
						$sth->bindParam(5, $_POST['propPrice'], PDO::PARAM_STR);
						$sth->bindParam(6, $_POST['propSize'], PDO::PARAM_STR);
						$sth->bindParam(7, $_POST['propDual'], PDO::PARAM_STR);
						$sth->bindParam(8, $_POST['propFeatImg'], PDO::PARAM_STR);
						$sth->bindParam(9, $this->propId, PDO::PARAM_STR);
						$sth->execute();
					}
				$this->conn->commit();
				} catch (PDOException $e) {
					$this->conn->rollBack();
					exit;
				}
			} else {
				echo "Something went wrong. Your update could not be completed.";
			}
		}




		public function create($type){ //Load template to make creating a new entry neater
			if($type === 'house'){
				$this->validProperty('House Template', $type);
			} elseif ($type === 'land'){
				$this->validProperty('Land Template', $type);
			}
			$this->getPropertyInfo(1);
		}


		public function save(){
			$this->type = $_POST['propType'];
			$this->conn->beginTransaction(); //Begin Transaction
			try {
				if($this->type === 'house'){
					$sth = $this->conn->prepare('INSERT INTO sale_properties(url, name, published, prop_head, prop_intro, prop_overview, prop_bedrooms, prop_bathrooms, prop_price, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
					$sth->bindParam(1, $_POST['propURL'], PDO::PARAM_STR);
					$sth->bindParam(2, $_POST['propName'], PDO::PARAM_STR);
					$sth->bindParam(3, $_POST['propPublish'], PDO::PARAM_STR);
					$sth->bindParam(4, $_POST['propHead'], PDO::PARAM_STR);
					$sth->bindParam(5, $_POST['propIntro'], PDO::PARAM_STR);
					$sth->bindParam(6, $_POST['editor1'], PDO::PARAM_STR);
					$sth->bindParam(7, $_POST['propBeds'], PDO::PARAM_STR);
					$sth->bindParam(8, $_POST['propBaths'], PDO::PARAM_STR);
					$sth->bindParam(9, $_POST['propPrice'], PDO::PARAM_STR);
					$sth->bindParam(10, $this->type, PDO::PARAM_STR);
					$sth->execute();

				} elseif($this->type === 'land'){
					$sth = $this->conn->prepare('INSERT INTO sale_properties(url, name, published, prop_head, prop_price, prop_size, prop_dual_occupy, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
					$sth->bindParam(1, $_POST['propURL'], PDO::PARAM_STR);
					$sth->bindParam(2, $_POST['propName'], PDO::PARAM_STR);
					$sth->bindParam(3, $_POST['propPublish'], PDO::PARAM_STR);
					$sth->bindParam(4, $_POST['editor1'], PDO::PARAM_STR); //For land, this is where all text goes
					$sth->bindParam(5, $_POST['propPrice'], PDO::PARAM_STR);
					$sth->bindParam(6, $_POST['propSize'], PDO::PARAM_STR);
					$sth->bindParam(7, $_POST['propDual'], PDO::PARAM_STR);
					$sth->bindParam(8, $this->type, PDO::PARAM_STR);
					$sth->execute();
				}

			$this->conn->commit();
			} catch (PDOException $e) {
				$this->conn->rollBack();
				exit;
			}
		}
	}
}

class messages {
	public function __construct() {
		$dbc = new dbc();
		$this->conn = $dbc->connect();
	}

	public function checkMessages($type) {
		$this->type = $type == 'new' ? false : true;
		$sql = 'SELECT * FROM message WHERE viewed = :viewed ORDER BY send_date DESC';
		$sth = $this->conn->prepare($sql);
		$sth->bindParam(":viewed", $this->type);
		$sth->execute();
		$this->newCount = $sth->rowCount();
		$this->result = $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	public function displayMessages() {
		foreach($this->result as $key => $value) {
			$neatDate = date('dS F y - h:ma', strtotime($this->result[$key]['send_date']));
?>
			<div class="message-container on">
				<div class="message-headers">
					<ul>
						<li><?php echo htmlspecialchars($this->result[$key]['name']);?></li>
						<li><?php echo htmlspecialchars($this->result[$key]['subject']);?> Enquiry</li>
						<li><a href="mailto:<?php echo htmlspecialchars($this->result[$key]['email']);?>"><?php echo $this->result[$key]['email'];?></a></li>
						<li><?php echo htmlspecialchars($this->result[$key]['phone']);?></li>
						<li><?php echo $neatDate;?></li>
						<hr>
						<li><span class="msg-read">Mark as Read</span></li>
						<li><span class="msg-del">Archive</span></li>
						<input type="hidden" value=<?php echo $this->result[$key]['id'];?>></input>
					</ul>
				</div>
				<div class="message-content">
					<p><?php echo htmlspecialchars($this->result[$key]['content']);?></p>
				</div>
			</div>
<?php 	}
	}
}

class userMessage {
	public function __construct() {
		$dbc = new dbc();
		$this->conn = $dbc->connect();
	}
	public function markRead($id) {
		$sql = 'UPDATE message SET viewed = 1 WHERE id = :id';
		$sth = $this->conn->prepare($sql);
		$sth->bindParam(":id", $id);
		$sth->execute();
	}
	public function archiveMsg($id) {
		$sql = 'UPDATE message SET viewed = 1, archived = 1 WHERE id = :id';
		$sth = $this->conn->prepare($sql);
		$sth->bindParam(":id", $id);
		$sth->execute();
	}

}



if(isset($_POST)){ //If data has been posted
	$article = new article(); //Create classes
	$property = new properties();

	if(isset($_POST['article'])){
		if($article->validArticle($_POST['article'])){
			$article->getArticleContent();
		}

	} elseif(isset($_POST['updateArticle'])){
		$article->updateArticle();

	} elseif(isset($_POST['propertySelect']) && (isset($_POST['propertyType']))){ //Ajax request for property
		if($property->validProperty($_POST['propertySelect'], $_POST['propertyType'])){
			$property->getPropertyInfo(0);
		}

	} elseif(isset($_POST['updateProperty'])){
		$property->updateProperty();

	} elseif(isset($_POST['createProperty'])){
		$property->create($_POST['createProperty']);

	} elseif(isset($_POST['createNewProperty'])){
		$property->save();

	} elseif(isset($_POST['logout'])){
		session_destroy();
		header('location: admin.php');

	} elseif(isset($_POST['markMsgRead'])) {
		$message = new userMessage();
		$message->markRead($_POST['markMsgRead']);

	} elseif(isset($_POST['archiveMsg'])) {
		$message = new userMessage();
		$message->archiveMsg($_POST['archiveMsg']);

	} elseif(isset($_POST['viewMessageType'])) {
		if($_POST['viewMessageType'] == "new"){
			$messages = new messages();
			$messages->checkMessages("new");
			$messages->displayMessages();
		} else {
			$messages = new messages();
			$messages->checkMessages('all');
			$messages->displayMessages();
		}
	}
}




?>
