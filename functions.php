<?php
/**
 * functions.php
 *
 * @package inspirations
 */


/**
 * Inspirations Init
 *
 */
function inspirations_init() {
	// Register new nav menu area
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );

	// Remove PGB blocks
	remove_action( 'pgb_block_header', 'pgb_load_block_header', 10 );

}
add_action( 'init', 'inspirations_init' );



/**
 * Enqueue front-end scripts
 *
 */
function inpirations_scripts() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	//wp_enqueue_style( 'bxslider-css', get_stylesheet_directory_uri() . '/includes/css/jquery.bxslider.css' );
	wp_enqueue_script( 'inspirations-js', get_stylesheet_directory_uri() . '/includes/js/jquery.bxslider.min.js', array('jquery'), '', true );
	wp_enqueue_script( 'bxslider-js', get_stylesheet_directory_uri() . '/includes/js/inspirations.js', array('jquery'), '', true );
}
add_action( 'wp_enqueue_scripts', 'inpirations_scripts' );


/**
 * Enqueue special scripts for various media objects in admin
 *
 */
function problogger_options_scripts(){
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'media-upload' );
	wp_register_script( 'inspirations-options-js', get_stylesheet_directory_uri() . '/includes/js/media.js', array('jquery','media-upload','thickbox') );
	wp_enqueue_script( 'inspirations-options-js' );
}
if ( is_admin() || (isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit') ) {
	// loads on admin ProBlogger options and Page edit pages
	add_action( 'admin_enqueue_scripts', 'problogger_options_scripts' );
}




add_filter('widget_text', 'do_shortcode');

/**
 * Register widgetized areas.
 *
 */
function inspirations_widgets_init() {

	// Register new Widget areas
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

	register_sidebars(3,array(
        'name' 			=> 'Front Page Widget %d',
        'id' 			=> 'frontpage-widget',
        'description' 	=> 'Front Page Widget Area',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' 	=> '</div>',
        'before_title' 	=> '<h2 class="widget-title">',
        'after_title' 	=> '</h2>',
    ));

	// Register new custom widgets
    register_widget( 'Inspirations_Widget_Text' );
    register_widget( 'Inspirations_Widget_Video' );
    register_widget( 'Inspirations_Widget_Testimonials' );

}
add_action( 'widgets_init', 'inspirations_widgets_init' );


/**
 * Content Blocks to add and remove...
 *
 */


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

	$post_id = isset($_GET['post']) ? $_GET['post'] : ( isset($_POST['post_ID']) ? $_POST['post_ID'] : false );
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

		if( $template_file == 'page-halfhero.php' || $template_file == 'page-maphero.php' || $template_file == 'front-page.php' ) {
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
	global $post, $wp_query;
	//$postid = $wp_query->post->ID;
	$postid = $post->ID;
	$color = get_post_meta( $postid, '_inspirations_meta_page_color', true );
	return ( $color ? $color : '#0d8092' );
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
 * Custom Front Page Text widget class
 *
 * @since 2.8.0
 */
class Inspirations_Widget_Text extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_text', 'description' => __('Arbitrary text or HTML.'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('front_page_text', __('Inspirations: Front Page Text'), $widget_ops, $control_ops);
	}

	public function widget( $args, $instance ) {

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$color = empty( $instance['color'] ) ? '' : $instance['color'];
		$bg = empty( $instance['bground'] ) ? '' : '<img src="' . htmlspecialchars_decode($instance['bground']) . '" />';
		$link = empty( $instance['link'] ) ? $bg : '<a href="' . htmlspecialchars_decode($instance['link']) . '">' . $bg . '</a>';

		/**
		 * Filter the content of the Text widget.
		 *
		 * @since 2.3.0
		 *
		 * @param string    $widget_text The widget content.
		 * @param WP_Widget $instance    WP_Widget instance.
		 */
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		echo '<div class="front-page-widget col-xs-12 col-sm-4">
			<div class="row front-page-widget-color-bar" style="background-color:'.$color.'; height:17px;"></div>' . $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . '<span style="color:'.$color.';">' . $title . '</span>' . $args['after_title'];
		} ?>
			<div class="textwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
		<?php
		echo $args['after_widget'] . '<div class="front-page-widget-img">'.$link.'</div></div>';
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['color'] = $new_instance['color'];
		$instance['bground'] = esc_html($new_instance['bground']);
		$instance['link'] = esc_html($new_instance['link']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = ! empty( $new_instance['filter'] );
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'color' => '#ffffff', 'bground' => '', 'link' => '' ) );
		$title = strip_tags($instance['title']);
		$color = $instance['color'];
		$bg = htmlspecialchars_decode($instance['bground']);
		$link = htmlspecialchars_decode($instance['link']);
		$text = esc_textarea($instance['text']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Color:'); ?></label><br />
		<input type="text" name="<?php echo $this->get_field_name('color'); ?>" value="<?php echo esc_attr($color); ?>" class="wp-color-picker-field" data-default-color="" /></p>
		
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p><label for="<?php echo $this->get_field_id('bground'); ?>" class="upload"><?php _e( 'Background Image' ); ?>
			<input type="text" id="<?php echo $this->get_field_id('bground'); ?>" class="text-upload" name="<?php echo $this->get_field_name('bground'); ?>" value="<?php echo $bg; ?>" />
			<input type="button" class="button button-upload" value="Upload"/>
			</br>
			<img style="max-width: 100%; display: block; margin: 10px 0;" src="<?php echo $bg; ?>" class="preview-upload" />
		</label></p>

		<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Image Link URL:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($link); ?>" /></p>
		
		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
		<?php
	}
}

/**
 * Custom Front Page Text widget class
 *
 * @since 2.8.0
 */
class Inspirations_Widget_Video extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_text', 'description' => __('Arbitrary text or HTML.'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('sidebar_video', __('Inspirations: Sidebar Video'), $widget_ops, $control_ops);
	}

	public function widget( $args, $instance ) {

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$bg = empty( $instance['bground'] ) ? '' : '<img src="' . htmlspecialchars_decode($instance['bground']) . '" />';
		$link = empty( $instance['link'] ) ? '<a href="#missing-video">' . $bg . '</a>' : '<a href="' . htmlspecialchars_decode($instance['link']) . '">' . $bg . '</a>';

		/**
		 * Filter the content of the Text widget.
		 *
		 * @since 2.3.0
		 *
		 * @param string    $widget_text The widget content.
		 * @param WP_Widget $instance    WP_Widget instance.
		 */
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
			<div class="textwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
			
		<?php
		echo $args['after_widget'];
		?>
		<div class="sidebar-video-widget"><?php echo $link; ?></div>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['bground'] = esc_html($new_instance['bground']);
		$instance['link'] = esc_html($new_instance['link']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = ! empty( $new_instance['filter'] );
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'bground' => '', 'link' => '' ) );
		$title = strip_tags($instance['title']);
		$bg = htmlspecialchars_decode($instance['bground']);
		$link = htmlspecialchars_decode($instance['link']);
		$text = esc_textarea($instance['text']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p><label for="<?php echo $this->get_field_id('bground'); ?>" class="upload"><?php _e( 'Background Image' ); ?>
			<input type="text" id="<?php echo $this->get_field_id('bground'); ?>" class="text-upload" name="<?php echo $this->get_field_name('bground'); ?>" value="<?php echo $bg; ?>" />
			<input type="button" class="button button-upload" value="Upload"/>
			</br>
			<img style="max-width: 100%; display: block; margin: 10px 0;" src="<?php echo $bg; ?>" class="preview-upload" />
		</label></p>

		<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Image Link URL:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($link); ?>" /></p>
		
		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
		<?php
	}
}



/**
 * Custom Testimonials widget class
 *
 * @since 2.8.0
 */
class Inspirations_Widget_Testimonials extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_text', 'description' => __('Arbitrary text or HTML.'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('testimnonials_widget', __('Inspirations: Testimonials Widget'), $widget_ops, $control_ops);
	}

	public function widget( $args, $instance ) {

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$sepimg = empty( $instance['sepimg'] ) ? '' : '<p><img src="' . htmlspecialchars_decode($instance['sepimg']) . '" /></p>';

		/**
		 * Filter the content of the Text widget.
		 *
		 * @since 2.3.0
		 *
		 * @param string    $widget_text The widget content.
		 * @param WP_Widget $instance    WP_Widget instance.
		 */
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
			echo $sepimg;
		} ?>
		<ul class="bxslider">
			<?php insp_testimonials_output(); ?>
		</ul>
		<?php
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['sepimg'] = esc_html($new_instance['sepimg']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = ! empty( $new_instance['filter'] );
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'sepimg' => '' ) );
		$title = strip_tags($instance['title']);
		$text = esc_textarea($instance['text']);
		$sepimg = htmlspecialchars_decode($instance['sepimg']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('sepimg'); ?>" class="upload"><?php _e( 'Separator Image' ); ?>
			<input type="text" id="<?php echo $this->get_field_id('sepimg'); ?>" class="text-upload" name="<?php echo $this->get_field_name('sepimg'); ?>" value="<?php echo $sepimg; ?>" />
			<input type="button" class="button button-upload" value="Upload"/>
			</br>
			<img style="max-width: 100%; display: block; margin: 10px 0;" src="<?php echo $sepimg; ?>" class="preview-upload" />
		</label></p>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
		<?php
	}
}



function insp_testimonials_init() {	
	// add "Testimonials" Custom Post Type
	register_post_type( 'insp_testimonials',
		array(
			'labels' => array(
				'name' => 'Customer Testimonials',
				'singular_name' => 'Testimonial',
				'add_new_item' => 'Add New Testimonial',
				'edit_item' => 'Edit Testimonial',
				'new_item' => 'New Testimonial',
				'view_item' => 'View Testimonial',
				'search_items' => 'Search Testimonials',
				'not_found' =>  'No testimonial found',
				'not_found_in_trash' => 'No testimonials found in Trash', 
				'parent_item_colon' => '',
				'menu_name' => 'Testimonials'
			),
			'public' => true,
			'public_queryable' => true,
			'exclude_from_search' => true,
			'show_in_menu' => true,
			'menu_position' => 41,
			'hierarchical' => false,
			'supports' => array('title','editor','revisions','page-attributes'),
			'register_meta_box_cb' => 'insp_testimonials_metaboxes',
			'taxonomies' => array('insp_testimonials_cats'),
		)
	);
	
	$labels = array(
		'name' => _x( 'Testimonial Categories', 'taxonomy general name' ),
		'singular_name' => _x( 'Testimonial Category', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Testimonial Categories' ),
		'all_items' => __( 'All Testimonial Categories' ),
		'parent_item' => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item' => __( 'Edit Testimonial Category' ), 
		'update_item' => __( 'Update Category' ),
		'add_new_item' => __( 'Add New Testimonial Category' ),
		'new_item_name' => __( 'New Testimonial Category Name' ),
		'menu_name' => __( 'Categories' ),
	); 	
	
	register_taxonomy('insp_testimonials_cats',array('insp_testimonials'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'testimonialcats' ),
	));
}
add_action( 'init', 'insp_testimonials_init' );


function insp_testimonials_metaboxes() {
	add_meta_box("insp_testimonials_metabox", "Author Info", "insp_testimonials_metabox", "insp_testimonials", "normal", "high");
}

function insp_testimonials_metabox() {
	global $post;
	$custom = get_post_meta($post->ID,'_insp_testimonials');
	$auth = isset($custom[0]) ? $custom[0] : '';
	$defaults = array(
		'auth' => 'John Q Smith',
		'loc' => ''
	);
	if ( $auth == '' ) {
		$auth = $defaults;
	} else {
		$auth = array_merge($defaults, $auth);
	}
	?>
    <table width="100%">
    <tr><td><label for="insp_testimonials_author[auth]">Name</label></td>
    <td><input type="text" name="insp_testimonials_author[auth]" value="<?php echo esc_attr($auth['auth']); ?>" size="60" maxlength="60" /></td></tr>
    <tr><td><label for="insp_testimonials_author[loc]">Location/Title</label></td>
    <td><input type="text" name="insp_testimonials_author[loc]" value="<?php echo esc_attr($auth['loc']); ?>" size="60" maxlength="60" /></td></tr>
    </table>
<?php
}

function insp_testimonials_save($post_id){
	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	return $post_id;
	
	
	// Check permissions
	if ( isset($_POST['post_type']) && $_POST['post_type'] == 'insp_testimonials' ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) return $post_id;
	} else {
	//if ( !current_user_can( 'edit_post', $post_id ) )
	  return $post_id;
	}
	
	// OK, we're authenticated: we need to find and save the data
	$auth = $_POST['insp_testimonials_author'];
	// sanitize
	$fields = array( 'auth', 'loc' );
	foreach ( $fields as $field ) {
		$auth[$field] = wp_kses( substr( $auth[$field], 0, 60 ), array() );
	}
	
	update_post_meta($post_id, "_insp_testimonials", $auth);
	return $auth;
}
add_action('save_post', 'insp_testimonials_save');

function insp_testimonials_output() {
	$args = array( 'post_type' => 'insp_testimonials' );
	$testimonial_posts = get_posts( $args );
	foreach ( $testimonial_posts as $post ) : setup_postdata($post); ?>
	<?php $custom = get_post_meta( $post->ID, '_insp_testimonials', true ); ?>
		<li class="testimonial-slide">
			<?php echo the_content(); ?>
			<p class="author"><?php echo $custom['auth']; ?></p>
		</li>
	<?php unset( $custom ); ?>
	<?php endforeach; 
	wp_reset_postdata();
}
