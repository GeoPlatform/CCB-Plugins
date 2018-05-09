<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeoPlatform_Test
 */

get_header('ngda');
get_template_part( 'mega-menu', get_post_format() );
get_template_part( 'single-banner', get_post_format() );

?>
<div class="container">

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		</br>
		<?php
		if ( have_posts() ) : ?>
			<div class="row">
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'post-card', get_post_format() );

			endwhile; ?>

			</div><!--#row-->

			<?php
			the_posts_navigation();
			echo "</br>";

			else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- #container -->

<?php
//get_sidebar();
get_footer();
