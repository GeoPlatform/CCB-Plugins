<?php
/**
 * Template Name: NGDA and Community Post Template
 * Template Post Type: community-post, ngda-post
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
get_header();
get_template_part( 'sub-header-com', get_post_format() );
?>

<div class="l-body l-body--two-column p-ngda-theme">
  <div class="l-body__main-column">

 <!-- Map section. Pulls apart and rebuilds map shortcode before execution. -->
      <?php
      if (isset($post->geopportal_compost_map_shortcode) && !empty($post->geopportal_compost_map_shortcode)){
        $geop_portal_broken_shortcode = explode( "'", $post->geopportal_compost_map_shortcode);
        $geop_portal_rebuilt_shortcode = "[geopmap id=" . $geop_portal_broken_shortcode[1] . " name='" . $geop_portal_broken_shortcode[3] . "' height=350]";
        ?>
        <div class="m-section-group">
          <div class="m-article">
              <div class="m-article__heading">
                  Featured <?php echo the_title() ?> Data
              </div>
              <div class="m-map" id="themeMapContainer">
                <?php echo do_shortcode($geop_portal_rebuilt_shortcode) ?>
              </div>
          </div>
        </div>
      <?php } ?>


 <!-- Data and search section. -->
      <?php
      if (isset($post->geopportal_compost_carousel_shortcode) && !empty($post->geopportal_compost_carousel_shortcode)){ ?>

        <div class="m-section-group">
          <?php echo do_shortcode($post->geopportal_compost_carousel_shortcode); ?>
        </div>

    <?php } ?>

 <!-- Section Heading section, gives information about the community using
      the post content. -->
      <div class="m-section-group">
          <article class="m-article">
              <div class="m-article__heading"><?php echo $post->geopportal_compost_content_title ?></div>
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                  ?><div class="m-article__desc"><?php
                    the_content();
                  ?></div><?php
                endwhile; endif; ?>
          </article>
      </div>
  </div>

<!-- Sidebar section. -->
  <div class="l-body__side-column">

      <!-- THUMBNAIL -->
      <?php
      if (has_post_thumbnail()){
        echo "<div class='m-article'>";
          echo "<div class='m-article__desc'>";
            echo "<img src='" . get_the_post_thumbnail_url() . "' width='100%' alt='" . get_the_title() . " Thumbnail'>";
          echo "</div>";
        echo "</div>";
      }
      ?>

      <div class="m-article">
          <div class="m-article__desc">
              <a class="btn btn-primary btn-block" href="<?php echo esc_url($post->geopportal_compost_community_url) ?>">
                  Go to the Community Portal
              </a>
          </div>
      </div>

      <?php
      if ((isset($post->geopportal_compost_sponsor_name) && !empty($post->geopportal_compost_sponsor_name)) ||
          (isset($post->geopportal_compost_agency_name) && !empty($post->geopportal_compost_agency_name)) ||
          (isset($post->geopportal_compost_lead_name) && !empty($post->geopportal_compost_lead_name) )){
      ?>
      <div class="m-article">
          <div class="m-article__heading">Community Details</div>
          <div class="m-article__desc">
              <?php
                if (isset($post->geopportal_compost_sponsor_name) && !empty($post->geopportal_compost_sponsor_name)){
                  echo "<div><strong>Sponsor:</strong> " . esc_attr($post->geopportal_compost_sponsor_name);
                  if (isset($post->geopportal_compost_sponsor_email) && !empty($post->geopportal_compost_sponsor_email)){
                    echo " (<a href='mailto:" . esc_url($post->geopportal_compost_sponsor_email) . "'>email</a>)";
                  }
                  echo "</div>";
                }
                if (isset($post->geopportal_compost_agency_name) && !empty($post->geopportal_compost_agency_name)){
                  echo "<div><strong>Theme Lead Agency:</strong> " . esc_attr($post->geopportal_compost_agency_name) . "</div>";
                }
                if (isset($post->geopportal_compost_lead_name) && !empty($post->geopportal_compost_lead_name)){
                  echo "<div><strong>Theme Lead:</strong> " . esc_attr($post->geopportal_compost_lead_name) . "</div>";
                }
              ?>
          </div>
      </div>
      <?php }

      if ( has_excerpt() ){
        echo "<div class='m-article'>";
          echo "<div class='m-article__heading'>Additional Info</div>";
          echo "<div class='m-article__desc'>";
            echo the_excerpt();
          echo "</div>";
        echo "</div>";
      } ?>


  </div>
</div>
<?php get_footer(); ?>
