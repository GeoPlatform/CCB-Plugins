
<div>
    <h5>
      <a href="<?php the_permalink(); ?>">
        <?php the_title();?></a>
      </h5>
    <h6><?php echo get_the_date();?></h6>

      <?php the_excerpt(); ?>
      <br />
</div>
