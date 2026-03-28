<?php
/**
 * Results partial — Phase 1 Step 3.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="hmdg-results-wrap" id="hmdg-results-wrap">

    <!-- Header -->
    <div class="hmdg-results-header">
        <div class="hmdg-results-header__inner">
            <div>
                <span class="hmdg-q-phase-badge">Phase 1 — Site Plan</span>
                <h2 class="hmdg-results-header__title">
                    <?php esc_html_e( 'Your Website Plan', 'hmdg-site-planner' ); ?>
                    <span id="hmdg-results-business-name"></span>
                </h2>
            </div>
            <button type="button" class="hmdg-btn hmdg-btn--secondary" id="hmdg-start-over">
                ← <?php esc_html_e( 'Start Over', 'hmdg-site-planner' ); ?>
            </button>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="hmdg-results-tabs-wrap">
        <nav class="hmdg-results-tabs" role="tablist">
            <button class="hmdg-results-tab active" data-tab="brief" role="tab" aria-selected="true">
                <?php esc_html_e( 'Project Brief', 'hmdg-site-planner' ); ?>
            </button>
            <button class="hmdg-results-tab" data-tab="sitemap" role="tab" aria-selected="false">
                <?php esc_html_e( 'Sitemap', 'hmdg-site-planner' ); ?>
            </button>
            <button class="hmdg-results-tab" data-tab="wireframes" role="tab" aria-selected="false">
                <?php esc_html_e( 'Wireframes', 'hmdg-site-planner' ); ?>
            </button>
            <button class="hmdg-results-tab" data-tab="phase2" role="tab" aria-selected="false">
                <?php esc_html_e( 'Dev Notes', 'hmdg-site-planner' ); ?>
            </button>
        </nav>
    </div>

    <!-- Tab Panels -->
    <div class="hmdg-results-content">

        <div class="hmdg-results-panel active" id="hmdg-tab-brief" role="tabpanel">
            <div class="hmdg-results-grid" id="hmdg-brief-content"></div>
        </div>

        <div class="hmdg-results-panel" id="hmdg-tab-sitemap" role="tabpanel" hidden>
            <div id="hmdg-sitemap-content"></div>
        </div>

        <div class="hmdg-results-panel" id="hmdg-tab-wireframes" role="tabpanel" hidden>
            <div id="hmdg-wireframes-content"></div>
        </div>

        <div class="hmdg-results-panel" id="hmdg-tab-phase2" role="tabpanel" hidden>
            <div id="hmdg-phase2-content"></div>
        </div>

    </div><!-- .hmdg-results-content -->

    <!-- ================================================================
         STEP 7 — CLIENT APPROVAL BAR
    ================================================================ -->
    <div class="hmdg-approval-bar">
        <div class="hmdg-approval-bar__inner">
            <p class="hmdg-approval-bar__question">
                <?php esc_html_e( 'Are you happy with this website plan?', 'hmdg-site-planner' ); ?>
            </p>
            <div class="hmdg-approval-bar__actions">
                <button type="button" class="hmdg-btn hmdg-btn--secondary" id="hmdg-request-changes">
                    ✕ <?php esc_html_e( 'Request Changes', 'hmdg-site-planner' ); ?>
                </button>
                <button type="button" class="hmdg-btn hmdg-btn--primary" id="hmdg-approve-plan">
                    ✓ <?php esc_html_e( 'Approve Plan', 'hmdg-site-planner' ); ?> →
                </button>
            </div>
        </div>
    </div>

    <!-- ================================================================
         REVISION PANEL (shown when client clicks "Request Changes")
    ================================================================ -->
    <div class="hmdg-revision-panel" id="hmdg-revision-panel" hidden>
        <div class="hmdg-revision-inner">
            <h3><?php esc_html_e( 'What would you like us to change?', 'hmdg-site-planner' ); ?></h3>
            <p><?php esc_html_e( 'Describe the changes and we\'ll regenerate your plan.', 'hmdg-site-planner' ); ?></p>
            <textarea
                id="hmdg-revision-notes"
                class="hmdg-q-textarea"
                rows="4"
                placeholder="<?php esc_attr_e( 'e.g. Add a blog section, change the tone to be more friendly, include a services page for each location…', 'hmdg-site-planner' ); ?>"
            ></textarea>
            <div class="hmdg-revision-actions">
                <button type="button" class="hmdg-btn hmdg-btn--primary" id="hmdg-regenerate">
                    <span aria-hidden="true">✦</span>
                    <?php esc_html_e( 'Regenerate Plan', 'hmdg-site-planner' ); ?>
                </button>
                <button type="button" class="hmdg-btn hmdg-btn--secondary" id="hmdg-cancel-revision">
                    <?php esc_html_e( 'Cancel', 'hmdg-site-planner' ); ?>
                </button>
            </div>
        </div>
    </div>

</div><!-- .hmdg-results-wrap -->
