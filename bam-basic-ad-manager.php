<?php

/**
 * @link              https://github.com/jacm365
 * @since             1.0.0
 * @package           Bam_Basic_Ad_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       BAM Basic Ad Manager
 * Plugin URI:        https://github.com/jacm365/bam
 * Description:       Basic ad manager plugin for Born Again Media.
 * Version:           1.0.0
 * Author:            Jaime Clavijo
 * Author URI:        https://github.com/jacm365
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bam-basic-ad-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Current plugin version.
define( 'BAM_BASIC_AD_MANAGER_VERSION', '1.0.0' );

// Plugin name.
define( 'BAM_BASIC_AD_MANAGE_NAME', plugin_basename(__FILE__));

// The code that runs during plugin activation.
function activate_bam_basic_ad_manager() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-bam-basic-ad-manager-activator.php';
    Bam_Basic_Ad_Manager_Activator::activate();
}

// The code that runs during plugin deactivation.
function deactivate_bam_basic_ad_manager() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-bam-basic-ad-manager-deactivator.php';
    Bam_Basic_Ad_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bam_basic_ad_manager' );
register_deactivation_hook( __FILE__, 'deactivate_bam_basic_ad_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bam-basic-ad-manager.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_bam_basic_ad_manager() {

    if(class_exists('Bam_Basic_Ad_Manager')) {  
        $plugin = new Bam_Basic_Ad_Manager();
        $plugin->run();
    }

}

run_bam_basic_ad_manager();
