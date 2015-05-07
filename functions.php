<?php
/**
 * functions.php
 *
 * @package inspirations
 */


/**
 * Register menu areas.
 *
 */

function register_inspirations_menus() {
	register_nav_menus(
		array(
			'extra-menu' => __( 'Footer Menu' )
		)
	);
}
add_action( 'init', 'register_inspirations_menus' );


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
	<div class="container">
		<div class="row">
			<div class="col-md-12">
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











/**
 * Add page meta boxes
 */
function inpirations_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'inspirations_sectionid',
			__( 'Inspirations formatting', 'inspirations' ),
			'inspirations_meta_box_callback',
			$screen
		);
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
	$value = get_post_meta( $post->ID, '_inspirations_meta_value_key', true );

	echo '<label for="inspirations_new_field">';
	_e( 'Description for this field', 'inspirations' );
	echo '</label> ';
	echo '<input type="text" id="inspirations_new_field" name="inspirations_new_field" value="' . esc_attr( $value ) . '" size="25" />';
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
	if ( ! isset( $_POST['inspirations_new_field'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['inspirations_new_field'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_inspirations_meta_value_key', $my_data );
}
add_action( 'save_post', 'inspirations_save_meta_box_data' );