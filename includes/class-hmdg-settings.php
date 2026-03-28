<?php
/**
 * Settings — stores and retrieves HMDG AI configuration.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HMDG_Settings {

    private static ?HMDG_Settings $instance = null;

    const OPTION_KEY = 'hmdg_sp_options';

    private array $defaults = [
        'provider'   => 'claude',
        'api_key'    => '',
        'model'      => 'claude-opus-4-6',
        'max_tokens' => 4096,
    ];

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}

    public function get_all(): array {
        $saved = get_option( self::OPTION_KEY, [] );
        return array_merge( $this->defaults, is_array( $saved ) ? $saved : [] );
    }

    public function get( string $key ): mixed {
        return $this->get_all()[ $key ] ?? null;
    }

    public function save( array $data ): bool {
        $current  = $this->get_all();
        $provider = sanitize_text_field( $data['provider'] ?? $current['provider'] );
        $api_key  = sanitize_text_field( $data['api_key']  ?? $current['api_key'] );
        $model    = sanitize_text_field( $data['model']    ?? $current['model'] );
        $tokens   = absint( $data['max_tokens'] ?? $current['max_tokens'] );

        return update_option( self::OPTION_KEY, [
            'provider'   => $provider,
            'api_key'    => $api_key,
            'model'      => $model,
            'max_tokens' => $tokens ?: 4096,
        ], false );
    }

    public function has_api_key(): bool {
        return ! empty( $this->get( 'api_key' ) );
    }

    /** Available models per provider */
    public static function models(): array {
        return [
            'claude' => [
                'claude-opus-4-6'    => 'Claude Opus 4.6 (Most capable)',
                'claude-sonnet-4-6'  => 'Claude Sonnet 4.6 (Balanced)',
                'claude-haiku-4-5-20251001' => 'Claude Haiku 4.5 (Fast)',
            ],
            'openai' => [
                'gpt-4o'      => 'GPT-4o (Recommended)',
                'gpt-4-turbo' => 'GPT-4 Turbo',
                'gpt-3.5-turbo' => 'GPT-3.5 Turbo (Fast)',
            ],
        ];
    }
}
