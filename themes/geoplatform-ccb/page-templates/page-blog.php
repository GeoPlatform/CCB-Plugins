<?php
/*
	Template Name: Blog
*/
?>
<?php get_header(); ?>
<?php get_template_part( 'mega-menu', get_post_format() ); ?>
<!--Used for the Main banner background to show up properly-->
<?php get_template_part( 'single-banner', get_post_format() ); ?>
<div class="container">
  <div class="row">
    <div class="col-md-8 col-sm-8">
    	<article>

    		<?php // Display blog posts on any page @ https://m0n.co/l
    		$temp = $wp_query; $wp_query= null;
    		$wp_query = new WP_Query(); $wp_query->query('posts_per_page=5' . '&paged='.$paged);
    		while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    		<h3><a href="<?php the_permalink(); ?>" title="Read more"><?php the_title(); ?></a></h3>
        <h4><?php echo get_the_date(); ?></h4>
    		<?php the_excerpt(); ?>
        </div>
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


    	</article>
      <br />
    </div><!--#col-md-8 col-sm-8-->
    <div class="col-md-4 col-sm-4">
      <?php get_template_part( 'sidebar', get_post_format() ); ?>
    </div><!--#col-md-4 col-sm-4-->
  </div><!--#row-->
</div><!--#container-->
<?php get_footer(); ?>
