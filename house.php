<?php
$page = 'house';
include("scripts/php/header.php");
$property = new property($_GET['req'], $_GET['type']);
if(!$property->display()){
  echo "Sorry, the page you're looking for could not be found.";
} else {
?>


<main>
	<article class="type-system-slab house-sale-article" id="scroll-on-page-top">
		<h1><?php echo $property->prop_info['name']; ?></h1>
		<h2><?php echo $property->prop_info['prop_head']; ?></h2>
		<p><?php echo $property->prop_info['prop_intro']; ?><a class="scroll-on-page-link" href="#scroll-link-1"> Read more <span>&#187;</span></a></p>
		<!--<img id="featuredImage" src="images/properties/homes/lot-23b.jpg" />-->
		<!--<img id="featuredImage" src="images/site/nav-house.jpg" />-->
        <img id="featuredImage" src="<?php echo $config['feat_img_loc'] . $property->prop_info['feat_img'];?>" />

        <!--
		<div id="image-viewer">
			<div class="cards">
				<div class="card-image">
					<img src="images/properties/homes/lot-23b.jpg" alt="">
				</div>

				<div class="card-image">
					<img src="images/site/nav-snowboarders.jpg" alt="Snowboarders">
				</div>

				<div class="card-image">
					<img src="images/site/nav-house.jpg" alt="House">
				</div>
				<div class="card-image">
					<img src="images/site/nav-snowboarders.jpg" alt="House">
				</div>
			</div>
		</div>
        -->
		<div id="stats-container">
			<div class="stats" id="scroll-link-1">
				<ul>
					<li><?php echo $property->prop_info['prop_bedrooms']; ?><span>Bedrooms</span></li>
					<li><?php echo $property->prop_info['prop_bathrooms']; ?><span>Bathrooms</span></li>
					<li><?php echo $property->prop_info['prop_price']; ?><span>Price</span></li>
				</ul>
			</div>
		</div>
		<div id="property-overview">
			<h4>Description</h4>
				<?php echo $property->prop_info['prop_overview']; ?>
		</div>
		<hr>
		<a href="contact.php">Contact Us</a> for more information. <a class="scroll-on-page-link" href="#scroll-on-page-top">Back to top</a>
	</article>
<?php
}; // End page->display()
?>
</main>
<?php include("scripts/php/footer.php"); ?>
