<?php
$page = 'contact';
include("scripts/php/header.php");
include("scripts/php/contact-form.php");


 ?>


<main>
<?php
if($_SESSION['sent'] === 1){  ?>
	<article class="type-system-slab">
		<h2>Your message has been sent. We will get back to you as soon as possible.</h2>
	</article>
<?php } else { 
		new contactForm();
	};
?>
	<div id="map_canvas"></div>
	<script src="https://maps.googleapis.com/maps/api/js"></script>
	<!--<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1605.2393253274115!2d148.6120031362422!3d-36.42180711390671!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b3ccbce1a361eb7%3A0x2cfd32e6dc3ab117!2sHighview+Estate+Properties!5e0!3m2!1sen!2sus!4v1428885021620" width="800" height="600" frameborder="0" style="border:0"></iframe>-->
</main>

<?php include ("scripts/php/footer.php"); ?>