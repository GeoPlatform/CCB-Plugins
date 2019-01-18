<?php

// Post fetch
$geopsearch_post_fetch = get_posts(array(
  'post_type' => array('post', 'page'),
  'orderby' => 'date',
  'order' => 'DSC',
  'numberposts' => -1,
) );

// JQuery input parse
$geopsearch_one = sanitize_key($_POST["key_one"]);
$geopsearch_two = sanitize_key($_POST["key_two"]);
$geopsearch_three = sanitize_key($_POST["key_three"]);
$geopsearch_four = sanitize_key($_POST["key_four"]);
$geopsearch_five = sanitize_key($_POST["key_five"]);


// media grabs
$query_images_args = array(
    'post_type'      => 'attachment',
    'post_mime_type' => 'image',
    'post_status'    => 'inherit',
    'posts_per_page' => - 1,
);

$query_images = new WP_Query( $query_images_args );
$images = array();
foreach ( $query_images->posts as $image ) {
    $images[] = wp_get_attachment_url( $image->ID );
}

// $results = "";
// if ( count($geopsearch_term_fetch) > 0 ){
//     foreach ( $geopsearch_term_fetch as $term ) {
//         $results .= esc_html( $term->name ) . " ===== ";
//     }
// }
// echo($results);

$geopsearch_return = array();
array_push( $geopsearch_return, $geopsearch_one);
array_push( $geopsearch_return, $geopsearch_two);
array_push( $geopsearch_return, $geopsearch_three);
array_push( $geopsearch_return, $geopsearch_four);
array_push( $geopsearch_return, $geopsearch_five);


var_dump($geopsearch_post_fetch);
?>
