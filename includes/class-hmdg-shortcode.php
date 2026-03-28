<?php
/**
 * Shortcode handler — [hmdg-website-development-ai]
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HMDG_Shortcode {

    private static ?HMDG_Shortcode $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'hmdg-website-development-ai', [ $this, 'render' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    /**
     * Enqueue front-end assets only when the shortcode is present on the page.
     */
    public function enqueue_assets(): void {
        global $post;

        if ( ! is_a( $post, 'WP_Post' ) || ! has_shortcode( $post->post_content, 'hmdg-website-development-ai' ) ) {
            return;
        }

        // Fonts
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

        // Bootstrap 5
        wp_enqueue_style(
            'bootstrap-5',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
            [],
            '5.3.3'
        );

        // HMDG Global CSS
        wp_enqueue_style(
            'hmdg-global',
            HMDG_SP_ASSETS . 'css/hmdg-global.css',
            [ 'bootstrap-5' ],
            HMDG_SP_VERSION
        );

        // Bootstrap 5 JS (bundle includes Popper)
        wp_enqueue_script(
            'bootstrap-5',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
            [],
            '5.3.3',
            true
        );

        // HMDG Global JS
        wp_enqueue_script(
            'hmdg-global',
            HMDG_SP_ASSETS . 'js/hmdg-global.js',
            [ 'bootstrap-5' ],
            HMDG_SP_VERSION,
            true
        );

        wp_localize_script( 'hmdg-global', 'HMDG', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'hmdg_nonce' ),
            'version' => HMDG_SP_VERSION,
        ] );
    }

    /**
     * Render the shortcode output.
     *
     * @return string HTML output.
     */
    public function render(): string {
        ob_start();
        require HMDG_SP_DIR . 'templates/shortcode-display.php';
        return ob_get_clean();
    }
}
