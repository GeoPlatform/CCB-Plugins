<?php
/**
 * A GeoPlatform Header search bar template for Geoplatform Search plugin integration.
 *
 * @link https://codex.wordpress.org/Theme_Development#Footer_.28footer.php.29
 *
 * @package GeoPlatform CCB
 *
 * @since 3.1.3
 */

// Visual output.
echo "<form id='geoplatformsearchform'>";
  echo "<div class='input-group-slick geop-header-search-min-geop'>";
    echo "<span class='icon fas fa-search'></span>";
    echo "<input type='text' class='form-control' style='padding-right:5em' id='geoplatformsearchfield' aria-label='Search the Site' placeholder='" . esc_attr( 'Search GeoPlatform...', 'geoplatform-ccb' ) . "'/>";
    echo "<button type='submit' class='btn btn-default' id='geoplatformsearchbutton'>". __( 'Search', 'geoplatform-ccb') . "</button>";
  echo "</div>";
echo "</form>";

?>
<!-- Javascript integration -->
<script>

// Code section. First jQuery triggers off of form submission (enter button) and
// navigates to the geoplatform-search page with the search field params.
  jQuery( "#geoplatformsearchform" ).submit(function( event ) {
    event.preventDefault();
    window.location.href='<?php echo home_url('geoplatform-search') ?>/#/?q='+jQuery('#geoplatformsearchfield').val();
  });

// Functionally identical to above, triggered by submit button press.
  jQuery( "#geoplatformsearchbutton" ).click(function( event ) {
    window.location.href='<?php echo home_url('geoplatform-search') ?>/#/?q='+jQuery('#geoplatformsearchfield').val();
  });
</script>
