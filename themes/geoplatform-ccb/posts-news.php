<!-- Posts right-->
  <?php if ( has_post_thumbnail()) {?>
   <div>
    <div class="col-md-4">
      <a class="media embed-responsive embed-responsive-16by9" href="<?php the_permalink(); ?>">

    <img class="embed-responsive-item" src=<?php the_post_thumbnail();?> />

    </a>
    </div>
    <div class="col-md-8">
      <h5>
        <a href="<?php the_permalink(); ?>">
          <?php the_title();?></a>
        </h5>
      <h6><?php echo get_the_date();?></h6>

        <?php the_excerpt(); ?>
        <br />
    </div>
  </div>
  <?php } else { ?>

    <div class="col-md-12">
      <h3>
        <a href="<?php the_permalink(); ?>">
          <?php the_title();?></a>
        </h3>
    <h4><?php echo get_the_date();?></h4>

      <?php the_excerpt(); ?>
      <!-- <?php the_content(); ?> -->
      <br />
    </div>
    <?php } ?>
