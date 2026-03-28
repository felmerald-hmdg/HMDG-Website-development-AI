<?php
/**
 * Front-end template for [hmdg-website-development-ai] shortcode.
 * Phase 1 — Landing / Hero.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="hmdg-wrap hmdg-landing">

    <section class="hmdg-hero">
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
                    id="hmdg-prompt"
                    class="hmdg-hero__input"
                    placeholder="<?php esc_attr_e( 'Describe your project, and get an AI-powered wireframe in minutes…', 'hmdg-site-planner' ); ?>"
                    autocomplete="off"
                />
                <button
                    class="hmdg-btn hmdg-btn--primary hmdg-hero__cta"
                    id="hmdg-generate"
                    type="button"
                >
                    <span class="hmdg-hero__cta-icon" aria-hidden="true">✦</span>
                    <?php esc_html_e( 'Generate', 'hmdg-site-planner' ); ?>
                </button>
            </div>

        </div>
    </section>

</div><!-- .hmdg-landing -->
