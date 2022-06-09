<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.convertkit.com
 * @since             1.0
 * @package           ConvertKit_PMP
 *
 * @wordpress-plugin
 * Plugin Name:       Paid Memberships Pro - ConvertKit Integration
 * Plugin URI: 		  https://www.paidmembershipspro.com/add-ons/convertkit
 * Description: 	  Subscribe and tag your Paid Memberships Pro members in ConvertKit.
 * Version:           1.2.0
 * Author:            Paid Memberships Pro
 * Author URI:        https://www.paidmembershipspro.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       convertkit-pmp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-convertkit-pmp-activator.php
 */
function activate_convertkit_pmp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-convertkit-pmp-activator.php';
	ConvertKit_PMP_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-convertkit-pmp-deactivator.php
 */
function deactivate_convertkit_pmp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-convertkit-pmp-deactivator.php';
	ConvertKit_PMP_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_convertkit_pmp' );
register_deactivation_hook( __FILE__, 'deactivate_convertkit_pmp' );

/**
 * The core plugin class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-convertkit-pmp.php';

/**
 * Start execution of the plugin.
 *
 * @since    1.0.0
 */
function run_convertkit_pmp() {

	$plugin = new ConvertKit_PMP();
	$plugin->run();

}
run_convertkit_pmp();
