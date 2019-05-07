<?php
/**
 * The template for single post content
 *
 * @package GeoPlatform CCB
 *
 * @since 3.0.0
 */

echo "<article class='m-article' id='post-" . the_ID() . "' " . post_class() . ">";
	echo "<div class='m-article__desc'>";
		the_content('Read more...');
	echo "</div>";

	// the rest of the content
	echo "<div>" . the_tags("Tags:&nbsp", ',&nbsp', '') . "</div>";
	echo "<div>" . __( 'Updated on', 'geoplatform-ccb') . " " . get_the_modified_date() . "</div>";
echo "</article>";
