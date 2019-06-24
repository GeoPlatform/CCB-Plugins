<?php
/**
 * Template Name: GeoPlatform Search Template
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
get_header();
get_template_part( 'sub-header-post', get_post_format() );

// $temp = get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response', true);
// var_dump($temp);
// echo "<br>";
// echo current_time( 'timestamp', TRUE );

$manager = WP_Session_Tokens::get_instance( wp_get_current_user()->ID );
$token = wp_get_session_token();
$session = $manager->get( $token );
var_dump($manager);
echo "<br>";
var_dump($token);
echo "<br>";
var_dump($session);

if ( have_posts() ) : while ( have_posts() ) : the_post();
  the_content();
endwhile; endif;

get_footer(); ?>
