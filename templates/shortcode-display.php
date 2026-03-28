<?php
/**
 * Front-end template for [hmdg-website-development-ai] shortcode.
 *
 * Phase 1 — 5-step flow:
 *   1. Hero
 *   2. Questionnaire
 *   3. Results + Approval
 *   4. Send to PM
 *   5. Confirmation
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="hmdg-wrap hmdg-phase1-app" id="hmdg-app">

    <!-- ================================================================
         STEP 1 — HERO
    ================================================================ -->
    <section class="hmdg-step hmdg-hero" id="hmdg-step-hero" data-step="1">
        <div class="hmdg-hero__inner">

            <p class="hmdg-hero__label">
                <?php esc_html_e( 'HMDG website builder', 'hmdg-site-planner' ); ?>
            </p>

            <h1 class="hmdg-hero__headline">
                <?php esc_html_e( 'Generate sitemaps', 'hmdg-site-planner' ); ?><br>
                <?php esc_html_e( 'and wireframes', 'hmdg-site-planner' ); ?><br>
                <?php esc_html_e( 'with HMDG', 'hmdg-site-planner' ); ?>
            </h1>

            <p class="hmdg-hero__sub">
                <?php esc_html_e( 'Go from concept to wireframes in record time with AI Site Planner,', 'hmdg-site-planner' ); ?><br>
                <?php esc_html_e( 'ready for client review, feedback, and fast revisions.', 'hmdg-site-planner' ); ?>
            </p>

            <div class="hmdg-hero__input-wrap">
                <input
                    type="text"
                    id="hmdg-hero-prompt"
                    class="hmdg-hero__input"
                    placeholder="<?php esc_attr_e( 'Describe your project, and get an AI-powered wireframe in minutes…', 'hmdg-site-planner' ); ?>"
                    autocomplete="off"
                />
                <button class="hmdg-btn hmdg-btn--primary hmdg-hero__cta" id="hmdg-start" type="button">
                    <span class="hmdg-hero__cta-icon" aria-hidden="true">✦</span>
                    <?php esc_html_e( 'Generate', 'hmdg-site-planner' ); ?>
                </button>
            </div>

        </div>
    </section>

    <!-- ================================================================
         STEP 2 — QUESTIONNAIRE
    ================================================================ -->
    <section class="hmdg-step" id="hmdg-step-questionnaire" data-step="2" hidden>
        <?php require HMDG_SP_DIR . 'templates/partials/step-questionnaire.php'; ?>
    </section>

    <!-- ================================================================
         STEP 3 — RESULTS + APPROVAL
    ================================================================ -->
    <section class="hmdg-step" id="hmdg-step-results" data-step="3" hidden>
        <?php require HMDG_SP_DIR . 'templates/partials/step-results.php'; ?>
    </section>

    <!-- ================================================================
         STEP 4 — SEND TO PROJECT MANAGER
    ================================================================ -->
    <section class="hmdg-step" id="hmdg-step-send" data-step="4" hidden>
        <?php require HMDG_SP_DIR . 'templates/partials/step-send.php'; ?>
    </section>

    <!-- ================================================================
         STEP 5 — CONFIRMATION
    ================================================================ -->
    <section class="hmdg-step" id="hmdg-step-confirmation" data-step="5" hidden>
        <?php require HMDG_SP_DIR . 'templates/partials/step-confirmation.php'; ?>
    </section>

</div><!-- #hmdg-app -->
