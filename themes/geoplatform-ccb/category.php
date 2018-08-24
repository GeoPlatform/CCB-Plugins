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
      //var_dump($geopccb_category);

      if (current_user_can('read_private_posts')) {
        $geopccb_SQLQuery = array(
        'orderby' => 'date',
        'post_status'=> array('publish','private'),
        'order' => 'DESC',
        'post_type' => array('post','page'),
        'cat'=> $geopccb_category,
        );
      }
      else{
        $geopccb_SQLQuery = array(
        'post_type' => array('post','page'),
        'orderby' => 'date',
        'order' => 'ASC',
        'cat'=> $geopccb_category
        );
      }
       $geopccb_the_query = new WP_Query( $geopccb_SQLQuery );

       // The Loop
       if ( $geopccb_the_query->have_posts() ) {
         while ( $geopccb_the_query->have_posts() ) {
           $geopccb_the_query->the_post();
           get_template_part('cat-body', get_post_format());
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
