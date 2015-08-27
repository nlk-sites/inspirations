<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package pgb
 */
$options = pgb_get_options();
?>
				
		</div><!-- close .row -->
	</div><!-- close .container -->
	<?php tha_content_after(); ?>
</div><!-- close .main-content -->

<!-- Footer widget area -->
<div class="footerwidgetarea">
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-widget') ) : ?>
			<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php tha_footer_before(); ?>
<footer id="colophon" class="site-footer" role="contentinfo">
	<?php tha_footer_top(); ?>
	<div class="container">
		<div class="row">
			<div class="site-footer-inner-top col-sm-12">
				<?php echo pgb_get_mobile_logo( 'pull-left' ); ?>
				<?php // Main Menu
				wp_nav_menu(
					array(
						'theme_location' => 'footer-menu',
						'container' => false,
						//'container_class' => 'top-view-primary-menu',
						'menu_class' => 'nav navbar-nav ',
						'fallback_cb' => '',
						'menu_id' => 'footer-menu',
						'walker' => new wp_bootstrap_navwalker()
					)
				);
				?>
				<?php if ( is_active_sidebar( 'footer_social' ) ) : ?>
					<div class="pull-right"><?php dynamic_sidebar( 'footer_social' ); ?></div>
				<?php endif; ?>
			</div>
			<div class="site-footer-inner col-sm-12">

				<div class="site-info">
					<?php do_action( 'pgb_credits' ); ?>
				</div><!-- close .site-info -->

			</div>
		</div>
	</div><!-- close .container -->
	<?php tha_footer_bottom(); ?>
</footer><!-- close #colophon -->
<?php tha_footer_after(); ?>
<?php tha_body_bottom(); ?>
<?php wp_footer(); ?>
</body>
</html>