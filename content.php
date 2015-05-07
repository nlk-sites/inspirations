<?php
/**
 * The default content display page
 *
 * @package pgb
 */
?>

	<div class="entry-content col-md-12">
		
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'inspirations' ) ); ?>
		
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'inspirations' ),
				'after'  => '</div>',
			) );
		?>
	
	</div><!-- .entry-content -->

