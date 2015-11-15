<?php 
	require("scripts/php/header.php");
	require("scripts/php/login.php");
	require("scripts/php/cms.php");
?>
<main>
	<div id="cms">
<?php
		if(!isset($_SESSION['loggedin'])){ //User not logged in
			$login = new login(); //Display login form
		} else { //User logged in. Display page.
			$article = new article(); 
			$article->listArticles();
			$properties = new properties();
			$properties->listProperties('house');
			$land = new properties();
			$land->listProperties('land');
			$messages = new messages();
			$messages->checkMessages('new');
			
?>

		<div id="messages">
			<button id="viewMessages"><?php echo $messages->newCount;?> New Messages</button>
			<button id="allMessages">Other Messages</button>
		</div>

		<span class="option">
			<label>Select an article to edit:</label>
			<select name="article" id="article_select">
				<option></option>
			<?php 
				foreach($article->results as $value){ ?>
					<option value='<?php echo $value; ?>'><?php echo $value; ?></option>
			<?php }//End foreach ?>
			</select>
			<input id="article_select_button" type="button" name="article_select" value="Edit this article">
		</span>
		
		<span class="option">
			<label>Select a property to edit:</label>
			<select name="property" id="property_select">
			<option></option>
			<?php 
				foreach($properties->results as $value){ ?>
					<option value='<?php echo $value; ?>'><?php echo $value; ?></option>
			<?php }//End foreach ?>
			</select>
			<input id="property_select_button" type="button" name="property_select" value="Edit this property">
			<input id="property_create_button" type="button" name="property_create" value="Add new property">
		</span>
		
		<span class="option">
			<label>Select land to edit:</label>
			<select name="land" id="land_select">
			<option></option>
			<?php 
				foreach($land->results as $value){ ?>
					<option value='<?php echo $value; ?>'><?php echo $value; ?></option>
			<?php }//End foreach ?>
			</select>
			<input id="land_select_button" type="button" name="land_select" value="Edit this land">
			<input id="land_create_button" type="button" name="land_create" value="Add new land">
		</span>

	</div>
	<form name="logout" id="logout" method="post">
		<input type="submit" name="logout" value="Log out">
	</form>
	<?php } //end if loggedin ?>
</main>

<?php include("scripts/php/footer.php"); ?>