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
get_template_part( 'sub-header-post', get_post_format() );
?>

<div class="l-body l-body--two-column p-ngda-theme">
  <div class="l-body__main-column">

 <!-- Map section. Needs work. -->
      <div class="m-section-group">
          <div class="m-article">
              <div class="m-article__heading">
                  Featured <?php echo the_title() ?> Data
              </div>
              <div class="m-map" id="themeMapContainer">
                <?php echo do_shortcode($post->geopportal_compost_map_shortcode) ?>
              </div>
          </div>
      </div>


 <!-- Data and search section. Needs extensive work. -->
      <div class="m-section-group t-light">
        <?php
        get_template_part( 'community-carousel', get_post_format() );
        ?>
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
                      <form class="input-group-slick flex-1">
                        <span class="icon fas fa-search"></span>
                        <input type="text" class="form-control" id="geop_community_resources_search_input" com_id="<?php echo $post->geopportal_compost_community_id ?>" aria-label="Search for resources" placeholder="Search for resources">
                      </form>
                      <button class="btn btn-secondary u-mg-left--lg" id="geop_community_resources_search_button">SEARCH</button>
                  </div>&nbsp;&nbsp;
              </div>
          </article>
      </div>

      <script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery("#geop_community_resources_search_button").click(function(event){
            alert("words");
            var geopportal_query_string = "/#/?communities=" + jQuery("#geop_community_resources_search_input").attr("com_id") + "&q=" + jQuery("#geop_community_resources_search_input").val();
            window.location.href="<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string;
          });

          jQuery( "#geop_community_resources_search_input" ).submit(function(event){
            event.preventDefault();
            var geopportal_query_string = "/#/?communities=" + <?php echo $post->geopportal_compost_community_id ?> + "&q=" + jQuery("geop_community_resources_search_input").val();
            window.location.href="<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string;
          });
        });
      </script>


 <!-- Section Heading section, gives information about the community using
      the post content. -->
      <div class="m-section-group">
          <article class="m-article">
              <div class="m-article__heading">Section Heading</div>
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                  ?><div class="m-article__desc"><?php
                    the_content();
                  ?></div><?php
                endwhile; endif; ?>
              <div class="article__actions">
                  <a href="#">Action</a>
              </div>
          </article>
      </div>
  </div>

<!-- Sidebar section. -->
  <div class="l-body__side-column">

      <!-- THUMBNAIL -->
      <div class="m-article">
          <div class="m-article__desc">
            <?php
            $geopportal_community_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
            if (has_post_thumbnail())
              $geopportal_community_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
            ?>

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

      <div class="m-article">
          <div class="m-article__heading">Community Details</div>
          <div class="m-article__desc">
              <div><strong>Sponsor:</strong> <?php echo esc_attr($post->geopportal_compost_sponsor_name) ?> (<a href="<?php echo esc_url($post->geopportal_compost_sponsor_email) ?>">email</a>)</div>
              <div><strong>Theme Lead Agency:</strong> <?php echo esc_attr($post->geopportal_compost_agency_name) ?></div>
              <div><strong>Theme Lead:</strong> <?php echo esc_attr($post->geopportal_compost_lead_name) ?></div>
              <br>
              Please visit the <?php echo the_title() ?> community portal at
              <a href="<?php echo esc_url($post->geopportal_compost_community_url) ?>"><?php echo esc_url($post->geopportal_compost_community_url) ?></a>
          </div>
      </div>

      <div class="m-article">
          <div class="m-article__heading">Additional Info</div>
          <div class="m-article__desc">
            <?php echo the_excerpt() ?>
          </div>
      </div>


  </div>
</div>
<?php get_footer(); ?>
