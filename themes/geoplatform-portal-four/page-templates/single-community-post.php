<?php
/**
 * Template Name: Community Post Template
 * Template Post Type: post, page, community-post
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
        $geop_portal_rebuilt_shortcode = "[geopmap id=" . $geop_portal_broken_shortcode[1] . " height=350 title=off]";
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

        <div class="m-section-group t-light">
          <?php echo do_shortcode($post->geopportal_compost_carousel_shortcode); ?>
        </div>

 <!-- Find Resources section, which redirects to the search interface. -->
        <div class="m-section-group">
            <article class="m-article">
                <div class="m-article__heading">Find Resources</div>
                <div class="m-article__desc">
                    <p>Find resources associated with <?php echo the_title() ?> using the controls below.</p>
                </div>
                <div class="article__actions">
                    <div class="flex-1 d-flex flex-justify-between flex-align-center">
                        <form class="input-group-slick flex-1" id="geop_community_resources_search_form">
                          <span class="icon fas fa-search"></span>
                          <input type="text" class="form-control" id="geop_community_resources_search_input" com_id="<?php echo $post->geopportal_compost_community_id ?>" aria-label="Search for resources" placeholder="Search for resources">
                        </form>
                        <button class="btn btn-secondary u-mg-left--lg" id="geop_community_resources_search_button">SEARCH</button>
                    </div>&nbsp;&nbsp;
                </div>
            </article>
        </div>

    <?php } ?>


      <script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery("#geop_community_resources_search_button").click(function(e){
            var geopportal_query_string = "/#/?communities=" + jQuery("#geop_community_resources_search_input").attr("com_id") + "&q=" + jQuery("#geop_community_resources_search_input").val();
            window.open(
  						"<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string,
  						'_blank'
  					);
          });

          jQuery("#geop_community_resources_search_form").submit(function(event){
            event.preventDefault();
            var geopportal_query_string = "/#/?communities=" + jQuery("#geop_community_resources_search_input").attr("com_id") + "&q=" + jQuery("#geop_community_resources_search_input").val();
            window.open(
  						"<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string,
  						'_blank'
  					);
          });
        });
      </script>


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
      <div class="m-article">
          <div class="m-article__desc">
            <img src="<?php echo get_the_post_thumbnail_url() ?>" width="100%">
          </div>
      </div>

      <div class="m-article">
          <div class="m-article__desc">
              <a class="btn btn-primary btn-block" href="<?php echo esc_url($post->geopportal_compost_community_url) ?>">
                  Go to the <?php echo the_title() ?> Portal
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
                    echo " (<a href='" . esc_url($post->geopportal_compost_sponsor_email) . "'>email</a>)";
                  }
                  echo "</div>";
                }
                if (isset($post->geopportal_compost_agency_name) && !empty($post->geopportal_compost_agency_name)){
                  echo "<div><strong>Theme Lead Agency:</strong> " . esc_attr($post->geopportal_compost_agency_name) . "</div>";
                }
                if (isset($post->geopportal_compost_lead_name) && !empty($post->geopportal_compost_lead_name)){
                  echo "<div><strong>Theme Lead:</strong> " . esc_attr($post->geopportal_compost_lead_name) . "</div>";
                }
                if (isset($post->geopportal_compost_community_url) && !empty($post->geopportal_compost_community_url)){
                  echo "<br>";
                  echo "Please visit the " . the_title() . " community portal at <a href='" . esc_url($post->geopportal_compost_community_url) . "'>" . esc_url($post->geopportal_compost_community_url) . "</a>";
                }
              ?>
          </div>
      </div>
      <?php } ?>

      <div class="m-article">
          <div class="m-article__heading">Additional Info</div>
          <div class="m-article__desc">
            <?php echo the_excerpt() ?>
          </div>
      </div>


  </div>
</div>
<?php get_footer(); ?>
