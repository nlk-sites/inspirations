<?php
/**
 * functions.php
 *
 * @package inspirations
 */

function inpirations_scripts() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'inpirations_scripts' );



/**
 * Register menu areas.
 *
 */

function register_inspirations_menus() {
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );
}
add_action( 'init', 'register_inspirations_menus' );


add_filter('widget_text', 'do_shortcode');

/**
 * Register widgetized areas.
 *
 */
function inspirations_widgets_init() {

	register_sidebar( 
		array(
			'name'          => 'Header Top Left',
			'id'            => 'header_top_left',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="rounded">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar( 
		array(
			'name'          => 'Header Top Right',
			'id'            => 'header_top_right',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="rounded">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar( 
		array(
			'name'          => 'Header Social',
			'id'            => 'header_social',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="rounded">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar( 
		array(
			'name'          => 'Footer Social',
			'id'            => 'footer_social',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="rounded">',
			'after_title'   => '</h2>',
		)
	);

}
add_action( 'widgets_init', 'inspirations_widgets_init' );


/**
 * Content Blocks to add and remove...
 *
 */
add_action('init', 'remove_pgb_blocks');
function remove_pgb_blocks() {
	remove_action( 'pgb_block_header', 'pgb_load_block_header', 10 );
}

add_action( 'tha_header_before', 'inspirations_top_bar' );
function inspirations_top_bar() {
	//something...
	?>
	<div class="top-header container">
		<div class="row">
			<div class="col-md-12 hidden-xs">
				<?php if ( is_active_sidebar( 'header_top_left' ) ) : ?>
					<div class="pull-left"><?php dynamic_sidebar( 'header_top_left' ); ?></div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'header_social' ) ) : ?>
					<div class="pull-right"><?php dynamic_sidebar( 'header_social' ); ?></div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'header_top_right' ) ) : ?>
					<div class="pull-right"><?php dynamic_sidebar( 'header_top_right' ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}





function hex2rgb( $colour ) {
        if ( !empty($colour) && $colour[0] == '#' ) {
                $colour = substr( $colour, 1 );
        }
        if ( strlen( $colour ) == 6 ) {
                list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
        } elseif ( strlen( $colour ) == 3 ) {
                list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
        } else {
                return false;
        }
        $r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );
        return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}





/**
 * Add page meta boxes
 */
function inpirations_add_meta_box() {

	$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
	$template_file = get_post_meta($post_id,'_wp_page_template',TRUE);

	$screens = array( 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'inspirations_page_color_selector',
			__( 'Inspirations Format', 'inspirations' ),
			'inspirations_meta_box_callback',
			$screen, 
			'side',
			'core'
		);

		if( $template_file == 'page-halfhero.php' || $template_file == 'page-maphero.php' ) {
			add_meta_box(
				'inspirations_page_hero_content',
				__( 'Hero Sidebar', 'inspirations' ),
				'inspirations_meta_box_callback_2',
				$screen
			);
		}
	}
}
add_action( 'add_meta_boxes', 'inpirations_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function inspirations_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'inspirations_meta_box', 'inspirations_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_inspirations_meta_page_color', true );

	echo '<label for="inspirations_page_color">';
	_e( 'Page Primary Color', 'inspirations' );
	echo '</label>';
	echo '<p><input type="text" name="inspirations_page_color" value="' . esc_attr( $value ) . '" class="wp-color-picker-field" data-default-color="" /></p>';
}
function inspirations_meta_box_callback_2( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'inspirations_meta_box_2', 'inspirations_meta_box_nonce_2' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_inspirations_meta_page_hero', true );

	echo '<label for="inspirations_page_hero">';
	_e( 'Page Primary Color', 'inspirations' );
	echo '</label>';
	wp_editor( htmlspecialchars_decode( $value ), 'inspirations_page_hero' );
	
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function inspirations_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['inspirations_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['inspirations_meta_box_nonce'], 'inspirations_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['inspirations_page_color'] ) ) {
		return;
	}
	$my_data = sanitize_text_field( $_POST['inspirations_page_color'] );
	update_post_meta( $post_id, '_inspirations_meta_page_color', $my_data );

}
add_action( 'save_post', 'inspirations_save_meta_box_data' );
function inspirations_save_meta_box_data_2( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['inspirations_meta_box_nonce_2'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['inspirations_meta_box_nonce_2'], 'inspirations_meta_box_2' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['inspirations_page_hero'] ) ) {
		return;
	}
	$my_data = ( esc_html( $_POST['inspirations_page_hero'] ) );
	update_post_meta( $post_id, '_inspirations_meta_page_hero', $my_data );
	
}
add_action( 'save_post', 'inspirations_save_meta_box_data_2' );

/**
 * Get page color
 *
 */
function inspirations_page_color(){
	global $wp_query;
	$postid = $wp_query->post->ID;
	return get_post_meta( $postid, '_inspirations_meta_page_color', true );
}

/**
 * Get hero content
 *
 */
function inspirations_hero_content(){
	global $wp_query;
	$postid = $wp_query->post->ID;
	return get_post_meta( $postid, '_inspirations_meta_page_hero', true );
}



/**
 * Video Custom Widget
 *
 * @since 1.0
 */
function show_custom_video_widget() {
	?>

		<aside id="inspirations-video-custom-widget" class="widget">
			<a href="https://www.youtube.com/watch?v=DVvBURjTXFo" target="_blank">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/video_image.png" />
			</a>
		</aside>

	<?php
}
add_action( 'tha_sidebar_bottom', 'show_custom_video_widget', 10 );
