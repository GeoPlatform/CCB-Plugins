<?php
/**
 * The template for displaying Tag archive
 *
 * @package GeoPlatform CCB
 *
 * @link https://codex.wordpress.org/Tag_Templates
 *
 * @since 3.0.0
 */

get_header();
// get_template_part( 'mega-menu', get_post_format() );

?>

<!--Used for the Main banner background to show up properly-->
<?php get_template_part( 'single-banner', get_post_format() ); ?>

<div class="container">
    <div class="row">
	<br />
  <div class="col-md-9">
          <h4><?php _e( 'Posts and Pages with the Tag : ', 'geoplatform-ccb') . single_tag_title();?></h4>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
              <div class="svc-card" style="padding:inherit; margin-right:-1em;">
                <div class="svc-card__body" style="flex-basis:102%;">
                    <div class="svc-card__title"><?php the_title(); ?> <?php printf( esc_html(__( '( %s )', 'geoplatform-ccb' )), esc_attr(get_post_type( get_the_ID() ) ) ); ?></div>
                    <p>
                        <?php the_excerpt('',TRUE);?>
                    </p>
                    <br>
                    <a class="btn btn-info" href="<?php the_permalink();?>"><?php _e( 'More Information', 'geoplatform-ccb'); ?></a>
                </div><!--#svc-card__body-->
                </div><!--#svc-card-->
                <br>
            <?php endwhile; endif; ?>
        </div><!--#col-md-9-->

        <div class="col-md-3">
            <?php get_template_part('sidebar'); ?>
        </div><!--#col-md-9-->
    </div><!--#row-->
	<br \>
</div><?php get_footer(); ?>
