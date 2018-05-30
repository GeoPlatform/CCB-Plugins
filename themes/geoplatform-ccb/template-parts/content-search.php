<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeoPlatform_Test
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php the_title( sprintf( '<h3 style="margin-bottom:0"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );

					?>
					<a href="<?php echo esc_url( get_permalink()); ?>"<cite style="color:#006621"><?php echo esc_url( get_permalink());?></cite></a>
					<p>
						<?php the_excerpt('',TRUE); ?>
					</p>


</article><!-- #post-<?php the_ID(); ?> -->
<hr />
