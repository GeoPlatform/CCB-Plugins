<?php
/*
    A GeoPlatform Home Page Template, for showing blog posts
*/
get_header();
get_template_part( 'mega-menu', get_post_format( ) );
get_template_part( 'single-banner', get_post_format() );
?>

<div class="container">
  <div class="row">
    <div class="col-md-8 col-sm-8">
        <?php 
            if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h3><a href="<?php the_permalink(); ?>" title="Read more"><?php the_title(); ?></a></h3>
                <h4><?php echo get_the_date(); ?></h4>
                <?php the_excerpt(); ?>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
        <nav id="nav-posts">
          <div class="row">
    			<div class="prev col-md-6"><?php next_posts_link('&laquo; Previous Posts'); ?></div>
    			<div class="next col-md-6 text-right"><?php previous_posts_link('Newer Posts &raquo;'); ?></div>
          </div>
    	</nav>
      <br />
    </div><!--#col-md-8 col-sm-8-->
    <div class="col-md-4 col-sm-4">
      <?php get_template_part( 'sidebar', get_post_format() ); ?>
    </div><!--#col-md-4 col-sm-4-->
  </div><!--#row-->
</div><!--#container-->
<?php get_footer(); ?>