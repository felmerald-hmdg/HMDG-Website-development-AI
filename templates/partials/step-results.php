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
                <span class="hmdg-q-phase-badge">Phase 1 Complete</span>
                <h2 class="hmdg-results-header__title">
                    <?php esc_html_e( 'Your Site Plan', 'hmdg-site-planner' ); ?>
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
                <?php esc_html_e( 'Phase 2 Notes', 'hmdg-site-planner' ); ?>
            </button>
        </nav>
    </div>

    <!-- Tab Panels -->
    <div class="hmdg-results-content">

        <!-- PROJECT BRIEF -->
        <div class="hmdg-results-panel active" id="hmdg-tab-brief" role="tabpanel">
            <div class="hmdg-results-grid" id="hmdg-brief-content">
                <!-- Rendered by JS -->
            </div>
        </div>

        <!-- SITEMAP -->
        <div class="hmdg-results-panel" id="hmdg-tab-sitemap" role="tabpanel" hidden>
            <div id="hmdg-sitemap-content">
                <!-- Rendered by JS -->
            </div>
        </div>

        <!-- WIREFRAMES -->
        <div class="hmdg-results-panel" id="hmdg-tab-wireframes" role="tabpanel" hidden>
            <div id="hmdg-wireframes-content">
                <!-- Rendered by JS -->
            </div>
        </div>

        <!-- PHASE 2 NOTES -->
        <div class="hmdg-results-panel" id="hmdg-tab-phase2" role="tabpanel" hidden>
            <div id="hmdg-phase2-content">
                <!-- Rendered by JS -->
            </div>
        </div>

    </div><!-- .hmdg-results-content -->

    <!-- Footer CTA -->
    <div class="hmdg-results-footer">
        <button type="button" class="hmdg-btn hmdg-btn--secondary" disabled>
            <?php esc_html_e( 'Continue to Phase 2 →', 'hmdg-site-planner' ); ?>
        </button>
        <span class="hmdg-results-footer__note">
            <?php esc_html_e( 'Phase 2 — Website AI Automation is coming soon.', 'hmdg-site-planner' ); ?>
        </span>
    </div>

</div><!-- .hmdg-results-wrap -->
