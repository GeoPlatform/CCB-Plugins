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

        $geopccb_customizerLink = get_theme_mod('map_gallery_link_box_setting', $geopccb_theme_options['map_gallery_link_box_setting']);
        if (!$geopccb_customizerLink ){
          _e( 'The Map Gallery Link in Customizer->Custom Links Section is blank. Please fill in the link according to the CCB Cookbook, to see your Map Gallery.', 'geoplatform-ccb');
        }

        $geopccb_link_scrub = wp_remote_get( ''.$geopccb_customizerLink.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
        $geopccb_response = wp_remote_retrieve_body( $geopccb_link_scrub );

        if(!empty($geopccb_response)){
          $geopccb_result = json_decode($geopccb_response, true);
        }else{
          $geopccb_result = __('This Gallery has no recent activity. Try adding some maps!', 'geoplatform-ccb');
        }

        //if map gallery env radio is different than current env
        $geopccb_gallery_link_env = get_theme_mod('map_gallery_env_choice_setting', $geopccb_theme_options['map_gallery_env_choice_setting']);

        if( ! empty( $geopccb_result ) ) {
          foreach($geopccb_result['items'] as $geopccb_map){
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

              	<?php }//foreach $geopccb_result['items'] as map?>

              </div><!--row-->
            </div><!--card text-center-->
              <?php } //if ! (empty ($geopccb_result))
              else { ?>
                <div>
                <p>
                  <?php _e('The Map Gallery did not load properly.', 'geoplatform-ccb'); ?>
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
