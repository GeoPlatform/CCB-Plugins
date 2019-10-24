<?php
/**
 * Template Name: Singular
 *
 * Used as default for posts and pages.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
get_header();
get_template_part( 'sub-header-post', get_post_format() );
$geopccb_theme_options = geop_ccb_get_theme_mods();


if (get_theme_mod('postbanner_controls', $geopccb_theme_options['postbanner_controls']) != 'off'){

  $geopccb_banner_card_style = get_theme_mod('feature_controls', 'fade');
  $geopccb_banner_card_fade = "widget-featured-fade-zero";

  if ($geopccb_banner_card_style == 'fade' || $geopccb_banner_card_style == 'both')
    $geopccb_banner_card_fade = "widget-featured-fade-five";

  // ELEMENTS
  echo "<div class='widget-banner-main'>";
    echo "<div class='widget-banner-sub ". $geopccb_banner_card_fade . "'>";
      echo "<div class='widget-banner-container container'>";

        if (get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true) != '' ){
          echo "<div class='m-article__heading geop-banner-wysiwyg-yes'>";
            echo esc_attr(the_title(), 'geoplatform-ccb');
          echo "</div>";
          echo "<div class='geop-banner-wysiwyg-yes'>";
           echo wp_kses_post(get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true));
          echo "</div>";
        } else {
          echo "<div class='m-article__heading geop-banner-wysiwyg-no'>";
            echo esc_attr(the_title(), 'geoplatform-ccb');
          echo "</div>";
        }

      echo "</div>";
    echo "</div>";
  echo "</div>";
}

echo "<div class='l-body l-body--two-column'>";

  echo "<div class='l-body__main-column'>";
    if ( have_posts() ) : while ( have_posts() ) : the_post();

      get_template_part( 'post-single', get_post_format() );

        //Un-comment the code below to show comments on the posts
        //if ( comments_open() || get_comments_number() ) :
        //	  comments_template();
        //	endif;
    endwhile; endif;
  echo "</div>";
  get_template_part( 'sidebar', get_post_format() );
echo "</div>";
get_footer();
