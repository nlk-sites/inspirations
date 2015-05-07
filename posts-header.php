<?php
/**
 * Template part to display the post/page header
 *
 * @package pgb
 */

$size = 'full';

$attr = array(
	'class' => 'img-responsive center-block'
);


?>

<div class="col-md-12">

	<header class="page-header row">

		<div class="page-color page-header-top col-md-12">
			<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
		</div>

		<div class="page-color page-header-image">
			<?php echo the_post_thumbnail( $size, $attr ); ?>
		</div>

		<div class="page-color page-header-bottom">
		</div>

	</header><!-- .entry-header -->

</div>