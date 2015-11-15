<?php 
$page = 'about';
include("scripts/php/header.php"); ?>

<main>
	<article class="type-system-slab">
<?php echo $content->result['content']; ?>
	</article>


	<div class="side-image">
		<div class="images-wrapper"></div>
		<div class="side-image-content">
		<h4>Lifestyle</h4>
		<h1>Living the Highview Estate Lifestyle</h1>
		<p>Picture yourself stepping out of your luxury architect designed home, at Highview Estate in the heart of Jindabyne, located only minutes away from carving up the slopes at some of Australia's top ski fields and experiencing the beauty of the mountains all year round.</p>
		<a href="lifestyle.php"><button>Read more</button></a>
		</div>
	</div>
	<hr>
</main>

<?php include("scripts/php/footer.php"); ?>