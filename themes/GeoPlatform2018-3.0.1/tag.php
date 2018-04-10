<?php
/*
    A GeoPlatform Category Template
*/

get_header();
get_template_part( 'mega-menu', get_post_format() );

?>

<!--Used for the Main banner background to show up properly-->
<?php get_template_part( 'single-banner', get_post_format() ); ?>

<div class="container">
    <div class="row">
	<br />
  <div class="col-md-9">
          <h4>Posts and Pages with the Tag : <?php single_tag_title();?></h4>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
              <div class="svc-card" style="padding:inherit; margin-right:-1em;">


                <div class="svc-card__body" style="flex-basis:102%;">
                    <div class="svc-card__title"><?php the_title(); ?> <?php printf( __( '( %s )', 'geoplatform-2017-theme' ), get_post_type( get_the_ID() ) ); ?></div>

                    <p>
                        <?php the_excerpt('',TRUE);?>
                    </p>
                    <br>

                    <a class="btn btn-info" href="<?php the_permalink();?>">More Information</a>

                </div>
                </div>
                <br>

            <?php endwhile; endif; ?>
        </div>

        <div class="col-md-3">
            <?php get_template_part('sidebar'); ?>
        </div>
    </div>
	<br \>
</div><?php get_footer(); ?>
