<?php
/* This file is for the sidebar account widget in posts. For the main page, see first-time.php.
*/
?>
<div class="card">


    <h4 class="card-title">
        <?php
			$current_user = wp_get_current_user();

			if(!($current_user->ID == 0)) {
		?>

        <div class="pull-right">
            <a class="btn btn-sm btn-default" href="<?php echo $GLOBALS["geopccb_accounts_url"]?>/profile" title="Edit Account Details">
                <span class="glyphicon glyphicon-wrench"></span>
            </a>
            <a class="btn btn-sm btn-warning" href="<?php echo wp_logout_url( get_permalink() ); ?>" title="Log Out">
                <span class="glyphicon glyphicon-off"></span>
            </a>
        </div>
        <?php } ?>
        <span class="glyphicon glyphicon-user"></span>
	<!-- My Account -->
        Account Management
    </h4>

    <!-- <% if (!authenticated) { %> -->
	<?php
		if($current_user->ID == 0) {
	?>
    <div id="loginSection">
        <!-- <p class="section-desc">Already have an account? Go ahead and sign in! Don't have an account? Register a new account using the button below. It's quick and free!</p> -->
	<p class="section-desc">Don't have an account? Register a new account using the button below. It's quick, free, and available to everyone!</p>
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-accent" href="<?php echo $GLOBALS["geopccb_accounts_url"]?>/register">Register</a>
            </div>
        </div>
    </div>

    <?php }

	   if(!($current_user->ID == 0)) {
		    $ch = curl_init();
        $url = $GLOBALS['geopccb_ual_url'] . "/api/items?createdBy=" . $current_user->user_login . "&size=3&sort=_modified,desc&type=Map&fields=*";
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    $response = curl_exec($ch);

		    curl_close($ch);

			if(!empty($response))
		   	$result = json_decode($response, true);
		  else
			  $result = "No recent activity";
	?>

    <div id="userInfoSection">
	    <h5>Welcome Back</h5>
      <span><?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname?></span>
      <div class="text-muted"><?php echo $current_user->user_email ?></div>
      <h5 class="text-accented">My Recent Resources</h5>
		  <div><?php

        if (array_key_exists('results', $result) && isset($result['results'])){
		      $user_activity = $result['results'];

			    if(count($user_activity) > 0) {
	          foreach($user_activity as $item){
              //$map_reference_url = $item['referenceUrl'];
              $map_reference_url = "";
              $agol_url = $item['landingPage'];

              //incorporate for AGOL maps
              if (empty($agol_url))
                $map_reference_url = $GLOBALS['geopccb_viewer_url'] . '/?id=' . $item['id'];
              else
                $map_reference_url = $agol_url;

              $thumb = $GLOBALS['geopccb_ual_url'] . '/api/maps/' . $item['id'] . '/thumbnail';

              if(empty($thumb))
                $thumb = $GLOBALS['geopccb_ual_url'] . '/api/maps/' . $item['id'] . '/thumbnail';
              else if(!(strpos($thumb, 'http') == 0 ))
                $thumb = $GLOBALS['geopccb_ual_url'] . '/api/maps/' . $item['id'] . '/thumbnail';

					    $map_activity_date = gmdate("M d Y", $item['modified'] / 1000);

              echo    '<div class="media">';
              echo    '  <a href="' . $map_reference_url . '" class="media-left media-middle">';
              echo    '    <img class="media-object bordered" src="' . $thumb . '" alt="' . $item['label'] . '" height="48">';
              echo    '  </a>';
              echo    '  <div class="media-body">';
              echo    '    <div class="media-heading"><a href="' . $map_reference_url . '">' . $item['label'] . '</a></div>';
              echo    '    <small class="text-muted">' . $map_activity_date . '</small>';
              echo    '  </div>';
              echo    '</div>';
            }
          } else
            echo '<em>You have no recent items. You should create some content!</em>';
        } else
          echo '<em>You have no recent items. You should create some content!</em>';
			?></div>
    </div>

    <?php } ?>

 </div>
