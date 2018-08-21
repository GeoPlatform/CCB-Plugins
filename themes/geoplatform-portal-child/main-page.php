<?php
class Geopportal_MainPage_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_mainpage_widget', // Base ID
			esc_html__( 'GeoPlatform Features & Announcements', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform features & announcements widget for the front page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    if (array_key_exists('geopportal_mainpage_title', $instance) && isset($instance['geopportal_mainpage_title']) && !empty($instance['geopportal_mainpage_title']))
      $geopportal_mainpage_disp_title = apply_filters('widget_title', $instance['geopportal_mainpage_title']);
		else
      $geopportal_mainpage_disp_title = "Features &amp; Announcements";
    echo $args['before_widget'];?>

    <div class="whatsNew section--linked">
      <div class="container-fluid">
        <div class="line"></div>
        <div class="line-arrow"></div>

        <div class="col-lg-10 col-lg-offset-1">
          <h4 class="heading text-centered">
            <div class="title darkened">
              <?php echo $geopportal_mainpage_disp_title ?>
            </div>
          </h4>
          <div class="row">
            <?php
             //set counter
              $postNum = 0;
              //only show the first two posts
              while ( have_posts() && ($postNum < 3)) : the_post();
                  $postNum++;
                  get_template_part( 'post-card', get_post_format() );
              endwhile;
              ?>
            <div class="clearfix"></div>
          </div><!--#row-->
          <div class="row">
            <nav>
              <ul class="pager">
                <li><?php next_posts_link( 'Previous' ); ?></li>
                <li><?php previous_posts_link( 'Next' ); ?></li>
              </ul>
            </nav>
          </div><!--#row-->
        </div><!--#col-lg-10 col-lg-offset-1-->
      </div><!--#container-fluid-->
      <br>
      <div class="footing">
          <div class="line-cap"></div>
          <div class="line"></div>
      </div><!--#footing-->
    </div><!--#whatsNew section-linked-->
    <?php
    echo $args['after_widget'];
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    $geopportal_mainpage_title = ! empty( $instance['geopportal_mainpage_title'] ) ? $instance['geopportal_mainpage_title'] : 'Features &amp; Announcements';
    ?>

<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpage_title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_title' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_title ); ?>" />
    </p>
		<p>
		  There are numerous changes to this widget planned for a future release, including resource sorting, prioritizing, exclusion and inclusion. For now, an editable title is the only feature.
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
    $instance[ 'geopportal_mainpage_title' ] = strip_tags( $new_instance[ 'geopportal_mainpage_title' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_mainpage_widget() {
		register_widget( 'Geopportal_MainPage_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_mainpage_widget' );
