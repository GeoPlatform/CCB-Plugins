<div class="col-sm-6 col-md-4">
<div class="gp-ui-card gp-ui-card--md gp-ui-card">
  <?php if ( has_post_thumbnail() ) {?>
    <a class="media embed-responsive embed-responsive-16by9" href="<?php the_permalink(); ?>"
        title="Register for the Geospatial Platform Workshop">

        <img class="embed-responsive-item" src="<?php the_post_thumbnail_url(); ?>" >

    </a>
    <div class="gp-ui-card__body">
        <div class="text--primary"><?php the_title(); ?></div>
        <div class="text--secondary"><?php echo get_the_date(); ?></div>
        <div class="text--supporting">
            <?php the_excerpt(); ?>
        </div>
    </div>

    <div class="gp-ui-card__footer">
        <div class="pull-right">
            <a href="<?php the_permalink(); ?>" class="btn btn-link pull-right"><?php _e( 'Learn More', 'geoplatform-ccb'); ?></a>
        </div>
    </div>

    <?php } else {?>
      <div class="gp-ui-card__body">
          <div class="text--primary"><?php the_title(); ?></div>
          <div class="text--secondary"><?php echo get_the_date(); ?></div>
          <div class="text--supporting">
              <?php the_excerpt(); ?>
          </div>
      </div>
      <div class="gp-ui-card__footer">
          <div class="pull-right">
              <a href="<?php the_permalink(); ?>" class="btn btn-link pull-right"><?php _e( 'Learn More', 'geoplatform-ccb'); ?></a>
          </div>
      </div>
  <?php } ?>
</div>
</div>
