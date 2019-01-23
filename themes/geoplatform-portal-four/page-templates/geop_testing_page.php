<?php
/**
 * Template Name: AAAAAAAAAAAAAAAAAAAAA Testpage
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
get_header();
get_template_part( 'sub-header-post', get_post_format() );


?>

<div class="l-body l-body--one-column">
  <div class="l-body__main-column">
    <?php
      var_dump(getUserAccessToken(get_current_user_id()));
      echo "<br><br>";
      echo get_current_user_id();

      echo "<br><br>";
      echo "<br><br>";
      echo "<br><br>";
      echo "<br><br>";
      var_dump(get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response'));
      echo "<br><br>";
      if (!empty(get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response', true)['access_token']))
        echo(get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response', true)['access_token']);
      echo "<br><br>";
      var_dump(get_user_meta(get_current_user_id(), 'wp_capabilities'));
      echo "<br><br>";
      echo(get_user_meta(get_current_user_id(), 'wp_capabilities', true)['administrator']);
      echo "<br><br>";
      var_dump(get_user_meta(get_current_user_id(), 'session_tokens', true));
      echo "<br><br>";
      echo "<br><br>";
      echo "<br><br>";
      echo "<br><br>";
    ?>
    <script>
      jQuery(document).ready(function() {
        // jQuery.ajax( {
        //     url: wpApiSettings.root + 'wp/v2/posts/1',
        //     method: 'POST',
        //     beforeSend: function ( xhr ) {
        //         xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
        //     },
        //     data:{
        //         'title' : 'Hello Moon'
        //     }
        // } ).done( function ( response ) {
        //     console.log( response );
        // } );




        jQuery.getJSON("http://localhost/my-test-site/wp-json/wp-gpoauth/v1/get_token", function(result){
          console.log(result);
        });
      });
    </script>
  </div>
</div>
<?php get_footer(); ?>
