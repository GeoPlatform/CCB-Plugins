<!-- STILL NEEDS UPDATE -->

<?php get_header(); ?>
<?php get_template_part( 'sub-header-post', get_post_format() ); ?>

<?php //get_template_part( 'mega-menu', get_post_format() ); ?>
<?php //get_template_part( 'single-banner', get_post_format() ); ?>

<div class="l-body l-body--two-column">
  <div class="l-body__main-column">
          <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

        get_template_part( 'post-single', get_post_format() );

        //Un-comment the code below to show comments on the posts
        //if ( comments_open() || get_comments_number() ) :
        //	  comments_template();
        //	endif;
      endwhile; endif;
      ?>
  </div>
  <?php get_template_part( 'sidebar', get_post_format() ); ?>
</div>
<?php get_footer(); ?>
