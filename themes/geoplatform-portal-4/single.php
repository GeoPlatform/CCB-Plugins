<!-- STILL NEEDS UPDATE -->

<?php get_header(); ?>
<?php get_template_part( 'sub-header-post', get_post_format() ); ?>

<?php //get_template_part( 'mega-menu', get_post_format() ); ?>
<?php //get_template_part( 'single-banner', get_post_format() ); ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-8">
          <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

        get_template_part( 'post-single', get_post_format() );

        //Un-comment the code below to show comments on the posts
        //if ( comments_open() || get_comments_number() ) :
        //	  comments_template();
        //	endif;
      endwhile; endif;
      ?>
    </div>
    <div class="col-md-4 col-sm-4">
      <?php get_template_part( 'sidebar', get_post_format() ); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
