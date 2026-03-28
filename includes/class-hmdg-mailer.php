<?php
/**
 * Mailer — sends the approved site plan to the HMDG Project Manager.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HMDG_Mailer {

    private static ?HMDG_Mailer $instance = null;

    const PM_EMAIL = 'felmerald@hmdg.co.uk';

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}

    /**
     * Send the approved site plan to the PM and optionally CC the client.
     *
     * @param array  $plan          Structured plan data from the AI.
     * @param string $client_email  Client email address (may be empty).
     * @param string $pm_notes      Optional extra notes from the client.
     * @return true|WP_Error
     */
    public function send_to_pm( array $plan, string $client_email, string $pm_notes = '' ): true|WP_Error {
        $brief         = $plan['project_brief'] ?? [];
        $business_name = $brief['business_overview'] ?? 'New Client';
        $subject       = sprintf(
            'New Website Plan Approval – Ready for Development – %s',
            wp_strip_all_tags( $business_name )
        );

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: HMDG Site Planner <noreply@hmdg.co.uk>',
        ];

        if ( ! empty( $client_email ) && is_email( $client_email ) ) {
            $headers[] = 'CC: ' . sanitize_email( $client_email );
        }

        $body = $this->build_email_html( $plan, $client_email, $pm_notes );

        $sent = wp_mail( self::PM_EMAIL, $subject, $body, $headers );

        if ( ! $sent ) {
            return new WP_Error( 'mail_failed', __( 'Email could not be sent. Please check your WordPress mail configuration.', 'hmdg-site-planner' ) );
        }

        return true;
    }

    // -------------------------------------------------------------------------
    // Email Template
    // -------------------------------------------------------------------------

    private function build_email_html( array $plan, string $client_email, string $pm_notes ): string {
        $brief    = $plan['project_brief']  ?? [];
        $sitemap  = $plan['sitemap']        ?? [];
        $wf       = $plan['wireframes']     ?? [];
        $notes    = $plan['phase2_notes']   ?? [];
        $date     = date( 'F j, Y' );

        $goals    = implode( ', ', (array) ( $brief['goals']    ?? [] ) );
        $features = implode( ', ', (array) ( $brief['features'] ?? [] ) );

        $sitemap_text  = $this->render_sitemap_text( $sitemap['pages'] ?? [] );
        $wf_text       = $this->render_wireframes_text( $wf );
        $integrations  = implode( ', ', (array) ( $notes['key_integrations']      ?? [] ) );
        $conversions   = implode( ', ', (array) ( $notes['conversion_priorities'] ?? [] ) );

        ob_start();
        ?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:30px 0;">
<tr><td align="center">
<table width="620" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;max-width:620px;width:100%;">

    <!-- HEADER -->
    <tr>
        <td style="background:#db0a40;padding:32px 40px;">
            <p style="margin:0;color:rgba(255,255,255,0.75);font-size:13px;letter-spacing:0.06em;text-transform:uppercase;">HMDG Site Planner</p>
            <h1 style="margin:8px 0 0;color:#ffffff;font-size:24px;font-weight:800;line-height:1.2;">
                New Website Plan Approval<br>Ready for Development
            </h1>
        </td>
    </tr>

    <!-- META -->
    <tr>
        <td style="padding:24px 40px;border-bottom:1px solid #ebebeb;background:#fafafa;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="font-size:13px;color:#888;">Date</td>
                    <td style="font-size:13px;color:#222;font-weight:600;text-align:right;"><?php echo esc_html( $date ); ?></td>
                </tr>
                <?php if ( $client_email ) : ?>
                <tr>
                    <td style="font-size:13px;color:#888;padding-top:6px;">Client Email</td>
                    <td style="font-size:13px;color:#222;font-weight:600;text-align:right;padding-top:6px;"><?php echo esc_html( $client_email ); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td style="font-size:13px;color:#888;padding-top:6px;">Status</td>
                    <td style="padding-top:6px;text-align:right;">
                        <span style="background:#db0a40;color:#fff;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:0.07em;">Client Approved</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- PROJECT BRIEF -->
    <tr>
        <td style="padding:32px 40px 0;">
            <h2 style="margin:0 0 16px;font-size:16px;font-weight:800;color:#db0a40;text-transform:uppercase;letter-spacing:0.06em;border-bottom:2px solid #db0a40;padding-bottom:8px;">Project Brief</h2>

            <p style="margin:0 0 8px;font-size:13px;color:#888;text-transform:uppercase;letter-spacing:0.05em;">Business Overview</p>
            <p style="margin:0 0 20px;font-size:15px;color:#222;line-height:1.6;"><?php echo nl2br( esc_html( $brief['business_overview'] ?? '' ) ); ?></p>

            <p style="margin:0 0 8px;font-size:13px;color:#888;text-transform:uppercase;letter-spacing:0.05em;">Target Audience</p>
            <p style="margin:0 0 20px;font-size:15px;color:#222;line-height:1.6;"><?php echo nl2br( esc_html( $brief['target_audience'] ?? '' ) ); ?></p>

            <p style="margin:0 0 8px;font-size:13px;color:#888;text-transform:uppercase;letter-spacing:0.05em;">Unique Value Proposition</p>
            <p style="margin:0 0 20px;font-size:15px;color:#222;line-height:1.6;"><?php echo nl2br( esc_html( $brief['unique_value_proposition'] ?? '' ) ); ?></p>

            <?php if ( $goals ) : ?>
            <p style="margin:0 0 8px;font-size:13px;color:#888;text-transform:uppercase;letter-spacing:0.05em;">Goals</p>
            <p style="margin:0 0 20px;font-size:15px;color:#222;line-height:1.6;"><?php echo esc_html( $goals ); ?></p>
            <?php endif; ?>

            <?php if ( $features ) : ?>
            <p style="margin:0 0 8px;font-size:13px;color:#888;text-transform:uppercase;letter-spacing:0.05em;">Features</p>
            <p style="margin:0 0 20px;font-size:15px;color:#222;line-height:1.6;"><?php echo esc_html( $features ); ?></p>
            <?php endif; ?>

            <p style="margin:0 0 8px;font-size:13px;color:#888;text-transform:uppercase;letter-spacing:0.05em;">Tone & Voice</p>
            <p style="margin:0 0 4px;font-size:15px;color:#222;"><?php echo esc_html( $brief['tone'] ?? '' ); ?></p>
        </td>
    </tr>

    <!-- SITEMAP -->
    <tr>
        <td style="padding:32px 40px 0;">
            <h2 style="margin:0 0 16px;font-size:16px;font-weight:800;color:#db0a40;text-transform:uppercase;letter-spacing:0.06em;border-bottom:2px solid #db0a40;padding-bottom:8px;">Sitemap</h2>
            <div style="background:#f9f9f9;border-radius:8px;padding:16px 20px;">
                <pre style="margin:0;font-family:monospace;font-size:13px;color:#333;line-height:1.7;white-space:pre-wrap;"><?php echo esc_html( $sitemap_text ); ?></pre>
            </div>
        </td>
    </tr>

    <!-- WIREFRAMES -->
    <tr>
        <td style="padding:32px 40px 0;">
            <h2 style="margin:0 0 16px;font-size:16px;font-weight:800;color:#db0a40;text-transform:uppercase;letter-spacing:0.06em;border-bottom:2px solid #db0a40;padding-bottom:8px;">Wireframe Overview</h2>
            <?php echo $wf_text; // Pre-escaped below ?>
        </td>
    </tr>

    <!-- DEVELOPMENT NOTES -->
    <?php if ( $notes ) : ?>
    <tr>
        <td style="padding:32px 40px 0;">
            <h2 style="margin:0 0 16px;font-size:16px;font-weight:800;color:#db0a40;text-transform:uppercase;letter-spacing:0.06em;border-bottom:2px solid #db0a40;padding-bottom:8px;">Notes for Development</h2>

            <?php if ( $notes['content_strategy'] ?? '' ) : ?>
            <p style="margin:0 0 6px;font-size:13px;color:#888;">Content Strategy</p>
            <p style="margin:0 0 16px;font-size:14px;color:#222;line-height:1.6;"><?php echo esc_html( $notes['content_strategy'] ); ?></p>
            <?php endif; ?>

            <?php if ( $notes['seo_focus'] ?? '' ) : ?>
            <p style="margin:0 0 6px;font-size:13px;color:#888;">SEO Focus</p>
            <p style="margin:0 0 16px;font-size:14px;color:#222;line-height:1.6;"><?php echo esc_html( $notes['seo_focus'] ); ?></p>
            <?php endif; ?>

            <?php if ( $integrations ) : ?>
            <p style="margin:0 0 6px;font-size:13px;color:#888;">Key Integrations</p>
            <p style="margin:0 0 16px;font-size:14px;color:#222;"><?php echo esc_html( $integrations ); ?></p>
            <?php endif; ?>

            <?php if ( $conversions ) : ?>
            <p style="margin:0 0 6px;font-size:13px;color:#888;">Conversion Priorities</p>
            <p style="margin:0 0 4px;font-size:14px;color:#222;"><?php echo esc_html( $conversions ); ?></p>
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>

    <!-- PM NOTES -->
    <?php if ( $pm_notes ) : ?>
    <tr>
        <td style="padding:24px 40px 0;">
            <div style="background:#fff8e6;border-left:4px solid #f59e0b;border-radius:0 8px 8px 0;padding:16px 20px;">
                <p style="margin:0 0 6px;font-size:12px;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:0.05em;">Additional Notes from Client</p>
                <p style="margin:0;font-size:14px;color:#78350f;line-height:1.6;"><?php echo nl2br( esc_html( $pm_notes ) ); ?></p>
            </div>
        </td>
    </tr>
    <?php endif; ?>

    <!-- FOOTER -->
    <tr>
        <td style="padding:32px 40px;margin-top:32px;border-top:1px solid #ebebeb;">
            <p style="margin:0 0 4px;font-size:13px;color:#888;">Sent from</p>
            <p style="margin:0;font-size:14px;font-weight:700;color:#db0a40;">HMDG Site Planner — Phase 1</p>
            <p style="margin:8px 0 0;font-size:12px;color:#bbb;">This plan was approved by the client and is ready to proceed to website development.</p>
        </td>
    </tr>

</table>
</td></tr>
</table>

</body>
</html>
        <?php
        return ob_get_clean();
    }

    private function render_sitemap_text( array $pages, int $depth = 0 ): string {
        $out = '';
        foreach ( $pages as $page ) {
            $indent = str_repeat( '  ', $depth );
            $prefix = $depth === 0 ? '' : '├ ';
            $out   .= $indent . $prefix . ( $page['name'] ?? '' ) . ' (' . ( $page['slug'] ?? '' ) . ")\n";
            if ( ! empty( $page['children'] ) ) {
                $out .= $this->render_sitemap_text( $page['children'], $depth + 1 );
            }
        }
        return $out;
    }

    private function render_wireframes_text( array $wireframes ): string {
        $html = '';
        foreach ( $wireframes as $page ) {
            $name     = esc_html( $page['page'] ?? '' );
            $sections = $page['sections'] ?? [];
            $html    .= '<div style="margin-bottom:20px;">';
            $html    .= "<p style=\"margin:0 0 8px;font-size:14px;font-weight:700;color:#111;\">{$name}</p>";
            $html    .= '<ul style="margin:0;padding-left:20px;">';
            foreach ( $sections as $s ) {
                $sname = esc_html( $s['name']        ?? '' );
                $cta   = esc_html( $s['cta']         ?? '' );
                $desc  = esc_html( $s['description'] ?? '' );
                $html .= "<li style=\"font-size:13px;color:#444;margin-bottom:4px;\">";
                $html .= "<strong>{$sname}</strong>";
                if ( $cta )  $html .= " — CTA: <em>{$cta}</em>";
                if ( $desc ) $html .= "<br><span style=\"color:#888;\">{$desc}</span>";
                $html .= "</li>";
            }
            $html .= '</ul></div>';
        }
        return $html;
    }
}
