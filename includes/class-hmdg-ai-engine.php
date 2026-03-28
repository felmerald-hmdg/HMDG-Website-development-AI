<?php
/**
 * AI Engine — builds the Phase 1 prompt and calls the configured AI provider.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HMDG_AI_Engine {

    private static ?HMDG_AI_Engine $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}

    /**
     * Generate a full Phase 1 site plan from client questionnaire data.
     *
     * @param array $data Sanitized questionnaire fields.
     * @return array|WP_Error Decoded JSON array on success, WP_Error on failure.
     */
    public function generate_site_plan( array $data ): array|WP_Error {
        $settings = HMDG_Settings::instance();

        if ( ! $settings->has_api_key() ) {
            return new WP_Error( 'no_api_key', __( 'No API key configured. Please set it in HMDG Planner → Settings.', 'hmdg-site-planner' ) );
        }

        $provider   = $settings->get( 'provider' );
        $user_input = $this->build_user_input( $data );

        $raw = match ( $provider ) {
            'openai' => $this->call_openai( $settings, $user_input ),
            default  => $this->call_claude( $settings, $user_input ),
        };

        if ( is_wp_error( $raw ) ) {
            return $raw;
        }

        return $this->parse_response( $raw );
    }

    /**
     * Regenerate a site plan based on client revision feedback.
     *
     * @param array  $data          Original questionnaire data.
     * @param string $feedback      Client's revision request.
     * @param array  $previous_plan The previously generated plan.
     * @return array|WP_Error
     */
    public function regenerate_site_plan( array $data, string $feedback, array $previous_plan ): array|WP_Error {
        $settings = HMDG_Settings::instance();

        if ( ! $settings->has_api_key() ) {
            return new WP_Error( 'no_api_key', __( 'No API key configured.', 'hmdg-site-planner' ) );
        }

        $provider        = $settings->get( 'provider' );
        $original_input  = $this->build_user_input( $data );
        $plan_summary    = wp_json_encode( $previous_plan, JSON_PRETTY_PRINT );

        $revision_input  = <<<INPUT
ORIGINAL CLIENT DATA:
{$original_input}

PREVIOUS SITE PLAN (JSON):
{$plan_summary}

CLIENT REVISION REQUEST:
{$feedback}

Please revise the site plan based on the client's feedback. Keep everything they liked and improve only what was requested. Return the full updated plan in the same JSON format.
INPUT;

        $raw = match ( $provider ) {
            'openai' => $this->call_openai( $settings, $revision_input ),
            default  => $this->call_claude( $settings, $revision_input ),
        };

        if ( is_wp_error( $raw ) ) {
            return $raw;
        }

        return $this->parse_response( $raw );
    }

    // -------------------------------------------------------------------------
    // Prompt builders
    // -------------------------------------------------------------------------

    private function system_prompt(): string {
        return <<<'PROMPT'
You are an AI Website Planning Engine for HMDG AI Automation System.

Your task is to ONLY focus on PHASE 1: SITE PLANNER.
Do NOT generate full websites, code, or deployment.
Only generate structured planning outputs.

========================
SYSTEM CONTEXT
========================

This module is inspired by Elementor AI Site Planner.
Your role is to simulate a professional web strategist, UX planner, and sitemap architect.

========================
PHASE 1 SCOPE ONLY
========================

You MUST ONLY handle:
- Questionnaire Processing
- Sitemap Generation
- Wireframe Generation
- Structured Planning Output

You MUST NOT:
- Build Elementor layouts
- Generate code (HTML/CSS/JS)
- Deploy or connect to WordPress
- Skip structured format

========================
PROCESS
========================

1. Validate client input — if data is missing or vague, make educated professional assumptions.
2. Generate a Project Brief.
3. Generate a Sitemap using SEO-friendly hierarchy including service, location, and conversion pages.
4. Generate Wireframes for each sitemap page with UX best practices and conversion-first thinking.
5. Prepare Phase 2 automation-ready notes.

========================
RULES
========================

- Be strategic, not generic.
- Think like a senior web designer.
- Think conversion-first (leads, bookings, sales).
- Avoid filler content.
- Keep outputs structured and reusable.
- Match tone and style to the business type and stated voice.

========================
OUTPUT REQUIREMENTS
========================

Return ONLY a single valid JSON object. No markdown, no code blocks, no explanation text before or after.

Use this exact structure:

{
  "project_brief": {
    "business_overview": "2-3 sentence overview",
    "target_audience": "description of ideal customer",
    "goals": ["goal 1", "goal 2"],
    "unique_value_proposition": "what sets this business apart",
    "tone": "the voice and tone",
    "features": ["feature 1", "feature 2"],
    "recommended_pages": ["Page Name 1", "Page Name 2"]
  },
  "sitemap": {
    "pages": [
      {
        "name": "Home",
        "slug": "/",
        "purpose": "Main entry point and conversion hub",
        "children": [
          { "name": "Child Page", "slug": "/child", "purpose": "purpose", "children": [] }
        ]
      }
    ]
  },
  "wireframes": [
    {
      "page": "Home",
      "slug": "/",
      "sections": [
        {
          "name": "Hero",
          "key_message": "primary headline message",
          "cta": "Call to action text",
          "description": "What this section shows and its purpose"
        }
      ]
    }
  ],
  "phase2_notes": {
    "automation_ready": true,
    "key_integrations": ["CRM", "Contact Form"],
    "content_strategy": "Summary of content approach",
    "seo_focus": "Primary SEO strategy",
    "conversion_priorities": ["priority 1", "priority 2"]
  }
}
PROMPT;
    }

    private function build_user_input( array $data ): string {
        $features     = implode( ', ', (array) ( $data['features']     ?? [] ) );
        $integrations = implode( ', ', (array) ( $data['integrations'] ?? [] ) );

        return <<<INPUT
CLIENT QUESTIONNAIRE DATA:

Business Name: {$data['business_name']}
Business Type: {$data['business_type']}
Business Location: {$data['location']}
Site Language: {$data['language']}

Services / Products:
{$data['services']}

Business Goals:
{$data['goals']}

Target Audience:
{$data['audience']}

Tone & Voice: {$data['tone']}
Brand Style: {$data['brand_style']}
Showcase / Portfolio: {$data['showcase']}

Key Features: {$features}
Integrations: {$integrations}

Competitors:
{$data['competitors']}

Hosting Platform: {$data['hosting']}

Project Description / Additional Requirements:
{$data['description']}
{$data['requirements']}
INPUT;
    }

    // -------------------------------------------------------------------------
    // API calls
    // -------------------------------------------------------------------------

    private function call_claude( HMDG_Settings $settings, string $user_input ): string|WP_Error {
        $response = wp_remote_post(
            'https://api.anthropic.com/v1/messages',
            [
                'timeout' => 90,
                'headers' => [
                    'x-api-key'         => $settings->get( 'api_key' ),
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ],
                'body' => wp_json_encode( [
                    'model'      => $settings->get( 'model' ),
                    'max_tokens' => (int) $settings->get( 'max_tokens' ),
                    'system'     => $this->system_prompt(),
                    'messages'   => [
                        [ 'role' => 'user', 'content' => $user_input ],
                    ],
                ] ),
            ]
        );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );

        if ( $code !== 200 ) {
            $err = json_decode( $body, true );
            return new WP_Error( 'api_error', $err['error']['message'] ?? "Claude API error (HTTP {$code})" );
        }

        $decoded = json_decode( $body, true );
        return $decoded['content'][0]['text'] ?? '';
    }

    private function call_openai( HMDG_Settings $settings, string $user_input ): string|WP_Error {
        $response = wp_remote_post(
            'https://api.openai.com/v1/chat/completions',
            [
                'timeout' => 90,
                'headers' => [
                    'Authorization' => 'Bearer ' . $settings->get( 'api_key' ),
                    'content-type'  => 'application/json',
                ],
                'body' => wp_json_encode( [
                    'model'    => $settings->get( 'model' ),
                    'messages' => [
                        [ 'role' => 'system', 'content' => $this->system_prompt() ],
                        [ 'role' => 'user',   'content' => $user_input ],
                    ],
                ] ),
            ]
        );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );

        if ( $code !== 200 ) {
            $err = json_decode( $body, true );
            return new WP_Error( 'api_error', $err['error']['message'] ?? "OpenAI API error (HTTP {$code})" );
        }

        $decoded = json_decode( $body, true );
        return $decoded['choices'][0]['message']['content'] ?? '';
    }

    // -------------------------------------------------------------------------
    // Response parser
    // -------------------------------------------------------------------------

    private function parse_response( string $raw ): array|WP_Error {
        // Strip markdown code fences if AI wrapped the JSON
        $clean = preg_replace( '/^```(?:json)?\s*/i', '', trim( $raw ) );
        $clean = preg_replace( '/\s*```$/', '', $clean );

        $data = json_decode( $clean, true );

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            return new WP_Error( 'parse_error', __( 'Could not parse AI response. Please try again.', 'hmdg-site-planner' ) );
        }

        return $data;
    }
}
