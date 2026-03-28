<?php
/**
 * Admin class — registers the HMDG menu and dashboard page.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HMDG_Admin {

    private static ?HMDG_Admin $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
    }

    /**
     * Register top-level admin menu and sub-pages.
     */
    public function register_menu(): void {
        add_menu_page(
            __( 'HMDG Site Planner', 'hmdg-site-planner' ),
            __( 'HMDG Planner', 'hmdg-site-planner' ),
            'manage_options',
            'hmdg-site-planner',
            [ $this, 'render_dashboard' ],
            'dashicons-layout',
            30
        );

        add_submenu_page(
            'hmdg-site-planner',
            __( 'Dashboard', 'hmdg-site-planner' ),
            __( 'Dashboard', 'hmdg-site-planner' ),
            'manage_options',
            'hmdg-site-planner',
            [ $this, 'render_dashboard' ]
        );

        add_submenu_page(
            'hmdg-site-planner',
            __( 'Settings', 'hmdg-site-planner' ),
            __( 'Settings', 'hmdg-site-planner' ),
            'manage_options',
            'hmdg-site-planner-settings',
            [ $this, 'render_settings' ]
        );
    }

    /**
     * Render the main dashboard view.
     */
    public function render_dashboard(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have permission to access this page.', 'hmdg-site-planner' ) );
        }

        require_once HMDG_SP_DIR . 'admin/views/dashboard.php';
    }

    /**
     * Render the settings view.
     */
    public function render_settings(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have permission to access this page.', 'hmdg-site-planner' ) );
        }

        require_once HMDG_SP_DIR . 'admin/views/settings.php';
    }
}
