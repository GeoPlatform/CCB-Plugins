<?php
// JQuery input parse
$geopsearch_content_type = sanitize_key($_POST["type"]);
$geopsearch_content_search = sanitize_key($_POST["search"]);
$geopsearch_content_author = sanitize_key($_POST["author"]);
$geopsearch_content_page = sanitize_key($_POST["page"]);
$geopsearch_content_perpage = sanitize_key($_POST["per_page"]);
$geopsearch_content_order = sanitize_key($_POST["order"]);
$geopsearch_content_orderby = sanitize_key($_POST["orderby"]);

$geopsearch_content_terms = explode(" ", $geopsearch_content_search);

if ($geopsearch_content_type == 'post'){
  $geopsearch_post_args_two = array(
    'post_type' => 'post',
    'numberposts' => -1,
    'author_name' => $geopsearch_content_author,
    'order' => $geopsearch_content_order,
    'orderby' => $geopsearch_content_orderby,
    'tax_query' => array(
      'relation' => 'OR',
      array(
        'taxonomy' => 'category',
        'field' => 'slug',
        'terms' => array($geopsearch_content_search),
      ),
      array(
        'taxonomy' => 'post_tag',
        'field' => 'slug',
        'terms' => array($geopsearch_content_search),
      ),
    )
  );

  $geopsearch_post_args_one = array(
    'post_type' => 'post',
    'numberposts' => -1,
    'author_name' => $geopsearch_content_author,
    's' => $geopsearch_content_search,
    'order' => $geopsearch_content_order,
    'orderby' => $geopsearch_content_orderby,
  );

  $geopsearch_post_fetch_one = new WP_Query($geopsearch_post_args_one);
  $geopsearch_post_fetch_two = new WP_Query($geopsearch_post_args_two);
  $geopsearch_post_fetch_final = new WP_Query();

  $geopsearch_post_fetch_final->posts = array_unique( array_merge( $geopsearch_post_fetch_one->posts, $geopsearch_post_fetch_two->posts ), SORT_REGULAR );
  $geopsearch_post_fetch_final->post_count = count($geopsearch_post_fetch_final->posts);

  echo json_encode($geopsearch_post_fetch_final->posts);
}


if ($geopsearch_content_type == 'page'){
  $geopsearch_page_args_two = array(
    'post_type' => 'page',
    'numberposts' => -1,
    'author_name' => $geopsearch_content_author,
    'order' => $geopsearch_content_order,
    'orderby' => $geopsearch_content_orderby,
    'tax_query' => array(
      'relation' => 'OR',
      array(
        'taxonomy' => 'category',
        'field' => 'slug',
        'terms' => array($geopsearch_content_search),
      ),
      array(
        'taxonomy' => 'post_tag',
        'field' => 'slug',
        'terms' => array($geopsearch_content_search),
      ),
    )
  );

  $geopsearch_page_args_one = array(
    'post_type' => 'page',
    'numberposts' => -1,
    'author_name' => $geopsearch_content_author,
    's' => $geopsearch_content_search,
    'order' => $geopsearch_content_order,
    'orderby' => $geopsearch_content_orderby,
  );

  $geopsearch_page_fetch_one = new WP_Query($geopsearch_page_args_one);
  $geopsearch_page_fetch_two = new WP_Query($geopsearch_page_args_two);
  $geopsearch_page_fetch_combo = new WP_Query();
  $geopsearch_page_fetch_final = array();

  $geopsearch_page_fetch_combo->posts = array_unique( array_merge( $geopsearch_page_fetch_one->posts, $geopsearch_page_fetch_two->posts ), SORT_REGULAR );
  $geopsearch_page_fetch_combo->post_count = count($geopsearch_page_fetch_final->posts);

  echo json_encode($geopsearch_page_fetch_combo->posts);
}

if ($geopsearch_content_type == 'media'){
  $geopsearch_media_args = array(
    'post_type'      => 'attachment',
    'post_mime_type' => 'image',
    'post_status'    => 'inherit',
    'posts_per_page' => - 1,
    's' => $geopsearch_content_search,
    'order' => $geopsearch_content_order,
    'orderby' => $geopsearch_content_orderby,
  );

  $geopsearch_media_fetch = new WP_Query( $geopsearch_media_args );
  echo json_encode($geopsearch_media_fetch->posts);
}
?>
