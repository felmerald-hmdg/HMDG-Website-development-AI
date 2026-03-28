<?php
/**
 * Core plugin class — handles asset enqueueing and global setup.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HMDG_Site_Planner {

    private static ?HMDG_Site_Planner $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    /**
     * Enqueue all admin-side assets for HMDG pages.
     */
    public function enqueue_admin_assets( string $hook ): void {
        if ( ! $this->is_hmdg_page( $hook ) ) {
            return;
        }

        // --- Fonts (Fontshare CDN) ---
        wp_enqueue_style(
            'hmdg-font-cabinet',
            'https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@800,700,500,400&display=swap',
            [],
            null
        );

        wp_enqueue_style(
            'hmdg-font-satoshi',
            'https://api.fontshare.com/v2/css?f[]=satoshi@500&display=swap',
            [],
            null
        );

        // --- Bootstrap 5 (CSS) ---
        wp_enqueue_style(
            'bootstrap-5',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
            [],
            '5.3.3'
        );

        // --- HMDG Global Styles ---
        wp_enqueue_style(
            'hmdg-global',
            HMDG_SP_ASSETS . 'css/hmdg-global.css',
            [ 'bootstrap-5' ],
            HMDG_SP_VERSION
        );

        // --- Bootstrap 5 (JS, no jQuery dependency) ---
        wp_enqueue_script(
            'bootstrap-5',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
            [],
            '5.3.3',
            true
        );

        // --- HMDG Global JS ---
        wp_enqueue_script(
            'hmdg-global',
            HMDG_SP_ASSETS . 'js/hmdg-global.js',
            [ 'bootstrap-5' ],
            HMDG_SP_VERSION,
            true
        );

        // Pass PHP data to JS
        wp_localize_script( 'hmdg-global', 'HMDG', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'hmdg_nonce' ),
            'version' => HMDG_SP_VERSION,
        ] );
    }

    /**
     * Check if the current admin page belongs to HMDG.
     */
    private function is_hmdg_page( string $hook ): bool {
        $hmdg_pages = [
            'toplevel_page_hmdg-site-planner',
            'hmdg_page_hmdg-site-planner',
        ];

        return in_array( $hook, $hmdg_pages, true )
            || str_contains( $hook, 'hmdg' );
    }
}
