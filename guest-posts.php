<?php

/**
 * Plugin Name:       Guest Post
 * Plugin URI:        https://profiles.wordpress.org/bansarikambariya/
 * Description:       The Guest-post plugin will provide functionality of adding custom post type posts from frontend side with essential validations for author user role only in draft mode. Only admin can publish it after reviewing it.
 * Version:           1.0.0
 * Author:            bansarikambariya
 * Author URI:        https://profiles.wordpress.org/bansarikambariya/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       guest-posts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GUEST_POSTS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-guest-posts-activator.php
 */
function activate_guest_posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-guest-posts-activator.php';
	Guest_Posts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-guest-posts-deactivator.php
 */
function deactivate_guest_posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-guest-posts-deactivator.php';
	Guest_Posts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_guest_posts' );
register_deactivation_hook( __FILE__, 'deactivate_guest_posts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-guest-posts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_guest_posts() {

	$plugin = new Guest_Posts();
	$plugin->run();

}
run_guest_posts();
