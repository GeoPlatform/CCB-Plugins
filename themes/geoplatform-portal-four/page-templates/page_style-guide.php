<?php
/**
 * Template Name: Style Guide
 *
 *https://developer.wordpress.org/themes/template-files-section/page-templates/
 */



get_header();
get_template_part( 'sub-header-post', get_post_format() );
?>



<div class="l-body l-body--two-column p-style-guide p-sizing">
  <div class="l-body__side-column">
    <nav class="p-menu" role="menu">
      <div class="u-text--uc t-text--strong">Navigation</div>
      <a href="<?php echo home_url() ?>/styleguide/">Getting Started</a>
      <a href="<?php echo home_url() ?>/styleguide/guidelines/">Principles &amp; Guidelines</a>
      <a href="<?php echo home_url() ?>/styleguide/typography/">Typography</a>
      <a href="<?php echo home_url() ?>/styleguide/iconography/">Iconography</a>
      <a href="<?php echo home_url() ?>/styleguide/colors/">Colors</a>
      <a href="<?php echo home_url() ?>/styleguide/sizing/">Sizing &amp; Spacing</a>
      <a href="<?php echo home_url() ?>/styleguide/buttons/">Buttons</a>
      <a href="<?php echo home_url() ?>/styleguide/messages/">Messages</a>
      <a href="<?php echo home_url() ?>/styleguide/articles/">Articles</a>
      <a href="<?php echo home_url() ?>/styleguide/cards/">Cards</a>
      <a href="<?php echo home_url() ?>/styleguide/tiles/">Tiles</a>
      <a href="<?php echo home_url() ?>/styleguide/results/">Search Results</a>
    </nav>
    <br>
    <br>
  </div>



  <div class="l-body__main-column">
          <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

        get_template_part( 'post-style', get_post_format() );

        //Un-comment the code below to show comments on the posts
        //if ( comments_open() || get_comments_number() ) :
        //	  comments_template();
        //	endif;
      endwhile; endif;
      ?>
  </div>
</div>
<?php get_footer(); ?>
