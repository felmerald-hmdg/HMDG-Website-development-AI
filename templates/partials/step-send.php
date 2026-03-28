<?php
/**
 * Send to Project Manager — Phase 1 Step 4.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="hmdg-send-wrap" id="hmdg-send-wrap">

    <!-- Header -->
    <div class="hmdg-send-header">
        <div class="hmdg-send-header__inner">
            <div class="hmdg-send-header__icon" aria-hidden="true">✓</div>
            <div>
                <span class="hmdg-q-phase-badge"><?php esc_html_e( 'Plan Approved', 'hmdg-site-planner' ); ?></span>
                <h2 class="hmdg-send-header__title">
                    <?php esc_html_e( 'Send to HMDG Team', 'hmdg-site-planner' ); ?>
                </h2>
                <p class="hmdg-send-header__sub">
                    <?php esc_html_e( 'Your site plan is ready. Fill in your email and we\'ll send the full plan to the HMDG project manager.', 'hmdg-site-planner' ); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="hmdg-send-body">

        <!-- What will be sent -->
        <div class="hmdg-send-recipients">
            <p class="hmdg-send-recipients__label">
                <?php esc_html_e( 'This plan will be sent to:', 'hmdg-site-planner' ); ?>
            </p>
            <div class="hmdg-send-recipients__list">
                <div class="hmdg-send-recipient">
                    <span class="hmdg-send-recipient__icon">👤</span>
                    <div>
                        <strong><?php esc_html_e( 'HMDG Project Manager', 'hmdg-site-planner' ); ?></strong>
                        <span><?php echo esc_html( HMDG_Mailer::PM_EMAIL ); ?></span>
                    </div>
                </div>
                <div class="hmdg-send-recipient" id="hmdg-client-recipient" hidden>
                    <span class="hmdg-send-recipient__icon">📧</span>
                    <div>
                        <strong><?php esc_html_e( 'You (CC)', 'hmdg-site-planner' ); ?></strong>
                        <span id="hmdg-client-email-display"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form id="hmdg-send-form" novalidate>

            <div class="hmdg-send-field">
                <label class="hmdg-q-label" for="hmdg-send-client-email">
                    <?php esc_html_e( 'Your Email', 'hmdg-site-planner' ); ?>
                    <span style="color:#999;font-weight:400;"> — <?php esc_html_e( 'for a copy of this plan', 'hmdg-site-planner' ); ?></span>
                </label>
                <input
                    type="email"
                    id="hmdg-send-client-email"
                    name="client_email"
                    class="hmdg-q-input"
                    placeholder="<?php esc_attr_e( 'your@email.com', 'hmdg-site-planner' ); ?>"
                    autocomplete="email"
                />
            </div>

            <div class="hmdg-send-field">
                <label class="hmdg-q-label" for="hmdg-send-pm-notes">
                    <?php esc_html_e( 'Additional Notes for the Team', 'hmdg-site-planner' ); ?>
                    <span style="color:#999;font-weight:400;"> — <?php esc_html_e( 'optional', 'hmdg-site-planner' ); ?></span>
                </label>
                <textarea
                    id="hmdg-send-pm-notes"
                    name="pm_notes"
                    class="hmdg-q-textarea"
                    rows="3"
                    placeholder="<?php esc_attr_e( 'Any extra details, deadlines, or requests for the development team…', 'hmdg-site-planner' ); ?>"
                ></textarea>
            </div>

            <!-- What's included -->
            <div class="hmdg-send-includes">
                <p class="hmdg-send-includes__label">
                    <?php esc_html_e( 'Email includes:', 'hmdg-site-planner' ); ?>
                </p>
                <ul class="hmdg-send-includes__list">
                    <li>✓ <?php esc_html_e( 'Full Project Brief', 'hmdg-site-planner' ); ?></li>
                    <li>✓ <?php esc_html_e( 'Sitemap & Page Structure', 'hmdg-site-planner' ); ?></li>
                    <li>✓ <?php esc_html_e( 'Wireframe Overview', 'hmdg-site-planner' ); ?></li>
                    <li>✓ <?php esc_html_e( 'Development Notes', 'hmdg-site-planner' ); ?></li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="hmdg-send-actions">
                <button type="button" class="hmdg-btn hmdg-btn--secondary" id="hmdg-send-back">
                    ← <?php esc_html_e( 'Back to Plan', 'hmdg-site-planner' ); ?>
                </button>
                <button type="submit" class="hmdg-btn hmdg-btn--primary hmdg-send-submit" id="hmdg-send-submit">
                    <span aria-hidden="true">✉</span>
                    <?php esc_html_e( 'Send to HMDG to proceed to website development', 'hmdg-site-planner' ); ?>
                </button>
            </div>

            <div id="hmdg-send-error" class="hmdg-q-error" hidden></div>

        </form>

    </div>

</div><!-- .hmdg-send-wrap -->
