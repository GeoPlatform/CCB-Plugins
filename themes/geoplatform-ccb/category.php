<?php
/*
    A GeoPlatform Category Template
*/

get_header();
get_template_part( 'mega-menu', get_post_format() );

?>

<!--Used for the Main banner background to show up properly-->
<?php get_template_part( 'cat-banner', get_post_format() ); ?>

<div class="container-fluid">
  <div class="row">
	<br />
    <div class="col-md-9">
      <?php
      //gets id of current category
      $category = $wp_query->get_queried_object_id();
      //var_dump($category);

      if (current_user_can('read_private_posts')) {
        $SQLQuery = array(
        'orderby' => 'date',
        'post_status'=> array('publish','private'),
        'order' => 'DESC',
        'post_type' => array('post','page'),
        'cat'=> $category,
        );
      }
      else{
        $SQLQuery = array(
        'post_type' => array('post','page'),
        'orderby' => 'date',
        'order' => 'ASC',
        'cat'=> $category
        );
      }
       $the_query = new WP_Query( $SQLQuery );

       // The Loop
       if ( $the_query->have_posts() ) {
         while ( $the_query->have_posts() ) {
           $the_query->the_post();
           //echo $category;
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
