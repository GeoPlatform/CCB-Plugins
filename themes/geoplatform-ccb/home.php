<?php
/**
 * A GeoPlatform Home Page Template, for showing blog posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#home-page-display
 *
* @package GeoPlatform CCB
*
* @since 3.1.2
 */

get_header();
get_template_part( 'mega-menu', get_post_format( ) );
get_template_part( 'single-banner', get_post_format() );
?>

<div class="container">
  <div class="row">
    <div class="col-md-8 col-sm-8">
        <?php
                //handling page content to show up if needed
                $geopccb_content = (empty(get_queried_object()->post_content)) ? '' : get_queried_object()->post_content;
                echo "<br>";
                echo wp_kses_post( $geopccb_content );
                echo "<br>";

            if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		        <?php the_title( sprintf( '<h3 style="margin-bottom:0"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );?>
                <a href="<?php echo esc_url( get_permalink()); ?>"<cite style="color:#006621"><?php echo esc_url( get_permalink());?></cite></a>
                <p>
                  <?php the_excerpt('',TRUE); ?>
                </p>
            </article><!-- #post-the_ID();-->
            <hr />
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
