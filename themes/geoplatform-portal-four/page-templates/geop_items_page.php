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
      The regex that redirects to this page will only trigger if the url is "resources/{id}".
      <br>
      It ASSUMES that any id input is 32 characters
      long and in hexidecimal format.
      <br>
      Any other inputs will either 404 or lead to a legitimate child page of resources if it exists.
    </p>
    <p>
      URL to a dataset with the assumed id being passed: <a href="<?php echo 'https://ual.geoplatform.gov/api/datasets/' . get_query_var('q') ?>">CLICK HERE</a>
      <br>
      The url above assumes that the ID provided is to a dataset and does not check this fact.
    </p>
    <?php
    echo "id passed: " . get_query_var('q') . "<br>";

    $current_url = home_url( add_query_arg( array(), $wp->request ) );
    echo $current_url . "<br><br>";

    echo "messy var_dump of all rewrite rules.<br>";
    var_dump($wp_rewrite->rules);



    ?>
  </div>
</div>
<?php get_footer(); ?>
