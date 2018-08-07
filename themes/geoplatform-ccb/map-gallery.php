<?php
/**
 * The Gallery template to integrate with Geoplatform Map galleries
 *
 * @package GeoPlatform CCB
 *
 * @since 3.0.0
 */
?>

<div class="apps-and-services section--linked">
  <h4 class="heading text-centered">
      <div class="line"></div>
      <div class="line-arrow"></div>
      <div class="title darkened">
        <?php _e('Community Map Gallery', 'geoplatform-ccb'); ?>
      </div>
  </h4>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="text-center">
          <div class="row">
        <?php
        //get theme mod defaults
        $geopccb_theme_options = geop_ccb_get_theme_mods();
        $geopccb_invalid_bool = false;
        $geopccb_error_report = '';

        // Explode customizerLink for use in validity checking.
        $geopccb_customizerLink = get_theme_mod('map_gallery_link_box_setting', $geopccb_theme_options['map_gallery_link_box_setting']);
        $geopccb_customizerLink_array = explode('/', $geopccb_customizerLink);

        // Map link format validity block one
        // Checks in turn if the gallery link is empty, if it contains a base URI,
        // if that URI matches one of the GeoPlatform sources, if it contains the
        // gallery ID portion of the URI, and if that URI is of valid format.
        if ( empty($geopccb_customizerLink) ){
          $geopccb_invalid_bool = true;
          $geopccb_error_report = 'The Map Gallery Link in Customizer->Custom Links Section is blank. Please fill in the link according to the CCB Cookbook, to see your Map Gallery.';
        }
        elseif (!array_key_exists(2, $geopccb_customizerLink_array)){
          $geopccb_invalid_bool = true;
          $geopccb_error_report = 'Invalid map gallery source. Please input a valid gallery endpoint';
        }
        elseif ($geopccb_customizerLink_array[2] != 'ual.geoplatform.gov' && $geopccb_customizerLink_array[2] != 'stg-ual.geoplatform.gov' && $geopccb_customizerLink_array[2] != 'sit-ual.geoplatform.us'){
          $geopccb_invalid_bool = true;
          $geopccb_error_report = 'Invalid map gallery source. Please input a valid gallery endpoint';
        }
        elseif (!array_key_exists(5, $geopccb_customizerLink_array)){
          $geopccb_invalid_bool = true;
          $geopccb_error_report = 'Invalid map gallery source. Please input a valid gallery endpoint';
        }
        elseif (!ctype_xdigit($geopccb_customizerLink_array[5]) || strlen($geopccb_customizerLink_array[5]) != 32){
          $geopccb_invalid_bool = true;
          $geopccb_error_report = 'Invalid gallery ID. Please check your your input and try again.';
        }

        // Map link format validity block two.
        // Grabs the gallery JSON. If the grab fails or returns a non-gallery, an error is reported.
        if (!$geopccb_invalid_bool){
          $geopccb_link_scrub = wp_remote_get( ''.$geopccb_customizerLink.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
          $geopccb_response = wp_remote_retrieve_body( $geopccb_link_scrub );
          if(!empty($geopccb_response)){
            $geopccb_result = json_decode($geopccb_response, true);
          }
          else{
            $geopccb_invalid_bool = true;
            $geopccb_error_report = 'Invalid gallery ID. Please check your your input and try again.' . $geopccb_customizerLink;
          }
          if (!$geopccb_invalid_bool && array_key_exists('statusCode', $geopccb_result) && $geopccb_result['statusCode'] == "404"){
            $geopccb_invalid_bool = true;
            $geopccb_error_report = 'Invalid gallery ID. Please check your your input and try again.';
          }
          elseif (!$geopccb_invalid_bool && array_key_exists('type', $geopccb_result) && $geopccb_result['type'] != "Gallery"){
            $geopccb_invalid_bool = true;
            $geopccb_error_report = 'This is not a gallery ID. Please check your your input and try again.';
          }
          //if map gallery env radio is different than current env
          $geopccb_gallery_link_env = get_theme_mod('map_gallery_env_choice_setting', $geopccb_theme_options['map_gallery_env_choice_setting']);
        }

        if( !$geopccb_invalid_bool ) {
          foreach($geopccb_result['items'] as $geopccb_map){
            try {
              //set map ID
              $geopccb_map_id = $geopccb_map['asset']['id'];
              switch ($geopccb_gallery_link_env) {
                case 'sit':
                  $geopccb_single_map = wp_remote_get( 'https://sit-ual.geoplatform.us/api/maps/'.$geopccb_map_id.'');
                  break;
                case 'stg':
                  $geopccb_single_map = wp_remote_get( 'https://stg-ual.geoplatform.gov/api/maps/'.$geopccb_map_id.'');
                  break;
                case 'prod':
                  $geopccb_single_map = wp_remote_get( 'https://ual.geoplatform.gov/api/maps/'.$geopccb_map_id . '');
                  break;
                default:
                  $geopccb_single_map = wp_remote_get( $GLOBALS['ual_url'] .'/api/maps/'.$geopccb_map_id.'');
                  break;
              }

              if( is_wp_error( $geopccb_single_map ) ) {
                return false; // Bail early
              }
              $geopccb_map_body = wp_remote_retrieve_body( $geopccb_single_map );
              //if the map is empty, handle it
              if(!empty($geopccb_map_body)){
                $geopccb_single_result = json_decode($geopccb_map_body, true);
              }else{
                $geopccb_single_result = __( 'The map did not load properly', 'geoplatform-ccb');
              }
              //for AGOL Maps
              //use isset() to get rid of php notices
              if (isset($geopccb_single_result['thumbnail']['url'])){
                $geopccb_thumbnail = $geopccb_single_result['thumbnail']['url'];
              }
              //for MM maps
              elseif (isset($geopccb_single_result['thumbnail'])) {
                switch ($geopccb_gallery_link_env) {
                  case 'sit':
                    $geopccb_thumbnail = 'https://sit-ual.geoplatform.us/api/maps/'. $geopccb_map_id . "/thumbnail";
                    break;
                  case 'stg':
                    $geopccb_thumbnail = 'https://stg-ual.geoplatform.gov/api/maps/'. $geopccb_map_id . "/thumbnail";
                    break;
                  case 'prod':
                    $geopccb_thumbnail = 'https://ual.geoplatform.gov/api/maps/'. $geopccb_map_id . "/thumbnail";
                    break;
                  default:
                    $geopccb_thumbnail = $GLOBALS['ual_url'] . '/api/maps/' . $geopccb_map_id . "/thumbnail";
                    break;
                }
              }

              //if the map doesn't have a thumbnail
              else {
                $geopccb_thumbnail = __( 'Could not find image.', 'geoplatform-ccb');
                continue;
              }

              $geopccb_href = "";
              //use isset() to get rid of php notices
              if (isset($geopccb_single_result['landingPage'])) {
                $geopccb_href = $geopccb_single_result['landingPage'];
              }
              //use isset() to get rid of php notices
              if (isset($geopccb_map['description'])) {
                $geopccb_description = $geopccb_map['description'];
              }
              $geopccb_label = $geopccb_map['label'];
              ?>

              <!-- Card for the map -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="gp-ui-card gp-ui-card--minimal">
                  <div class="media">

                  <?php  if($geopccb_href){ ?>
                    <a class="embed-responsive embed-responsive-16by9" title="<?php echo esc_html($geopccb_label); ?>" href="<?php echo esc_url($geopccb_href);?>" target="_blank">
                  <?php } else{
                    switch ($geopccb_gallery_link_env) {
                      case 'sit':
                        $geopccb_href = 'https://sit-viewer.geoplatform.us/?id=' . $geopccb_map_id;
                        break;
                      case 'stg':
                        $geopccb_href = 'https://stg-viewer.geoplatform.gov/?id=' . $geopccb_map_id;
                        break;
                      case 'prod':
                        $geopccb_href = 'https://viewer.geoplatform.gov/?id=' . $geopccb_map_id;
                        break;
                      default:
                        $geopccb_href = $GLOBALS['viewer_url'] . '/?id=' . $geopccb_map_id;
                        break;
                      }
                    ?>
                    <a class="embed-responsive embed-responsive-16by9" title="<?php echo esc_html($geopccb_label); ?>" href=" <?php echo esc_url($geopccb_href);?>" target="_blank">
                    <?php } ?>
                      <img class="embed-responsive-item" src="<?php echo esc_url($geopccb_thumbnail); ?>" alt=""></a>
                    </div> <!--media-->
                    <div class="gp-ui-card__body" style="height:55px;">
                      <h4 class="text--primary"><?php echo esc_html($geopccb_label); ?></h4>
                    </div>
                  </div> <!--gp-ui-card gp-ui-card-minimal-->
              </div> <!-- class="col-md-3 col-sm-3 col-xs-6" -->
              <?php } //try
                catch (Exception $e){
                  _e('There are errors with the linked gallery', 'geoplatform-ccb');
                } //catch
              }?>
              </div><!--row-->
            </div><!--card text-center-->
          <?php } //if ! (empty ($geopccb_result))
              else { ?>
                <div>
                  <p>
                    <?php _e($geopccb_error_report, 'geoplatform-ccb'); ?>
                  </p>
                </div>
              </div><!--col-md-12 col-sm-12-->
            </div><!--row-->
          </div><!--container-fluid-->
            <?php  } //else ?>

      </div> <!--outloop col-md-12 col-sm-12-->
      </div> <!--outloop row -->

      </div> <!--outloop container fluid-->
      <div class="footing">
          <div class="line-cap"></div>
          <div class="line"></div>
      </div>
    </div> <!--outloop section-linked-->
