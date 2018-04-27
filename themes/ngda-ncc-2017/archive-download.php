<?php
/*
Template Name: Download Archives
*/
get_header();
get_template_part( 'mega-menu', get_post_format() );
get_template_part( 'single-banner', get_post_format() );

?>

<div class="container">

    <div class="row">
      <div class="col-md-8 col-sm-8">
        <?php the_content('',true);?>
        <br />
        <?php do_action('show_beautiful_filters', 'wpdmpro'); ?>

        <table class="table">
          <thead>
            <tr>
              <td>
                <h1 class="entry-title">List Items</h1>
              </td>
            </tr>
          </thead>
        <?php while ( have_posts() ) : the_post(); ?>

          <div class="entry-content">

            <?php get_template_part( 'table-view', get_post_format() ); ?>


          </div><!-- .entry-content -->


        <?php endwhile; // end of the loop. ?>


</table>

      </div> <!-- col-md-8 col-sm-8 -->
        <div class="col-md-4 col-sm-4">
          <?php get_template_part( 'sidebar', get_post_format() ); ?>
        </div> <!-- col-md-4 col-sm-4 -->
      </div> <!-- row -->
  </div> <!-- container -->
  <?php get_footer(); ?>
