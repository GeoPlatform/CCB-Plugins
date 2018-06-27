<br/>
<?php if (has_post_thumbnail()){ ?>
<div class="svc-card">
  <a title="<?php the_title(); ?>" class="svc-card__img">
      <img src="<?php the_post_thumbnail_url('post-thumbnail', ['class' => 'img-responsive responsive--full'])?>">
  </a>
  <div class="svc-card__body">
      <div class="svc-card__title"><?php the_title(); ?></div><!--#svc-card__title-->
        <p>
            <?php the_excerpt('',TRUE);?>
        </p>
      <br/>
      <a class="btn btn-info" href="<?php the_permalink();?>"><?php _e( 'More Information', 'geoplatform-ccb'); ?></a>
  </div><!--#svc-card__body-->
</div><!--#svc-card-->
<br />

<?php } else {?>
<div class="svc-card" style="padding:inherit; margin-right:-1em;">
  <div class="svc-card__body" style="flex-basis:102%;">
      <div class="svc-card__title"><?php the_title(); ?></div><!--#svc-card__title-->
        <p>
            <?php the_excerpt('Read more',TRUE);?>
        </p>
      <br>
      <a class="btn btn-info" href="<?php the_permalink();?>"><?php _e( 'More Information', 'geoplatform-ccb'); ?></a>
  </div><!--#svc-card__body-->
</div><!--#svc-card-->
<br />
  <?php } ?>
