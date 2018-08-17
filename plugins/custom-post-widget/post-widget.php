<?php

// Add featured image support
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}

// First create the widget for the admin panel
class custom_post_widget extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'classname' => 'widget_custom_post_widget', 'description' => __( 'Displays custom post content in a widget', 'custom-post-widget' ) );
		parent::__construct( 'custom_post_widget', __( 'Content Block', 'custom-post-widget' ), $widget_ops );
	}

	function form( $instance ) {
		$custom_post_id = ''; // Initialize the variable
		if (isset($instance['custom_post_id'])) {
			$custom_post_id = esc_attr($instance['custom_post_id']);
		};
		$show_custom_post_title  = isset( $instance['show_custom_post_title'] ) ? $instance['show_custom_post_title'] : true;
		$show_featured_image  = isset( $instance['show_featured_image'] ) ? $instance['show_featured_image'] : true;
		$apply_content_filters  = isset( $instance['apply_content_filters'] ) ? $instance['apply_content_filters'] : true;
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'custom_post_id' ); ?>"> <?php echo __( 'Content Block to Display:', 'custom-post-widget' ) ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'custom_post_id' ); ?>" name="<?php echo $this->get_field_name( 'custom_post_id' ); ?>">
				<?php
					$args = array( 'post_type' => 'content_block', 'suppress_filters' => 0, 'numberposts' => -1, 'order' => 'ASC' );
					$content_block = get_posts( $args );
					if ($content_block) {
						foreach( $content_block as $content_block ) : setup_postdata( $content_block );
							echo '<option value="' . $content_block -> ID . '"';
							if( $custom_post_id == $content_block -> ID ) {
								echo ' selected';
								$widgetExtraTitle = $content_block -> post_title;
							};
							echo '>' . $content_block -> post_title . '</option>';
						endforeach;
					} else {
						echo '<option value="">' . __( 'No content blocks available', 'custom-post-widget' ) . '</option>';
					};
				?>
				</select>
			</label>
		</p>

		<input type="hidden" id="<?php echo $this -> get_field_id( 'title' ); ?>" name="<?php echo $this -> get_field_name( 'title' ); ?>" value="<?php if ( !empty( $widgetExtraTitle ) ) { echo $widgetExtraTitle; } ?>" />

		<p>
			<?php
				echo '<a href="post.php?post=' . $custom_post_id . '&action=edit">' . __( 'Edit Content Block', 'custom-post-widget' ) . '</a>' ;
			?>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) isset( $instance['show_custom_post_title'] ), true ); ?> id="<?php echo $this->get_field_id( 'show_custom_post_title' ); ?>" name="<?php echo $this->get_field_name( 'show_custom_post_title' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_custom_post_title' ); ?>"><?php echo __( 'Show post title', 'custom-post-widget' ) ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) isset( $instance['show_featured_image'] ), true ); ?> id="<?php echo $this->get_field_id( 'show_featured_image' ); ?>" name="<?php echo $this->get_field_name( 'show_featured_image' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_featured_image' ); ?>"><?php echo __( 'Show featured image', 'custom-post-widget' ) ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) isset( $instance['apply_content_filters'] ), true ); ?> id="<?php echo $this->get_field_id( 'apply_content_filters' ); ?>" name="<?php echo $this->get_field_name( 'apply_content_filters' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'apply_content_filters' ); ?>"><?php echo __( 'Do not apply content filters', 'custom-post-widget' ) ?></label>
		</p> <?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['custom_post_id'] = strip_tags( $new_instance['custom_post_id'] );
		$instance['show_custom_post_title'] = $new_instance['show_custom_post_title'];
		$instance['show_featured_image'] = $new_instance['show_featured_image'];
		$instance['apply_content_filters'] = $new_instance['apply_content_filters'];
		return $instance;
	}

	// Display the content block content in the widget area
	function widget($args, $instance) {
		extract($args);
		$custom_post_id  = ( $instance['custom_post_id'] != '' ) ? esc_attr($instance['custom_post_id']) : __( 'Find', 'custom-post-widget' );
		// Add support for WPML Plugin.
		if ( function_exists( 'icl_object_id' ) ){
			$custom_post_id = icl_object_id( $custom_post_id, 'content_block', true );
		}
		// Variables from the widget settings.
		$show_custom_post_title = isset( $instance['show_custom_post_title'] ) ? $instance['show_custom_post_title'] : false;
		$show_featured_image  = isset($instance['show_featured_image']) ? $instance['show_featured_image'] : false;
		$apply_content_filters  = isset($instance['apply_content_filters']) ? $instance['apply_content_filters'] : false;
		$content_post = get_post( $custom_post_id );
		$post_status = get_post_status( $custom_post_id );
		$content = $content_post->post_content;
		if ( $post_status == 'publish' ) {
			// Display custom widget frontend
			if ( $located = locate_template( 'custom-post-widget.php' ) ) {
				require $located;
				return;
			}
			if ( !$apply_content_filters ) { // Don't apply the content filter if checkbox selected
				$content = apply_filters( 'the_content', $content);
			}
			echo $before_widget;
			if ( $show_custom_post_title ) {
				echo $before_title . apply_filters( 'widget_title',$content_post->post_title) . $after_title; // This is the line that displays the title (only if show title is set)
			}
			if ( $show_featured_image ) {
				echo get_the_post_thumbnail( $content_post -> ID );
			}
			echo do_shortcode( $content ); // This is where the actual content of the custom post is being displayed
			echo $after_widget;
		}
	}
}

// Create the Content Block custom post type
function cpw_post_type_init() {
	$labels = array(
		'name' => _x( 'Content Blocks', 'post type general name', 'custom-post-widget' ),
		'singular_name' => _x( 'Content Block', 'post type singular name', 'custom-post-widget' ),
		'plural_name' => _x( 'Content Blocks', 'post type plural name', 'custom-post-widget' ),
		'add_new' => _x( 'Add Content Block', 'block', 'custom-post-widget' ),
		'add_new_item' => __( 'Add New Content Block', 'custom-post-widget' ),
		'edit_item' => __( 'Edit Content Block', 'custom-post-widget' ),
		'new_item' => __( 'New Content Block', 'custom-post-widget' ),
		'view_item' => __( 'View Content Block', 'custom-post-widget' ),
		'search_items' => __( 'Search Content Blocks', 'custom-post-widget' ),
		'not_found' =>  __( 'No Content Blocks Found', 'custom-post-widget' ),
		'not_found_in_trash' => __( 'No Content Blocks found in Trash', 'custom-post-widget' )
	);
	$content_block_public = false; // added to make this a filterable option
	$options = array(
		'labels' => $labels,
		'public' => apply_filters( 'content_block_post_type', $content_block_public ),
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_icon' => 'dashicons-screenoptions',
		'supports' => array( 'title','editor','revisions','thumbnail','author' )
	);
	register_post_type( 'content_block',$options );
}
add_action( 'init', 'cpw_post_type_init' );

function content_block_messages( $messages ) {
	$messages['content_block'] = array(
		0 => '',
		1 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Content Block updated. <a href="%s">Manage Widgets</a>', 'custom-post-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Content Block updated.', 'custom-post-widget' ), esc_url( 'widgets.php' ) ),
		2 => __( 'Custom field updated.', 'custom-post-widget' ),
		3 => __( 'Custom field deleted.', 'custom-post-widget' ),
		4 => __( 'Content Block updated.', 'custom-post-widget' ),
		5 => isset($_GET['revision']) ? sprintf( __( 'Content Block restored to revision from %s', 'custom-post-widget' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Content Block published. <a href="%s">Manage Widgets</a>', 'custom-post-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Content Block published.', 'custom-post-widget' ), esc_url( 'widgets.php' ) ),
		7 => __( 'Block saved.', 'custom-post-widget' ),
		8 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Content Block submitted. <a href="%s">Manage Widgets</a>', 'custom-post-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Content Block submitted.', 'custom-post-widget' ), esc_url( 'widgets.php' ) ),
		9 => sprintf( __( 'Content Block scheduled for: <strong>%1$s</strong>.', 'custom-post-widget' ), date_i18n( __( 'M j, Y @ G:i' , 'custom-post-widget' ), strtotime(isset($post->post_date) ? $post->post_date : null) ), esc_url( 'widgets.php' ) ),
		10 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Content Block draft updated. <a href="%s">Manage Widgets</a>', 'custom-post-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Content Block draft updated.', 'custom-post-widget' ), esc_url( 'widgets.php' ) ),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'content_block_messages' );

// Add the ability to display the content block in a reqular post using a shortcode
function custom_post_widget_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'id' => '',
		'slug' => '',
		'class' => 'content_block',
		'suppress_content_filters' => 'no',
		'featured_image' => 'no',
		'featured_image_size' => 'medium',
		'title' => 'no',
		'title_tag' => 'h3'
	), $atts ) );

	if ( $slug ) {
		$block = get_page_by_path( $slug, OBJECT, 'content_block' );
		if ( $block ) {
			$id = $block->ID;
		}
	}

	$content = "";

	if( $id != "" ) {
		$args = array(
			'post__in' => array( $id ),
			'post_type' => 'content_block',
		);

		$content_post = get_posts( $args );

		foreach( $content_post as $post ) :
			$content .= '<div class="'. esc_attr( $class ) .'" id="custom_post_widget-' . $id . '">';
			if ( $title === 'yes' ) {
				$content .= '<' . esc_attr( $title_tag ) . '>' . $post -> post_title . '</' . esc_attr( $title_tag ) . '>';
			}
			if ( $featured_image === 'yes' ) {
				$content .= get_the_post_thumbnail( $post -> ID, $featured_image_size );
			}
			if ( $suppress_content_filters === 'no' ) {
				$content .= apply_filters( 'the_content', $post -> post_content );
			} else {
				$content .= $post -> post_content;
			}
			$content .= '</div>';
		endforeach;
	}

	return $content;
}
add_shortcode( 'content_block', 'custom_post_widget_shortcode' );

// Only add content_block icon above posts and pages
function cpw_add_content_block_button() {
	global $current_screen;
    if ( ( 'content_block' != $current_screen -> post_type ) && ( 'toplevel_page_revslider' != $current_screen -> id ) ) {
		add_action( 'media_buttons', 'add_content_block_icon' );
		add_action( 'admin_footer', 'add_content_block_popup' );
	}
}
add_action( 'admin_head', 'cpw_add_content_block_button' );