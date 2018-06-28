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
      Community Map Gallery
      </div>
  </h4>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="text-center">
          <div class="row">
        <?php
        //get theme mod defaults
        $theme_options = geop_ccb_get_theme_mods();

        $customizerLink = get_theme_mod('map_gallery_link_box_setting', $theme_options['map_gallery_link_box_setting']);
        if (!$customizerLink ){
          echo 'The Map Gallery Link in Customizer->Custom Links Section is blank. Please fill in the link according to the CCB Cookbook, to see your Map Gallery. ';
        }
        
        $link_scrub = wp_remote_get( ''.$customizerLink.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
        $response = wp_remote_retrieve_body( $link_scrub );

        if(!empty($response)){
          $result = json_decode($response, true);
        }else{
          $result = "This Gallery has no recent activity. Try adding some maps!";
        }

        //if map gallery env radio is different than current env
        $gallery_link_env = get_theme_mod('map_gallery_env_choice_setting', $theme_options['map_gallery_env_choice_setting']);

        if( ! empty( $result ) ) {
          foreach($result['items'] as $map){
            switch ($gallery_link_env) {
              case 'sit':
                $single_map = wp_remote_get( 'https://sit-ual.geoplatform.us/api/maps/'.$map['assetId'].'');
                break;
              case 'stg':
                $single_map = wp_remote_get( 'https://stg-ual.geoplatform.gov/api/maps/'.$map['assetId'].'');
                break;
              case 'prod':
                $single_map = wp_remote_get( 'https://ual.geoplatform.gov/api/maps/'.$map['assetId'].'');
                break;
              default:
                $single_map = wp_remote_get( $GLOBALS['ual_url'] .'/api/maps/'.$map['assetId'].'');
                break;
                }
            
            if( is_wp_error( $single_map ) ) {
              return false; // Bail early
            }
            $map_body = wp_remote_retrieve_body( $single_map );
            //if the map is empty, handle it
            if(!empty($map_body)){
              $single_result = json_decode($map_body, true);
            }else{
              $single_result = "The map did not load properly";
            }
            //for AGOL Maps
            //use isset() to get rid of php notices
            if (isset($single_result['thumbnail']['url'])){
              $thumbnail = $single_result['thumbnail']['url'];
              }
            //for MM maps
            elseif (isset($single_result['thumbnail'])) {
              switch ($gallery_link_env) {
                case 'sit':
                  $thumbnail = 'https://sit-ual.geoplatform.us/api/maps/'. $map['assetId'] . "/thumbnail";
                  break;
                case 'stg':
                  $thumbnail = 'https://stg-ual.geoplatform.gov/api/maps/'. $map['assetId'] . "/thumbnail";
                  break;
                case 'prod':
                  $thumbnail = 'https://ual.geoplatform.gov/api/maps/'. $map['assetId'] . "/thumbnail";
                  break;
                default:
                  $thumbnail = $GLOBALS['ual_url'] . '/api/maps/' . $map['assetId'] . "/thumbnail";
                  break;
                  }
              }

                  //if the map doesn't have a thumbnail
                  else {
                    $thumbnail = "Could not find image";
                    continue;
                    }

                    $href = "";
                    //use isset() to get rid of php notices
                    if (isset($single_result['landingPage'])) {
                      $href = $single_result['landingPage'];
                    }
                    //use isset() to get rid of php notices
                    if (isset($map['description'])) {
                      $description = $map['description'];
                    }
                    $label = $map['label'];
                    ?>
                    
                <!-- Card for the map -->
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="gp-ui-card gp-ui-card--minimal">
                        <div class="media">

                        <?php  if($href){ ?>
                            <a class="embed-responsive embed-responsive-16by9" title="<?php echo esc_html($label); ?>" href="<?php echo esc_url($href);?>" target="_blank">
                        <?php } else{
                          switch ($gallery_link_env) {
                            case 'sit':
                              $href = 'https://sit-viewer.geoplatform.us/?id=' . $map['assetId'];
                              break;
                            case 'stg':
                              $href = 'https://stg-viewer.geoplatform.gov/?id=' . $map['assetId'];
                              break;
                            case 'prod':
                              $href = 'https://viewer.geoplatform.gov/?id=' . $map['assetId'];
                              break;
                            default:
                              $href = $GLOBALS['viewer_url'] . '/?id=' . $map['assetId'];
                              break;
                              }
                            ?>
                            <a class="embed-responsive embed-responsive-16by9" title="<?php echo esc_html($label); ?>" href=" <?php echo esc_url($href);?>" target="_blank">
                        <?php } ?>
                        <img class="embed-responsive-item" src="<?php echo esc_url($thumbnail); ?>" alt=""></a>
                        </div> <!--media-->
                          <div class="gp-ui-card__body" style="height:55px;">
                              <h4 class="text--primary"><?php echo esc_html($label); ?></h4>
                          </div>
                    </div> <!--gp-ui-card gp-ui-card-minimal-->
                </div> <!-- class="col-md-3 col-sm-3 col-xs-6" -->

              	<?php }//foreach $result['items'] as map?>

              </div><!--row-->
            </div><!--card text-center-->
              <?php } //if ! (empty ($result))
              else { ?>
                <div>
                <p>
                The Map Gallery did not load properly.
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
