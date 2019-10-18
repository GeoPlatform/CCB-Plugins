<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeoPlatform CCB
 *
 * Template Name: Blog
 *
 * @since 3.0.0
 */

get_header();
get_template_part( 'sub-header-post', get_post_format() );

?>
<div class="l-body l-body--two-column">
	<div class="l-body__main-column">
	<?php

    $geopportal_featured_sort_format = get_theme_mod('featured_appearance', 'date');
    $geopportal_pages_final = array();

    $geopportal_pages = get_posts(array(
      'post_type' => array('post','page'),
      'orderby' => 'date',
      'order' => 'DESC',
      'numberposts' => -1,
    ));

    // Mimics the old way of populating, but functional. Grabs all pages.
    // if ($geopportal_featured_sort_format == 'date'){
      $geopportal_pages_final = $geopportal_pages;
    // }
    // else {
    //   // Assigns pages with valid priority values to the trimmed array.
    //   $geopportal_pages_trimmed = array();
    //   foreach($geopportal_pages as $geopportal_page){
    //     if ($geopportal_page->geop_ccb_post_priority > 0)
    //       array_push($geopportal_pages_trimmed, $geopportal_page);
    //   }
		//
    //   // Bubble sorts the resulting pages.
    //   $geopportal_pages_size = count($geopportal_pages_trimmed)-1;
    //   for ($i = 0; $i < $geopportal_pages_size; $i++) {
    //     for ($j = 0; $j < $geopportal_pages_size - $i; $j++) {
    //       $k = $j + 1;
    //       $geopportal_test_left = $geopportal_pages_trimmed[$j]->geop_ccb_post_priority;
    //       $geopportal_test_right = $geopportal_pages_trimmed[$k]->geop_ccb_post_priority;
    //       if ($geopportal_test_left > $geopportal_test_right) {
    //         // Swap elements at indices: $j, $k
    //         list($geopportal_pages_trimmed[$j], $geopportal_pages_trimmed[$k]) = array($geopportal_pages_trimmed[$k], $geopportal_pages_trimmed[$j]);
    //       }
    //     }
    //   }
    //   $geopportal_pages_final = $geopportal_pages_trimmed;
    // }

 		foreach($geopportal_pages_final as $geopportal_post){

 			// Grabs default 404 image as thumb and overwrites if the post has one.
 			$geopportal_archive_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
 			if ( has_post_thumbnail($geopportal_post) )
 				$geopportal_archive_disp_thumb = get_the_post_thumbnail_url($geopportal_post, 'medium');

 			// To prevent entries overlapping their blocks, sets min height to match thumb.
 			// list($width, $height) = getimagesize($geopportal_archive_disp_thumb);
 			// $geopportal_archive_scaled_height = ((350 * $height) / $width) + 30;
    	?>

			<div class="m-article m-article--flex">
				<a class="m-article__thumbnail is-16x9" href="<?php echo get_the_permalink($geopportal_post); ?>">
					<img alt="Article Heading" src="<?php echo $geopportal_archive_disp_thumb ?>">
				</a>
				<div class="m-article__body">
					<a class="m-article__heading" href="<?php echo get_the_permalink($geopportal_post); ?>"><?php echo get_the_title($geopportal_post) ?></a>
					<div class="m-article__desc"><?php echo get_the_date("F j, Y", $geopportal_post->ID) ?></div>
					<div class="m-article__desc"><?php echo esc_attr(wp_strip_all_tags($geopportal_post->post_excerpt)) ?></div>
				</div>
			</div>

 		<?php } ?>

  </div>
  <?php get_template_part( 'sidebar', get_post_format() ); ?>
</div>
<?php get_footer(); ?>
