<?php
/**
 * Template part to display the post/page header with map and sidebar content
 *
 * @package pgb
 */

$size = 'full';

$attr = array(
	'class' => 'img-responsive center-block'
);


?>

<div class="col-md-12">

	<header class="page-header with-content row page-color">

		<div class="page-color page-header-top col-md-12">
			<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
		</div>

		<div class="page-color page-header-map col-md-8 col-sm-12">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3219.7495244227434!2d-115.99812299999999!3d36.196973!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c6382a50b6106d%3A0xe85c01d44a9f1a3a!2sInspirations+Senior+Living!5e0!3m2!1sen!2sus!4v1434659012410" width="600" height="450" frameborder="0" style="border:0"></iframe>
		</div>

		<div class="page-color page-header-content col-md-4 col-sm-12">
			<?php _e( htmlspecialchars_decode(inspirations_hero_content()) ); ?>
		</div>

		<div class="page-color page-header-bottom clear">
		</div>

	</header><!-- .entry-header -->

</div>