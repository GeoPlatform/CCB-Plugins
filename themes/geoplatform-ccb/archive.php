<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeoPlatform CCB
 *
 * Template Name: Archive
 *
 * @since 3.0.0
 */

get_header();
get_template_part( 'mega-menu', get_post_format() );
get_template_part( 'single-banner', get_post_format() );

?>
<div class="container">

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<br>
			<?php

			$geopccb_posts = get_posts(array(
				'post_type' => 'post',
				'orderby' => 'date',
				'order' => 'DSC',
				'numberposts' => -1,
			) );

			foreach($geopccb_posts as $geopccb_post){
				$geopccb_excerpt = substr(esc_attr($geopccb_post->post_content), 0, 55);
				?>
				<div class="col-sm-6 col-md-4">
				<div class="gp-ui-card gp-ui-card--md gp-ui-card">
				<?php if ( has_post_thumbnail($geopccb_post) ) {?>
				  <a class="media embed-responsive embed-responsive-16by9" href="<?php echo get_the_permalink($geopccb_post); ?>"
				      title="<?php _e( 'Register for the Geospatial Platform Workshop', 'geoplatform-ccb') ?> ">

				      <img class="embed-responsive-item" src="<?php echo get_the_post_thumbnail_url($geopccb_post); ?>" >
				  </a>
				  <div class="gp-ui-card__body">
				    <div class="text--primary"><?php echo get_the_title($geopccb_post); ?></div>
				    <div class="text--secondary"><?php echo get_the_date("F j, Y", $geopccb_post); ?></div>
				    <div class="text--supporting">
				      <?php echo $geopccb_post->post_content; ?>
				    </div>
				  </div>
		    	<div class="gp-ui-card__footer">
				    <div class="pull-right">
				        <a href="<?php echo get_the_permalink($geopccb_post); ?>" class="btn btn-link pull-right"><?php _e( 'Learn More', 'geoplatform-ccb'); ?></a>
				    </div>
				  </div>

				<?php } else {?>
				  <div class="gp-ui-card__body">
				    <div class="text--primary"><?php echo get_the_title($geopccb_post); ?></div>
				    <div class="text--secondary"><?php echo get_the_date("F j, Y", $geopccb_post); ?></div>
				    <div class="text--supporting">
				      <?php echo $geopccb_post->post_content; ?>
				    </div>
				  </div>
				  <div class="gp-ui-card__footer">
				    <div class="pull-right">
				      <a href="<?php echo get_the_permalink($geopccb_post); ?>" class="btn btn-link pull-right"><?php _e( 'Learn More', 'geoplatform-ccb'); ?></a>
				    </div>
				  </div>
				<?php } ?>
				</div>
			</div>
		  <?php } ?>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- #container -->

<?php
//get_sidebar();
get_footer();
