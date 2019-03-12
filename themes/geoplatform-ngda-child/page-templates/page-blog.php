<?php
/*
	Template Name: Blog
*/
?>
<?php get_header('ngda'); ?>
<!--Used for the Main banner background to show up properly-->
<?php get_template_part( 'single-banner', get_post_format() ); ?>
<div class="container-fluid">
  <div class="row">
	<br />
    <div class="col-md-9">

    		<?php // Display blog posts on any page @ https://m0n.co/l
    		$temp = $wp_query; $wp_query= null;
    		$wp_query = new WP_Query(); $wp_query->query('posts_per_page=5' . '&paged='.$paged);
    		while ($wp_query->have_posts()) : $wp_query->the_post();


          // Checks if there's data in the excerpt and, if so, assigns it to be displayed.
          // If not, grabs post content and clips it at 400 characters.
          if (!empty(get_the_excerpt()))
            $geopccb_excerpt = esc_attr(wp_strip_all_tags(get_the_excerpt()));
          else{
            $geopccb_excerpt = esc_attr(wp_strip_all_tags(get_the_excerpt()));
            if (strlen($geopccb_excerpt) > 400)
              $geopccb_excerpt = esc_attr(substr($geopccb_excerpt, 0, 400) . '...');
          }
          ?>

          <br/>
          <?php if (has_post_thumbnail()){ ?>
          <div class="svc-card">
            <a title="<?php the_title(); ?>" class="svc-card__img">
                <img src="<?php echo get_the_post_thumbnail_url(); ?>">
            </a>
            <div class="svc-card__body">
                <div class="svc-card__title"><?php the_title(); ?></div><!--#svc-card__title-->
                <p>
                  <?php echo $geopccb_excerpt;?>
                </p>
                <br/>
                <a class="btn btn-info" href="<?php the_permalink(); ?>"><?php _e( 'More Information', 'geoplatform-ccb'); ?></a>
            </div><!--#svc-card__body-->
          </div><!--#svc-card-->
          <br />

          <?php } else {?>
          <div class="svc-card" style="padding:inherit; margin-right:-1em;">
            <div class="svc-card__body" style="flex-basis:102%;">
                <div class="svc-card__title"><?php the_title(); ?></div><!--#svc-card__title-->
                <p>
                  <?php echo $geopccb_excerpt;?>
                </p>
                <br>
                <a class="btn btn-info" href="<?php the_permalink(); ?>"><?php _e( 'More Information', 'geoplatform-ccb'); ?></a>
            </div><!--#svc-card__body-->
          </div><!--#svc-card-->
          <br /><?php
          }
        ?>

    		<?php endwhile; ?>

    		<?php if ($paged > 1) { ?>

    		<nav id="nav-posts">
          <br />
    			<div class="prev"><?php next_posts_link('&laquo; Previous Posts'); ?></div>
    			<div class="next"><?php previous_posts_link('Newer Posts &raquo;'); ?></div>
    		</nav>

    		<?php } else { ?>

    		<nav id="nav-posts">
          <br />
    			<div class="prev"><?php next_posts_link('&laquo; Previous Posts'); ?></div>
    		</nav>

    		<?php } ?>

    		<?php wp_reset_postdata(); ?>


      </div><!--#col-md-9-->
      <div class="col-md-3">
          <?php get_template_part('sidebar'); ?>
      </div><!--#col-md-3-->
    </div><!--#row-->
  	<br>
  </div><!--#container-fluid-->
  <?php get_footer(); ?>
