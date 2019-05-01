<?php
// Secondary header, used for the home page.
global $wp;

?>

<ul class="m-page-breadcrumbs">
    <li><a href="<?php echo home_url() ?>/">Home</a></li>
    <li><a href='#'/>
      <?php
        printf( esc_html__( 'Search Results for: %s', 'geoplatform-ccb' ), "<span>" . get_search_query() . '</span>' );
      ?>
    </a></li>
</ul>
