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
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

              get_template_part( 'cat-body', get_post_format() );

              //Un-comment the code below to show comments on the posts
              //if ( comments_open() || get_comments_number() ) :
              //      comments_template();
              //    endif;
            endwhile; endif;
            ?>
    </div><!--#col-md-9-->
    <div class="col-md-3">
        <?php get_template_part('sidebar'); ?>
    </div><!--#col-md-3-->
  </div><!--#row-->
	<br \>
</div><!--#container-fluid-->
<?php get_footer(); ?>
