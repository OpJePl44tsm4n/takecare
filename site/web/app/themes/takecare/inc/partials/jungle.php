<?php

$leaftop = trailingslashit(get_stylesheet_directory_uri()) . 'assets/dist/img/jungle-svg-01-top.svg';
$rightback = trailingslashit(get_stylesheet_directory_uri()) . 'assets/dist/img/jungle-svg-02-rechts-achter.svg';
$leftback = trailingslashit(get_stylesheet_directory_uri()) . 'assets/dist/img/jungle-svg-03-links-achter.svg';
$rightfront = trailingslashit(get_stylesheet_directory_uri()) . 'assets/dist/img/jungle-svg-04-rechts-voor.svg';
$leftfront = trailingslashit(get_stylesheet_directory_uri()) . 'assets/dist/img/jungle-svg-05-links-voor.svg';

?>
<div class="jungle_bg">
	<div class="leftback"><img class="img-fluid" src="<?php echo $leftback ?>" alt="Logo"></div>
	<div class="left"><img class="img-fluid" src="<?php echo $leftfront ?>" alt="Logo"></div>

	<div class="top"><img class="img-fluid" src="<?php echo $leaftop ?>" alt="Logo"></div>
	<div class="rightback"><img class="img-fluid" src="<?php echo $rightback ?>" alt="Logo"></div>
	<div class="right"><img class="img-fluid" src="<?php echo $rightfront ?>" alt="Logo"></div>
	
</div>