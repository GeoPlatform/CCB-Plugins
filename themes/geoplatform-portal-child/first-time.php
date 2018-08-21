<?php
class Geopportal_Front_Account_Widget extends WP_Widget {

  // Constructor, simple.
	function __construct() {
	   parent::__construct(
  		'geopportal_front_account_widget', // Base ID
  		esc_html__( 'GeoPlatform Account', 'geoplatform-ccb' ), // Name
  		array( 'description' => esc_html__( 'GeoPlatform Account widget for the front page, providing a reactive user account interface.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true ) // Args
  	);
  }

  // Handles widget output
	public function widget( $args, $instance ) {
    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    if (array_key_exists('geopportal_firsttime_in_title', $instance) && isset($instance['geopportal_firsttime_in_title']) && !empty($instance['geopportal_firsttime_in_title']))
      $geopportal_firsttime_disp_in_title = apply_filters('widget_title', $instance['geopportal_firsttime_in_title']);
    else
      $geopportal_firsttime_disp_in_title = "My Account";
    if (array_key_exists('geopportal_firsttime_out_title', $instance) && isset($instance['geopportal_firsttime_out_title']) && !empty($instance['geopportal_firsttime_out_title']))
      $geopportal_firsttime_disp_out_title = apply_filters('widget_title', $instance['geopportal_firsttime_out_title']);
    else
      $geopportal_firsttime_disp_out_title = "First Time Here?";
    if (array_key_exists('geopportal_firsttime_out_content', $instance) && isset($instance['geopportal_firsttime_out_content']) && !empty($instance['geopportal_firsttime_out_content']))
      $geopportal_firsttime_disp_out_content = apply_filters('widget_title', $instance['geopportal_firsttime_out_content']);
    else
      $geopportal_firsttime_disp_out_content = "Sign up to access thousands of datasets uploaded by others and contribute your own data to the world! You can also share your expertise and find experts to help with your geospatial data needs by joining one of our <a href='" . home_url() . "social'>Communities</a>. Submit your metadata to <a href='http://www.data.gov' targetr='_blank'>Data.gov <span class='glyphicon glyphicon-new-window'></span></a> and we’ll add it to GeoPlatform so others can use it.";
		echo $args['before_widget'];

    /* This file is for the main page account widget. For the sidebar widget in posts, see account.php.
     */
    $current_user = wp_get_current_user();
    if($current_user->ID == 0) {
    ?>
    <div class="firstTime section--linked">

        <h4 class="heading">
            <div class="line"></div>
            <div class="line-arrow"></div>
        </h4>

        <div class="container">
            <div class="row">
                <div class="col-md-8">

                    <h3><?php echo $geopportal_firsttime_disp_out_title ?></h3>

                    <picture class="pull-right inline-figure">
                        <source srcset="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/register_sm.jpeg"); ?>" media="(min-width: 768px)">
                        <img alt="Register for a GeoPlatform Account" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/register_sm.jpeg"); ?>">
                    </picture>

                    <p><?php echo do_shortcode($geopportal_firsttime_disp_out_content) ?></p>

                    <br>
                      <div class="first-time-buttons">
                          <a href="<?php echo $GLOBALS['geopccb_accounts_url'] ?>/register" class="btn btn-lg btn-accent">Register</a>
                          <!-- &nbsp;&nbsp;&nbsp; or &nbsp;&nbsp;&nbsp;
                          <a href="<?php echo wp_login_url( get_option('siteurl') ); ?>" class="btn btn-lg btn-primary">Sign In</a> -->
                      </div><!--#firstTime first-time-buttons-->

                    <br>
                    <br>

                </div><!--#firstTime col-md-8-->
            </div> <!--#firstTime row-->
        </div> <!--#firstTime container-->
    <?php } else {
    	$ch = curl_init();
      $url = $GLOBALS['geopccb_ual_url'] . "/api/items?createdBy=" . $current_user->user_login . "&size=3&sort=_modified,desc&type=Map&fields=*";
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$response = curl_exec($ch);

    	curl_close($ch);

    	if(!empty($response)){
    		$result = json_decode($response, true);
    	}else{
    		$result = "No recent activity";
    	}
    ?>

      <div class="whatsNew section--linked">
      	<div class="container">
      	  <h4 class="heading">
      	      <div class="line"></div>
      	      <div class="line-arrow"></div>
      	      <div class="title"><?php echo $geopportal_firsttime_disp_in_title ?></div>
      	  </h4>
      	  <br>
      	    <div class="row">

      	        <div class="col-xs-12 col-sm-4">

      	            <span class="glyphicon glyphicon-user text--xxxlg"></span>
      	            <h4><?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname?> <small>(<?php echo $current_user->user_login ?>)</small></h4>

      	            <br>

      	            <?php //var_dump($current_user); ?>

                  <a class="btn btn-info" href="<?php echo $GLOBALS['geopccb_accounts_url'];?>/profile" title="Edit Account Details">
                      <span class="glyphicon glyphicon-wrench"></span>
                      Edit
                  </a>
                  &nbsp;&nbsp;&nbsp;

      				      <a class="btn btn-default" href="<?php echo wp_logout_url( home_url() ); ?>" title="Sign Out">
      	                <span class="glyphicon glyphicon-off"></span>
      	                Sign Out
      	            </a>

      	       </div> <!--#col-xs-12 col-sm-4 -->

      	        <div class="col-xs-12 col-sm-8">
      	            <h5>My Recent Items</h5>
      	            <div id="recent_items" class="my-account__items--recent">
      	              <div class="row">

      	                <?php
                        if (array_key_exists('results', $result) && isset($result['results'])){
    					            $user_activity = $result['results'];

                          if(count($user_activity) > 0){
                  					foreach($user_activity as $item){
                              $map_reference_url = "";
                              $agol_url = $item['landingPage'];

                              //incorporate for AGOL maps
                              if (empty($agol_url))
                                $map_reference_url = $GLOBALS['geopccb_viewer_url'] . '/?id=' . $item['id'];
                              else
                                $map_reference_url = $agol_url;

                							$thumb = $GLOBALS['geopccb_ual_url'] . '/api/maps/' . $item['id'] . '/thumbnail';

                              if(empty($thumb))
                                $thumb =  $GLOBALS['geopccb_ual_url'] . '/api/maps/' . $item['id'] . '/thumbnail';
                							else if(!(strpos($thumb, 'http') == 0 ))
                                $thumb =  $GLOBALS['geopccb_ual_url'] . '/api/maps/' . $item['id'] . '/thumbnail';
                							$map_activity_date = gmdate("M d Y", $item['updated'] / 1000);
      					          ?>

      							    <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0">
      								    <div class="gp-ui-card gp-ui-card--minimal">
      							  		  <a class="media embed-responsive embed-responsive-16by9" href="<?php echo $map_reference_url; ?>">
      							    		  <img class="embed-responsive-item" src="<?php echo $thumb; ?>" alt="<?php echo $item['label']; ?>" >
      							  		  </a>
      							  		  <div class="gp-ui-card__body">
      							    		  <div class="text--primary">
      											    <a href="<?php echo $map_reference_url; ?>"> <?php echo $item['label']; ?> </a>
      							  			  </div><!--gp-ui-card__body-->
      									    </div><!--gp-ui-card gp-ui-card-minimal-->
      								    </div><!--#col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0-->
      							    </div><!--#col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0-->

      					        <?php
                            } //foreach
      					          } else
      						          echo '<em>You have no recent items. You should create some content!</em>';
                        } else
                          echo '<em>You have no recent items. You should create some content!</em>';
                ?>
                    </div><!--#My Recent Items row-->
    	            </div><!--#id "recent_items"-->
    	        </div><!--#whatsNew col-xs-12 col-sm-8-->
    	    </div><!--#whatsNew row-->
    	</div><!--#whatsNew container-->

    <?php } ?>
        <!-- bottom directional lines -->
        <div class="footing">
            <div class="line-cap"></div>
            <div class="line"></div>
        </div><!--#footing-->
    </div> <!--#first-time or whatsNew, depending on if statment-->
    <?php
    echo $args['after_widget'];
	}

  // admin side of the widget
	public function form( $instance ) {

    // Top-left input boxes.
		$geopportal_firsttime_in_title = ! empty( $instance['geopportal_firsttime_in_title'] ) ? $instance['geopportal_firsttime_in_title'] : 'My Account';
		$geopportal_firsttime_out_title = ! empty( $instance['geopportal_firsttime_out_title'] ) ? $instance['geopportal_firsttime_out_title'] : 'First Time Here?';
    $geopportal_firsttime_out_content = ! empty( $instance['geopportal_firsttime_out_content'] ) ? $instance['geopportal_firsttime_out_content'] : '';

		// Sets up the top-left content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_firsttime_out_content', $instance) && isset($instance['geopportal_firsttime_out_content']) && !empty($instance['geopportal_firsttime_out_content'])){
    	$geopportal_firsttime_out_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_firsttime_out_content' ]);
    	if (is_numeric($geopportal_firsttime_out_temp_url))
      	$geopportal_firsttime_out_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_firsttime_out_temp_url . "&action=edit";
    	else
      	$geopportal_firsttime_out_url = home_url();
		}
		else
			$geopportal_firsttime_out_url = home_url();?>

<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_firsttime_in_title' ); ?>">Logged-In Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_firsttime_in_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_firsttime_in_title' ); ?>" value="<?php echo esc_attr( $geopportal_firsttime_in_title ); ?>" />
    </p>
    <hr>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_firsttime_out_title' ); ?>">Logged-Out Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_firsttime_out_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_firsttime_out_title' ); ?>" value="<?php echo esc_attr( $geopportal_firsttime_out_title ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_firsttime_out_content' ); ?>">Logged-Out Content Block Shortcode:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_firsttime_out_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_firsttime_out_content' ); ?>" value="<?php echo esc_attr($geopportal_firsttime_out_content); ?>" />
      <a href="<?php echo esc_url($geopportal_firsttime_out_url); ?>" target="_blank">Click here to edit the content block</a><br>
    </p>
    <?php
	}

	public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
		$instance[ 'geopportal_firsttime_in_title' ] = strip_tags( $new_instance[ 'geopportal_firsttime_in_title' ] );
	  $instance[ 'geopportal_firsttime_out_title' ] = strip_tags( $new_instance[ 'geopportal_firsttime_out_title' ] );
	  $instance[ 'geopportal_firsttime_out_content' ] = strip_tags( $new_instance[ 'geopportal_firsttime_out_content' ] );

    // Validity check for the content box URL.
		if (array_key_exists('geopportal_firsttime_out_content', $instance) && isset($instance['geopportal_firsttime_out_content']) && !empty($instance['geopportal_firsttime_out_content'])){
	  	$geopportal_firsttime_out_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_firsttime_out_content' ]);
	  	if (is_numeric($geopportal_firsttime_out_temp_url))
	    	$geopportal_firsttime_out_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_firsttime_out_temp_url . "&action=edit";
	  	else
	    	$geopportal_firsttime_out_url = home_url();
		}
		else
			$geopportal_firsttime_out_url = home_url();

	  return $instance;
  }
}

// Registers and enqueues the widget.
function geopportal_register_portal_account_frontpage_widget() {
  register_widget( 'Geopportal_Front_Account_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_account_frontpage_widget' );
