<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.geoplatform.gov
 * @since             1.0.0
 * @package           Geop_Maps
 *
 * @wordpress-plugin
 * Plugin Name:       Geoplatform Maps Plugin
 * Plugin URI:        www.geoplatform.gov
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Kevin Schmidt
 * Author URI:        www.geoplatform.gov
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geop-maps
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geop-maps-activator.php
 */
function activate_geop_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geop-maps-activator.php';
	Geop_Maps_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geop-maps-deactivator.php
 */
function deactivate_geop_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geop-maps-deactivator.php';
	Geop_Maps_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_geop_maps' );
register_deactivation_hook( __FILE__, 'deactivate_geop_maps' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geop-maps.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geop_maps() {

	$plugin = new Geop_Maps();
	$plugin->run();

}
run_geop_maps();

// Example 1 : WP Shortcode to display form on any page or post.
function form_creation(){
?>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="gp-ui-card gp-ui-card--minimal">
                        <div class="media">

                                                    <a class="embed-responsive embed-responsive-16by9" title="Fir Trees in the Continental U.S." href=" https://sit-viewer.geoplatform.us/?id=2e969783ac99a8b104aca8c482d5fbe7" target="_blank">

                        string(70) "https://sit-viewer.geoplatform.us/?id=2e969783ac99a8b104aca8c482d5fbe7"
                        <img class="embed-responsive-item" src="https://ual.geoplatform.gov/api/maps/2e969783ac99a8b104aca8c482d5fbe7/thumbnail" alt=""></a>
                        </div> <!--media-->
                          <div class="gp-ui-card__body" style="height:55px;">
                              <h4 class="text--primary">Fir Trees in the Continental U.S.</h4>
                          </div>
                    </div> <!--gp-ui-card gp-ui-card-minimal-->
                </div>
<?php
}
add_shortcode('testicleees', 'form_creation');
