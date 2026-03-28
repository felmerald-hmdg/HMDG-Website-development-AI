<?php
/**
 * Dashboard view — HMDG Site Planner.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="hmdg-wrap">

    <!-- Header -->
    <header class="hmdg-header">
        <div class="hmdg-header__inner">
            <h1 class="hmdg-header__title">
                HMDG <span>Site Planner</span>
            </h1>
            <p class="hmdg-header__sub">
                Generate sitemaps and wireframes powered by AI.
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="hmdg-main container-fluid">
        <div class="row g-4">

            <!-- Phase Cards -->
            <div class="col-12">
                <h2><?php esc_html_e( 'Phases', 'hmdg-site-planner' ); ?></h2>
            </div>

            <!-- Phase 1 -->
            <div class="col-xl-4 col-md-6">
                <div class="hmdg-card hmdg-card--active">
                    <div class="hmdg-card__badge">Phase 1</div>
                    <h3 class="hmdg-card__title">Site Planner</h3>
                    <p class="hmdg-card__desc">
                        Generate a sitemap and wireframe based on client questionnaire responses.
                    </p>
                    <button class="hmdg-btn hmdg-btn--primary" id="hmdg-open-planner" type="button">
                        Open Site Planner
                    </button>
                </div>
            </div>

            <!-- Phase 2 -->
            <div class="col-xl-4 col-md-6">
                <div class="hmdg-card hmdg-card--soon">
                    <div class="hmdg-card__badge">Phase 2</div>
                    <h3 class="hmdg-card__title">Website AI Automation</h3>
                    <p class="hmdg-card__desc">Coming soon.</p>
                    <button class="hmdg-btn hmdg-btn--secondary" type="button" disabled>
                        Coming Soon
                    </button>
                </div>
            </div>

            <!-- Phase 3 -->
            <div class="col-xl-4 col-md-6">
                <div class="hmdg-card hmdg-card--soon">
                    <div class="hmdg-card__badge">Phase 3</div>
                    <h3 class="hmdg-card__title">SEO AI Automation</h3>
                    <p class="hmdg-card__desc">Coming soon.</p>
                    <button class="hmdg-btn hmdg-btn--secondary" type="button" disabled>
                        Coming Soon
                    </button>
                </div>
            </div>

            <!-- Phase 4 -->
            <div class="col-xl-4 col-md-6">
                <div class="hmdg-card hmdg-card--soon">
                    <div class="hmdg-card__badge">Phase 4</div>
                    <h3 class="hmdg-card__title">Marketing Consent + Clinik</h3>
                    <p class="hmdg-card__desc">Coming soon.</p>
                    <button class="hmdg-btn hmdg-btn--secondary" type="button" disabled>
                        Coming Soon
                    </button>
                </div>
            </div>

            <!-- Phase 5 -->
            <div class="col-xl-4 col-md-6">
                <div class="hmdg-card hmdg-card--soon">
                    <div class="hmdg-card__badge">Phase 5</div>
                    <h3 class="hmdg-card__title">Website Support Automation</h3>
                    <p class="hmdg-card__desc">Coming soon.</p>
                    <button class="hmdg-btn hmdg-btn--secondary" type="button" disabled>
                        Coming Soon
                    </button>
                </div>
            </div>

        </div>
    </main>

</div><!-- .hmdg-wrap -->
