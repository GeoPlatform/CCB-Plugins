<?php
/*
Template Name: Search Page
*/
?>

<?php
global $query_string;

$query_args = explode("&", $query_string);
$search_query = array();

if( strlen($query_string) > 0 ) {
	foreach($query_args as $key => $string) {
		$query_split = explode("=", $string);
		$search_query[$query_split[0]] = urldecode($query_split[1]);
	} // foreach
} //if

$search = new WP_Query($search_query);
?>
<!--Not completely implemented, will flesh out in next version
https://codex.wordpress.org/Creating_a_Search_Page
-->
<?php get_header(); ?>
<?php get_template_part( 'single-banner', get_post_format() ); ?>
<div class="container-fluid">
  <div class="row">
      <div class="col-lg-10 col-lg-offset-1">
    <div class="row">
        <div class="col-md-8 col-sm-8">
        <h3>Search Results</h3>
        <?php get_search_form(); ?>
    </div>
    <div class="col-md-4 col-sm-4">
      <?php
  global $wp_query;
  $total_results = $wp_query->found_posts;
  ?>
    </div>
  </div>
</div>
</div>
</div>

<?php get_footer(); ?>
