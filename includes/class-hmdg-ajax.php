<?php
/**
 * AJAX handlers for HMDG Phase 1 — Site Planner.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HMDG_Ajax {

    private static ?HMDG_Ajax $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Generate + Regenerate (public)
        add_action( 'wp_ajax_hmdg_generate_site_plan',          [ $this, 'generate_site_plan' ] );
        add_action( 'wp_ajax_nopriv_hmdg_generate_site_plan',   [ $this, 'generate_site_plan' ] );

        add_action( 'wp_ajax_hmdg_regenerate_site_plan',        [ $this, 'regenerate_site_plan' ] );
        add_action( 'wp_ajax_nopriv_hmdg_regenerate_site_plan', [ $this, 'regenerate_site_plan' ] );

        // Send to PM (public)
        add_action( 'wp_ajax_hmdg_send_to_pm',                  [ $this, 'send_to_pm' ] );
        add_action( 'wp_ajax_nopriv_hmdg_send_to_pm',           [ $this, 'send_to_pm' ] );

        // Admin-only: save settings
        add_action( 'wp_ajax_hmdg_save_settings', [ $this, 'save_settings' ] );

        // Admin-only: test API connection
        add_action( 'wp_ajax_hmdg_test_api', [ $this, 'test_api' ] );
    }

    // -------------------------------------------------------------------------
    // Generate Site Plan
    // -------------------------------------------------------------------------

    public function generate_site_plan(): void {
        check_ajax_referer( 'hmdg_nonce', 'nonce' );

        // Simple rate limiting — 1 request per IP per 10 seconds
        $ip_key = 'hmdg_rate_' . md5( $_SERVER['REMOTE_ADDR'] ?? 'unknown' );
        if ( get_transient( $ip_key ) ) {
            wp_send_json_error( [ 'message' => __( 'Please wait a moment before generating again.', 'hmdg-site-planner' ) ], 429 );
        }
        set_transient( $ip_key, 1, 10 );

        $data = $this->sanitize_questionnaire( $_POST );

        $result = HMDG_AI_Engine::instance()->generate_site_plan( $data );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ] );
        }

        wp_send_json_success( $result );
    }

    // -------------------------------------------------------------------------
    // Regenerate Site Plan
    // -------------------------------------------------------------------------

    public function regenerate_site_plan(): void {
        check_ajax_referer( 'hmdg_nonce', 'nonce' );

        $ip_key = 'hmdg_rate_' . md5( $_SERVER['REMOTE_ADDR'] ?? 'unknown' );
        if ( get_transient( $ip_key ) ) {
            wp_send_json_error( [ 'message' => __( 'Please wait a moment before regenerating.', 'hmdg-site-planner' ) ], 429 );
        }
        set_transient( $ip_key, 1, 10 );

        $data          = $this->sanitize_questionnaire( $_POST );
        $feedback      = sanitize_textarea_field( $_POST['feedback']      ?? '' );
        $previous_json = wp_unslash( $_POST['previous_plan'] ?? '{}' );
        $previous_plan = json_decode( $previous_json, true ) ?: [];

        if ( empty( $feedback ) ) {
            wp_send_json_error( [ 'message' => __( 'Please describe what you\'d like to change.', 'hmdg-site-planner' ) ] );
        }

        $result = HMDG_AI_Engine::instance()->regenerate_site_plan( $data, $feedback, $previous_plan );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ] );
        }

        wp_send_json_success( $result );
    }

    // -------------------------------------------------------------------------
    // Send to Project Manager
    // -------------------------------------------------------------------------

    public function send_to_pm(): void {
        check_ajax_referer( 'hmdg_nonce', 'nonce' );

        $client_email = sanitize_email(        $_POST['client_email'] ?? '' );
        $pm_notes     = sanitize_textarea_field( $_POST['pm_notes']   ?? '' );
        $plan_json    = wp_unslash( $_POST['plan'] ?? '{}' );
        $plan         = json_decode( $plan_json, true ) ?: [];

        if ( empty( $plan ) ) {
            wp_send_json_error( [ 'message' => __( 'No plan data received.', 'hmdg-site-planner' ) ] );
        }

        $result = HMDG_Mailer::instance()->send_to_pm( $plan, $client_email, $pm_notes );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ] );
        }

        wp_send_json_success( [ 'message' => __( 'Plan sent successfully!', 'hmdg-site-planner' ) ] );
    }

    // -------------------------------------------------------------------------
    // Save Settings (admin only)
    // -------------------------------------------------------------------------

    public function save_settings(): void {
        check_ajax_referer( 'hmdg_admin_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => __( 'Unauthorized.', 'hmdg-site-planner' ) ], 403 );
        }

        $saved = HMDG_Settings::instance()->save( $_POST );

        if ( $saved ) {
            wp_send_json_success( [ 'message' => __( 'Settings saved.', 'hmdg-site-planner' ) ] );
        } else {
            wp_send_json_error( [ 'message' => __( 'Nothing changed.', 'hmdg-site-planner' ) ] );
        }
    }

    // -------------------------------------------------------------------------
    // Test API (admin only)
    // -------------------------------------------------------------------------

    public function test_api(): void {
        check_ajax_referer( 'hmdg_admin_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => __( 'Unauthorized.', 'hmdg-site-planner' ) ], 403 );
        }

        $settings = HMDG_Settings::instance();

        if ( ! $settings->has_api_key() ) {
            wp_send_json_error( [ 'message' => __( 'No API key saved.', 'hmdg-site-planner' ) ] );
        }

        // Send a minimal test prompt
        $test_data = [
            'business_name' => 'Test Business',
            'business_type' => 'Agency',
            'location'      => 'Test City',
            'language'      => 'English',
            'services'      => 'Web design',
            'goals'         => 'Get leads',
            'audience'      => 'Small businesses',
            'tone'          => 'Professional',
            'brand_style'   => 'Modern',
            'showcase'      => 'Yes',
            'features'      => [ 'Contact Form' ],
            'integrations'  => [],
            'competitors'   => '',
            'hosting'       => '',
            'description'   => 'API connection test — respond with valid JSON only.',
            'requirements'  => '',
        ];

        $result = HMDG_AI_Engine::instance()->generate_site_plan( $test_data );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ] );
        }

        wp_send_json_success( [ 'message' => __( 'API connection successful!', 'hmdg-site-planner' ) ] );
    }

    // -------------------------------------------------------------------------
    // Sanitize questionnaire input
    // -------------------------------------------------------------------------

    private function sanitize_questionnaire( array $post ): array {
        $features     = array_map( 'sanitize_text_field', (array) ( $post['features']     ?? [] ) );
        $integrations = array_map( 'sanitize_text_field', (array) ( $post['integrations'] ?? [] ) );

        return [
            'description'  => sanitize_textarea_field( $post['description']  ?? '' ),
            'business_name'=> sanitize_text_field(     $post['business_name']?? '' ),
            'business_type'=> sanitize_text_field(     $post['business_type']?? '' ),
            'location'     => sanitize_text_field(     $post['location']     ?? '' ),
            'language'     => sanitize_text_field(     $post['language']     ?? 'English' ),
            'services'     => sanitize_textarea_field( $post['services']     ?? '' ),
            'goals'        => sanitize_textarea_field( $post['goals']        ?? '' ),
            'audience'     => sanitize_textarea_field( $post['audience']     ?? '' ),
            'tone'         => sanitize_text_field(     $post['tone']         ?? '' ),
            'brand_style'  => sanitize_text_field(     $post['brand_style']  ?? '' ),
            'showcase'     => sanitize_text_field(     $post['showcase']     ?? 'No' ),
            'features'     => $features,
            'integrations' => $integrations,
            'competitors'  => sanitize_textarea_field( $post['competitors']  ?? '' ),
            'hosting'      => sanitize_text_field(     $post['hosting']      ?? '' ),
            'requirements' => sanitize_textarea_field( $post['requirements'] ?? '' ),
        ];
    }
}
