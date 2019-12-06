<?php
/**
 * The template for displaying search results
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeoPlatform CCB
 *
 * Template Name: Search
 *
 * @since 3.0.0
 */

get_header();
get_template_part( 'sub-header-search', get_post_format() );

$geopportal_sidebar_vis = get_theme_mod('sidebar_controls', 'on');

if ($geopportal_sidebar_vis == 'on')
	echo "<div class='l-body l-body--two-column'>";
else
	echo "<div class='l-body l-body--one-column'>";

	echo "<div class='l-body__main-column'>";

	if ( have_posts() ) :

			// echo "<p><strong><span class='a-page__title'>";
			//
			// 	printf( esc_html__( 'Search Results for: %s', 'geoplatform-ccb' ), "<span>" . get_search_query() . '</span>' );
			// echo "</span></strong></p>";

		/* Start the Loop */
		while ( have_posts() ) : the_post();

			/**
			 * Run the loop for the search to output the results.
			 * If you want to overload this in a child theme then include a file
			 * called content-search.php and that will be used instead.
			 */
 			if ( has_post_thumbnail() )
 				$geopccb_search_disp_thumb = get_the_post_thumbnail_url();
			else
	      $geopccb_search_disp_thumb = get_template_directory_uri() . "/img/default-featured.jpg";


			echo "<div class='m-article m-article--flex'>";
				echo "<a class='m-article__thumbnail is-16x9' href='" . esc_url(get_permalink()) . "'>";
					echo "<img alt='Article Heading' src='" . $geopccb_search_disp_thumb . "'>";
				echo "</a>";
				echo "<div class='m-article__body'>";
					echo "<a class='m-article__heading' href='" . esc_url(get_permalink()) . "'>" . esc_attr(get_the_title()) . "</a>";
					echo "<div class='m-article__desc'>" . get_the_date("F j, Y") . "</div>";
					echo "<div class='m-article__desc'>". esc_attr(wp_strip_all_tags(get_the_excerpt())) . "</div>";
				echo "</div>";
			echo "</div>";

		endwhile;

		the_posts_navigation();

	else :

		echo "<article class='m-article'>";
			echo "<br><br>";
			echo "<span class='far fa-frown u-text--gargantuan'></span>";
			echo "<div class='m-article__heading'>Page Not Found</div>";
			echo "<div class='m-article__desc'>";
				echo "<p>" . esc_html( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'geoplatform-ccb' ) . "</p>";
				get_search_form();
			echo "</div>";
			echo "<br><br>";
		echo "</article>";

	endif;

  echo "</div>";

	if ($geopportal_sidebar_vis == 'on')
  	get_template_part( 'sidebar', get_post_format() );

echo "</div>";
get_footer();
