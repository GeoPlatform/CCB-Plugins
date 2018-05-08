  <?php
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

                <h3>First Time Here?</h3>

                <picture class="pull-right inline-figure">
                    <source srcset="/wp-content/uploads/2017/03/register_sm.jpeg" media="(min-width: 768px)">
                    <img alt="Register for a GeoPlatform Account" src="/wp-content/uploads/2017/03/register_sm.jpeg">
                </picture>

                <p>Sign up to access thousands of datasets uploaded by others and contribute your own data to the world!
	               You can also share your expertise and find experts to help with your geospatial data needs by joining
	               one of our <a href="social">Communities</a>.  Submit your metadata to
	               <a href="http://www.data.gov/" target="_blank">Data.gov <span class="glyphicon glyphicon-new-window">
		           </span></a> and we’ll add it to GeoPlatform so others can use it.
                </p>

                <br>

                  <div class="first-time-buttons">
                      <a href="<?php echo $GLOBALS['accounts_url'];?>/register" class="btn btn-lg btn-accent">Register</a>
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
	//$url = "https://registry.geoplatform.gov/api/maps?author=" . $current_user->user_login . "&sortElement=updated&sortOrder=dsc&count=4";
  $url = $GLOBALS['ual_url'] . "/api/items?createdBy=" . $current_user->user_login . "&size=3&sort=_modified,desc&type=Map&fields=*";
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
  	      <div class="title">My Account</div>
  	  </h4>
  	  <br>

  	    <div class="row">

  	        <div class="col-xs-12 col-sm-4">

  	            <span class="glyphicon glyphicon-user text--xxxlg"></span>
  	            <h4><?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname?> <small>(<?php echo $current_user->user_login ?>)</small></h4>

  	            <br>

  	            <?php //var_dump($current_user); ?>

              <a class="btn btn-info" href="<?php echo $GLOBALS['accounts_url'];?>/profile" title="Edit Account Details">
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

  					            $user_activity = $result['results'];

              					if(count($user_activity) > 0){
              						foreach($user_activity as $item){
                              $map_reference_url = "";
                              $agol_url = $item['landingPage'];
                              //incorporate for AGOL maps
                              if (empty($agol_url)){
                                $map_reference_url =  $GLOBALS['viewer_url'] . '/?id=' . $item['id'];
                              }
                              else{
                                $map_reference_url = $agol_url;
                              }
                							$thumb = $GLOBALS['ual_url'] . '/api/maps/' . $item['id'] . '/thumbnail';

                							//if(empty($thumb)) $thumb = 'https://registry.geoplatform.gov/api/maps/' . $item['_id'] . '/thumbnail?format=image';
                              if(empty($thumb)) $thumb =  $GLOBALS['ual_url'] . '/api/maps/' . $item['id'] . '/thumbnail';
                							else if(!(strpos($thumb, 'http') == 0 )) $thumb =  $GLOBALS['ual_url'] . '/api/maps/' . $item['id'] . '/thumbnail';
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
  					} else {
  						echo '<em>You have no recent items. You should create some content!</em>';
  					}
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
