<?php
/**
 * Template part to display the post/page header with sidebar content
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

		<div class="page-color page-header-image col-md-8 col-sm-12">
			<?php echo the_post_thumbnail( $size, $attr ); ?>
		</div>

		<div class="page-color page-header-content col-md-4 col-sm-12">
			<?php _e( htmlspecialchars_decode(inspirations_hero_content()) ); ?>
		</div>

		<div class="page-color page-header-bottom clear">
		</div>

	</header><!-- .entry-header -->

</div>