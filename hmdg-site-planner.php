<?php
/**
 * Plugin Name:       HMDG Site Planner
 * Plugin URI:        https://github.com/felmerald-hmdg/HMDG-Website-development-AI
 * Description:       AI-powered site planning tool that generates sitemaps and wireframes based on client prompts.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            HMDG
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hmdg-site-planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Constants
define( 'HMDG_SP_VERSION',   '1.0.0' );
define( 'HMDG_SP_FILE',      __FILE__ );
define( 'HMDG_SP_DIR',       plugin_dir_path( __FILE__ ) );
define( 'HMDG_SP_URL',       plugin_dir_url( __FILE__ ) );
define( 'HMDG_SP_ASSETS',    HMDG_SP_URL . 'assets/' );

// Autoload core classes
require_once HMDG_SP_DIR . 'includes/class-hmdg-settings.php';
require_once HMDG_SP_DIR . 'includes/class-hmdg-ai-engine.php';
require_once HMDG_SP_DIR . 'includes/class-hmdg-ajax.php';
require_once HMDG_SP_DIR . 'includes/class-hmdg-site-planner.php';
require_once HMDG_SP_DIR . 'includes/class-hmdg-shortcode.php';
require_once HMDG_SP_DIR . 'admin/class-hmdg-admin.php';

/**
 * Boot the plugin.
 */
function hmdg_site_planner_init(): void {
    HMDG_Settings::instance();
    HMDG_AI_Engine::instance();
    HMDG_Ajax::instance();
    HMDG_Site_Planner::instance();
    HMDG_Shortcode::instance();
    HMDG_Admin::instance();
}
add_action( 'plugins_loaded', 'hmdg_site_planner_init' );
