<?php
/**
 * The template for Single post and page banners
 *
 * @link https://developer.wordpress.org/themes/template-files-section/post-template-files/
 * 
 * @package GeoPlatform CCB
 * 
 * @since 3.0.0
 */
?>
<div class="banner banner--fixed-height">
<!--Used for the Main banner background to show up properly-->
  <div class="content">
      <div class="container">
          <div class="row">
              <div class="col-md-10 col-sm-10 col-xs-12">
		            <?php if (is_single()){ ?>
                  <h3 style="color:white"><?php the_title(); ?></h3>
                  <p>
                    <?php echo wp_kses_post(get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true)); ?>
                  </p>
                <?php } elseif (is_page()) { ?>
                    <!--Otherwise page title shows above banner intro content-->
                    <h3 style="color:white"><?php the_title(); ?></h3>
                    <p>
                      <?php echo wp_kses_post(get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true)); ?>
                    </p>

                <?php } elseif (is_home()){
                   //get title through queried object, because the_title is more of a loop hook
                   $home_title = (empty(get_queried_object()->post_title)) ? 'Blog Posts' : get_queried_object()->post_title;
                   //get home banner meta https://wordpress.stackexchange.com/questions/208225/why-does-get-post-meta-not-work-with-the-posts-page
                   $home_banner_meta = (empty(get_post_meta( get_queried_object_id(), 'geop_ccb_custom_wysiwyg', true ))) ? 'Example Banner Content' : get_post_meta( get_queried_object_id(), 'geop_ccb_custom_wysiwyg', true );;
                   ?>
                   <h3 style="color:white"><?php echo $home_title; ?></h3>
                    <p>
                      <?php echo wp_kses_post($home_banner_meta); ?>
                    </p>
                <?php } elseif (is_archive()) {
                      the_archive_title( '<h1 class="page-title">', '</h1>' );
                      the_archive_description( '<div class="archive-description">', '</div>' );

                  } elseif (is_404()) { ?>
                      <h2 class="page-title">
                        <?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'geoplatform-ccb' ); ?>
                      </h2>
                  <?php } else {
                    //if nothing, then show nothing
                  }
                  ?>
              </div><!--#col-md-10 col-sm-10 col-xs-12-->
          </div><!--#row-->
      </div><!--#container-->
  </div><!--#content-->
</div> <!--#banner banner-fixed-height-->
