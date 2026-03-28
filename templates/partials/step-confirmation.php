<?php
/**
 * Confirmation — Phase 1 Step 5.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="hmdg-confirm-wrap" id="hmdg-confirm-wrap">

    <div class="hmdg-confirm-inner">

        <div class="hmdg-confirm-icon" aria-hidden="true">✓</div>

        <span class="hmdg-q-phase-badge"><?php esc_html_e( 'Plan Submitted', 'hmdg-site-planner' ); ?></span>

        <h2 class="hmdg-confirm-title">
            <?php esc_html_e( 'Your plan is on its way!', 'hmdg-site-planner' ); ?>
        </h2>

        <p class="hmdg-confirm-sub">
            <?php esc_html_e( 'The HMDG team has received your approved website plan and will review it shortly.', 'hmdg-site-planner' ); ?>
        </p>

        <!-- What happens next -->
        <div class="hmdg-confirm-next">
            <h3><?php esc_html_e( 'What happens next', 'hmdg-site-planner' ); ?></h3>
            <ol class="hmdg-confirm-steps">
                <li>
                    <span class="hmdg-confirm-steps__num">1</span>
                    <div>
                        <strong><?php esc_html_e( 'Plan Review', 'hmdg-site-planner' ); ?></strong>
                        <p><?php esc_html_e( 'The HMDG project manager reviews your sitemap and wireframes.', 'hmdg-site-planner' ); ?></p>
                    </div>
                </li>
                <li>
                    <span class="hmdg-confirm-steps__num">2</span>
                    <div>
                        <strong><?php esc_html_e( 'Project Kick-off', 'hmdg-site-planner' ); ?></strong>
                        <p><?php esc_html_e( 'The team will be in touch to confirm your project timeline.', 'hmdg-site-planner' ); ?></p>
                    </div>
                </li>
                <li>
                    <span class="hmdg-confirm-steps__num">3</span>
                    <div>
                        <strong><?php esc_html_e( 'Website Development', 'hmdg-site-planner' ); ?></strong>
                        <p><?php esc_html_e( 'Build begins based on your approved plan.', 'hmdg-site-planner' ); ?></p>
                    </div>
                </li>
            </ol>
        </div>

        <div class="hmdg-confirm-ref">
            <span><?php esc_html_e( 'Sent to:', 'hmdg-site-planner' ); ?> <?php echo esc_html( HMDG_Mailer::PM_EMAIL ); ?></span>
        </div>

        <button type="button" class="hmdg-btn hmdg-btn--secondary" id="hmdg-new-plan">
            <?php esc_html_e( '+ Start a New Plan', 'hmdg-site-planner' ); ?>
        </button>

    </div>

</div><!-- .hmdg-confirm-wrap -->
