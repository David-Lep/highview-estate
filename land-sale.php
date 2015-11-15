<?php
$page = 'land-sale';
include("scripts/php/header.php"); ?>

<main>
	<article class="type-system-slab">
<?php echo $content->result['content']; ?>
	</article>

	<ul class="accordion-tabs-minimal">
<?php
	$preview = new preview('land');
	$preview->properties();
	if(!array_key_exists('result', $preview)){
		echo "[No land found. None for sale currently]";
	} else {
		foreach($preview->result as $key => $value){
?>
		<li class="tab-header-and-content">
			<a href="#" class="tab-link"><?php echo $preview->result[$key]['name']; ?></a>
			<div class="tab-content">
				<!--<img class="accordion-img" src="images/properties/land/lot-32-land.jpg" />-->
				<img class="accordion-img" src="<?php echo $config['feat_img_loc'] . $preview->result[$key]['feat_img'];?>" />
				<h3><?php echo $preview->result[$key]['name']; ?></h3>
				<?php echo $preview->result[$key]['prop_head']; ?>
				<div class="stats">
					<ul>
						<li><?php echo $preview->result[$key]['prop_size']; ?>&#13217;<span>Size</span></li>
						<li><?php if($preview->result[$key]['prop_dual_occupy'] == 0){
							echo 'No';
							} else {
							echo 'Yes';
							};?><span>Dual Occupancy</span></li>
						<li><?php echo $preview->result[$key]['prop_price']; ?><span>Starting From</span></li>
					</ul>
				</div>
			</div>
		</li>
<?php
	};
};
?>
	</ul>
</main>
<?php include("scripts/php/footer.php"); ?>
