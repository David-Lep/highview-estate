<?php
session_start();
ob_start();
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width" />
<?php
if(basename($_SERVER['PHP_SELF']) !== 'contact.php'){ //Prevent refresh resending contact form. Clicking any other page then going back resets
	$_SESSION['sent'] = 0;
}
require_once("scripts/php/functions.php");
$prepare = new prepare();
if(isset($page)){
	$content = new content($page);
}
$config = $prepare->getConfig();
if(isset($content->result['meta_desc'])){ //If custom metadata is set
?>
		<meta name="description" content="<?php echo $content->result['meta_desc']?>">
		<meta name="keywords" content="<?php echo $content->result['meta_keywords']?>"><?php }//end custom metadata ?>
		<link rel="stylesheet" href="application.css.css">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,700,600,300' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,300,100,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
		<script src="ckeditor/ckeditor.js"></script>
		<title><?php 
					if((basename($_SERVER['PHP_SELF']) === 'admin.php')) {
						echo "Backend - Highview Estate";
					} elseif (!empty($_GET['req'])) {
						echo "For Sale - " . ucwords(str_replace("-", " ", $_GET['req']));
						
					} else {
						echo $content->result['page_title'];
					}

					?>
		</title>
	</head>
	<body>
		<header>
			<section id="nav">
				<a href="index.php"><img src="images/site/logo-blue.jpg" alt="Logo image" /></a>
				<div id="branding">
					<button id="nav-btn">&#9776;</button>
				</div>
				<nav role="naviation">
					<ul id="navigation-menu">
						<li><a href="index.php">Home</a></li>
						<li><a href="about.php">About</a></li>
						<li><a href="lifestyle.php">Lifestyle</a></li>
						<li><a href="land-sale.php">Land for Sale</a></li>
						<li><a href="house-sale.php">Houses for Sale</a></li>
						<li><a href="faq.php">FAQ</a></li>
						<li><a href="contact.php">Contact Us</a></li>
				    </ul>
				</nav>
			</section>
		</header>
