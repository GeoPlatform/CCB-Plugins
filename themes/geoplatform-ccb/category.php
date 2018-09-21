<?php
/**
 * A GeoPlatform Category template
 *
 * @link https://codex.wordpress.org/Category_Templates
 *
 * @package GeoPlatform CCB
 *
 * @since 2.0.0
 */

get_header();
get_template_part( 'mega-menu', get_post_format() );
//Used for the Main banner background to show up properly
get_template_part( 'cat-banner', get_post_format() );
?>

<div class="container-fluid">
  <div class="row">
	<br />
    <div class="col-md-9">
      <?php
      //gets id of current category
      $geopccb_category = $wp_query->get_queried_object_id();

      // Get view perms.
      $geop_ccb_private_perm = array('publish');
      if (current_user_can('read_private_pages'))
        $geop_ccb_private_perm = array('publish', 'private');

      // Sets the result types to post and page, including cat links if not a child theme
      $geop_ccb_post_types = array('post','page');
      if (!is_child_theme())
        $geop_ccb_post_types = array('post','page','geopccb_catlink');

      $geopccb_featured_sort_format = get_theme_mod('featured_appearance', 'date');
      $geopccb_pages_final = array();

      $geopccb_pages = get_posts(array(
        'post_type' => $geop_ccb_post_types,
        'orderby' => 'date',
        'order' => 'DESC',
        'numberposts' => -1,
        'cat'=> $geopccb_category,
        'post_status' => $geop_ccb_private_perm
      ) );

      // Mimics the old way of populating, but functional. Grabs all pages.
      if ($geopccb_featured_sort_format == 'date'){
        $geopccb_pages_final = $geopccb_pages;
      }
      else {
        // Assigns pages with valid priority values to the trimmed array.
        $geopccb_pages_trimmed = array();
        foreach($geopccb_pages as $geopccb_page){
          if ($geopccb_page->geop_ccb_post_priority > 0)
            array_push($geopccb_pages_trimmed, $geopccb_page);
        }

        // Bubble sorts the resulting pages.
        $geopccb_pages_size = count($geopccb_pages_trimmed)-1;
        for ($i = 0; $i < $geopccb_pages_size; $i++) {
          for ($j = 0; $j < $geopccb_pages_size - $i; $j++) {
            $k = $j + 1;
            $geopccb_test_left = $geopccb_pages_trimmed[$j]->geop_ccb_post_priority;
            $geopccb_test_right = $geopccb_pages_trimmed[$k]->geop_ccb_post_priority;
            if ($geopccb_test_left > $geopccb_test_right) {
              // Swap elements at indices: $j, $k
              list($geopccb_pages_trimmed[$j], $geopccb_pages_trimmed[$k]) = array($geopccb_pages_trimmed[$k], $geopccb_pages_trimmed[$j]);
            }
          }
        }
        $geopccb_pages_final = $geopccb_pages_trimmed;
      }

      foreach ($geopccb_pages_final as $geopccb_post){

        // Checks if there's data in the excerpt and, if so, assigns it to be displayed.
        // If not, grabs post content and clips it at 400 characters.
        if (!empty($geopccb_post->post_excerpt))
          $geopccb_excerpt = esc_attr($geopccb_post->post_excerpt);
        else{
          $geopccb_excerpt = esc_attr($geopccb_post->post_content);
          if (strlen($geopccb_excerpt) > 400)
            $geopccb_excerpt = esc_attr(substr($geopccb_excerpt, 0, 400) . '...');
        }

        // Sets the More Information link to point to the post or page, but replaces
        // it with the cat link's URL custom value if it is a cat link.
        $geopccb_link_url = get_the_permalink($geopccb_post);
        if (get_post_type($geopccb_post) == 'geopccb_catlink')
          $geopccb_link_url = esc_url($geopccb_post->geop_ccb_cat_link_url);

        ?>
        <br/>
        <?php if (has_post_thumbnail($geopccb_post)){ ?>
        <div class="svc-card">
          <a title="<?php echo get_the_title($geopccb_post); ?>" class="svc-card__img">
              <img src="<?php echo get_the_post_thumbnail_url($geopccb_post); ?>">
          </a>
          <div class="svc-card__body">
              <div class="svc-card__title"><?php echo get_the_title($geopccb_post); ?></div><!--#svc-card__title-->
                <p>
                    <?php echo $geopccb_excerpt;?>
                </p>
              <br/>
              <a class="btn btn-info" href="<?php echo $geopccb_link_url; ?>" target="_blank"><?php _e( 'More Information', 'geoplatform-ccb'); ?></a>
          </div><!--#svc-card__body-->
        </div><!--#svc-card-->
        <br />

        <?php } else {?>
        <div class="svc-card" style="padding:inherit; margin-right:-1em;">
          <div class="svc-card__body" style="flex-basis:102%;">
              <div class="svc-card__title"><?php echo get_the_title($geopccb_post); ?></div><!--#svc-card__title-->
                <p>
                    <?php echo $geopccb_excerpt;?>
                </p>
              <br>
              <a class="btn btn-info" href="<?php echo $geopccb_link_url; ?>"><?php _e( 'More Information', 'geoplatform-ccb'); ?></a>
          </div><!--#svc-card__body-->
        </div><!--#svc-card-->
        <br /><?php
        }
      }
      ?>
    </div><!--#col-md-9-->
    <div class="col-md-3">
        <?php get_template_part('sidebar'); ?>
    </div><!--#col-md-3-->
  </div><!--#row-->
	<br \>
</div><!--#container-fluid-->
<?php get_footer(); ?>
