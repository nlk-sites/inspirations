<?php
/**
 * Template Name: Front Page
 *
 * @package pgb
 */

get_header(); ?>

	<?php get_template_part( 'posts', 'contentheader' ); ?>

		<?php tha_content_top(); ?>

		<?php // <!--The Loop ?>

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php tha_entry_before(); ?>

					<?php tha_entry_top(); ?>

					<?php //get_template_part( 'posts', 'header' ); ?>

					<div class="col-md-12">
						<div class="row front-page-widgets">
							<?php dynamic_sidebar( 'frontpage-widget' ); ?>
							<?php dynamic_sidebar( 'frontpage-widget-2' ); ?>
							<?php dynamic_sidebar( 'frontpage-widget-3' ); ?>
						</div>
					</div>

					<?php //get_template_part( 'posts', 'footer' ); ?>

					<?php tha_entry_bottom(); ?>

				<?php tha_entry_after(); ?>

			<?php endwhile; // end of the loop. ?>

		<?php endif; ?>

		<?php // The Loop--> ?>

		<?php tha_content_bottom(); ?>

<?php get_footer(); ?>
