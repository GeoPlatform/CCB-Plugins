<?php
/**
 * Template Name: GeoPlatform Items Template
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */


global $wp_query;
global $wp;
get_header();
get_template_part( 'sub-header-post', get_post_format() );
?>

<div class="l-body l-body--one-column">
  <div class="l-body__main-column">
    <p>
      Throw anything into the address bar after "/resources/" and it will redirect here.
    </p>
      URL to a dataset with the assumed id being passed: <a href="<?php echo 'https://ual.geoplatform.gov/api/datasets/' . get_query_var('q') ?>">CLICK HERE</a>
    <br>
    <?php
    echo the_permalink();
    echo "<br>";
    echo "id passed: " . get_query_var('q');
    echo "<br><br>";
    var_dump($wp_rewrite->rules);
    echo "<br><br><br>";

    // echo 'Food : ' . $wp_query->query_vars['food'];
    // echo '<br />';
    // echo 'Variety : ' . $wp_query->query_vars['variety'];
    echo "<br><br><br>";

    // $current_url = home_url( add_query_arg( array(), $wp->request ) );
    echo $current_url;
    ?>
  </div>
</div>
<?php get_footer(); ?>
