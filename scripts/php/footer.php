    <!--<div id="js-parallax-window" class="parallax-window">
    <div class="parallax-static-content"></div>
    <div id="js-parallax-background" class="parallax-background"></div>-->
</div>

<footer class="footer" role="contentinfo">
    <div class="footer-logo">
        <img src="images/site/logo-blue.jpg" alt="Logo image">
    </div>
    <div class="footer-links">
        <ul>
			<li><h3>Content</h3></li>
			<li><a href="index.php">Home</a></li>
			<li><a href="about.php">About</a></li>
			<li><a href="lifestyle.php">Lifestyle</a></li>
			<li><a href="land-sale.php">Land for Sale</a></li>
			<li><a href="house-sale.php">Houses for Sale</a></li>
			<li><a href="faq.php">FAQ</a></li>
			<li><a href="contact.php">Contact Us</a></li>
		</ul>


<?php
	$menu = new menu();
	$menu->footerMenu('house');
    
    if($menu->count > 0) { //Menu items were found
?>
        <ul>
            <li><h3>Properties for Sale</h3></li>
<?php
    	foreach($menu->result as $value => $key){ ?>
			<li>
                <a href='house.php?type=house&amp;req=<?php echo $menu->result[$value]['url']?>'>
                    <?php echo $menu->result[$value]['name']?>
                </a>
            </li>
<?php    } ?>
        </ul>
<?php } ?>


<?php
    $menu->footerMenu('land');
    if($menu->count > 0) { ?>
        <ul>
            <li><h3>Land for Sale</h3></li>
<?php    foreach($menu->result as $value => $key){ ?>

			<li>
                <a href='land-sale.php'>
                    <?php echo $menu->result[$value]['name']?>
                </a>
            </li>
<?php } ?>
    </ul>
<?php }?>

    </div>
    <hr>
    <p>Visit us on <a href="https://plus.google.com/100170636015239822498/about" rel="publisher">Google+</a></p>



  </footer>



	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="scripts/javascript/bourbon.js"></script>
	<script src="scripts/javascript/jquery.flexslider.js"></script>
	<script src="scripts/javascript/notify.min.js"></script>
	<script src="scripts/javascript/scripts.js"></script>
	<?php
    	if($_SESSION['notFound']) {
        	$_SESSION['notFound'] = null;?>
        <script type="text/javascript">$.notify("Page not found", "info");</script>
    	<?php
	}?>
  </body>
</html>
