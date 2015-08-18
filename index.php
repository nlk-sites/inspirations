<?php
/**
 * The main template file.
 *
 * @package pgb
 */

get_header(); ?>

	<div class="col-md-12">

		<header class="page-header row">

			<div class="page-color page-header-top col-md-12">
				<?php 
				if ( function_exists('blog_page_title') ) { 
					blog_page_title( '<h1 class="page-title">', '</h1>' ); 
				} else { ?>
					<h1 class="page-title">Blog</h1>
				<?php  } ?>
			</div>

		</header><!-- .entry-header -->

	</div>

	<div id="content" class="main-content-inner col-sm-12 col-md-8 col-lg-9" data-file="index.php">

		<?php tha_content_top(); ?>

		<?php // <!--The Loop ?>

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php tha_entry_before(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'row' ); ?>>

					<?php tha_entry_top(); ?>

					<div class="col-md-12">

						<header class="page-header row">

							<div class="page-header-top col-md-12">
								<?php the_title( '<h2 class="page-title"><a href="'.get_permalink().'" >', '</a></h2>' ); ?>
							</div>

						</header><!-- .entry-header -->

					</div>

					<div class="col-md-12">

						<div class="row">

							<?php get_template_part( 'content', get_post_format() ); ?>

						</div>

					</div>

					<?php get_template_part( 'posts', 'footer' ); ?>

					<?php tha_entry_bottom(); ?>

				</article><!-- #post-## -->

				<?php tha_entry_after(); ?>

			<?php endwhile; ?>

			<?php pgb_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'index' ); ?>

		<?php endif; ?>

		<?php // The Loop--> ?>

		<?php tha_content_bottom(); ?>

	</div>

	<?php get_sidebar(); ?>

<?php get_footer(); ?>