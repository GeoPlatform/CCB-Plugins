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
      echo wp_get_session_token();
      echo "<br><br>";
      var_dump(get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response'));
      echo "<br><br>";
      var_dump(get_post_meta(get_the_ID()));
    ?>
  </div>
</div>
<?php get_footer(); ?>
