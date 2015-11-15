<?php
$page = 'house-sale';
include("scripts/php/header.php");
?>

<main>
	<article class="type-system-slab">
<?php echo $content->result['content']; ?>
	</article>

	<div class="flex-boxes-custom">
<?php
	$preview = new preview('house');
	$preview->properties();
	if(!array_key_exists('result', $preview)){
		echo "[No Properties found. None for sale currently]";
	} else {
		foreach($preview->result as $key => $value){
?>
	<a href="house.php?type=house&req=<?php echo $preview->result[$key]['url']; ?>" class="flex-box medium-box">
		<img src="<?php echo $config['feat_img_loc'] . $preview->result[$key]['feat_img'];?>" />
		<h1 class="flex-title"><?php echo $preview->result[$key]['name']; ?></h1>
		<p><?php echo $preview->result[$key]['prop_head']; ?></p>
		<p class="priceGuide price-guide">Price Guide: <?php echo $preview->result[$key]['prop_price']; ?></p>
	</a>
<?php
	}//end loop
}; //End foreac
?>
	</div>
</main>
<?php include("scripts/php/footer.php"); ?>
