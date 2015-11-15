<?php 
$page = 'index';
include("scripts/php/header.php"); ?>

<main>
	<article class="type-system-slab">
<?php echo $content->result['content']; ?>
	</article>

	<div class="video">
		<h2>Highview Estate Fly-Over</h2>
		<div class="video-wrapper">
			<iframe src="//www.youtube.com/embed/l6NxqtEhaRI?modestbranding=1&;?rel=0&;showinfo=0&;controls=0;" frameborder="0" allowfullscreen></iframe>
		</div>
	</div>
	<hr>
	<div class="cards">
		<div class="card">
			<a href="land-sale.php">
				<div class="card-image">
					<img src="images/site/nav-aerialview.jpg" alt="">
				</div>
				<div class="card-header">Land for Sale</div>
				<div class="card-copy">
					<p>Highview Estate offers premium land for sale in Jindabyne, starting from <?php echo $config['land_start_price'];?></p>
				</div>
			</a>
		</div>

		<div class="card">
			<a href="lifestyle.php">
				<div class="card-image">
					<img src="images/site/nav-snowboarders.jpg" alt="Snowboarders">
				</div>
				<div class="card-header">Jindabyne Activities</div>
				<div class="card-copy">
					<p>Located only minutes away from some of Australia's top ski fields, with the year round beauty of the mountainous region.</p>
				</div>
			</a>
		</div>

		<div class="card">
			<a href="house-sale.php">
				<div class="card-image">
					<img src="images/site/nav-house.jpg" alt="House">
				</div>
				<div class="card-header">House and Land Packages</div>
				<div class="card-copy">
					<p>House and land packages starting from just <?php echo $config['house_start_price'];?></p>
				</div>
			</a>
		</div>
	</div>
	<hr> 
</main>

<?php include("scripts/php/footer.php"); ?>