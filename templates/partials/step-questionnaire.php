<?php
/**
 * Questionnaire partial — Phase 1 Step 2.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="hmdg-q-wrap">

    <!-- Header -->
    <div class="hmdg-q-header">
        <button type="button" class="hmdg-q-back" id="hmdg-q-back">
            ← <?php esc_html_e( 'Back', 'hmdg-site-planner' ); ?>
        </button>
        <div class="hmdg-q-header__brand">
            <span class="hmdg-q-phase-badge">Phase 1</span>
            <span class="hmdg-q-header__title">
                <?php esc_html_e( 'Site Planner', 'hmdg-site-planner' ); ?>
            </span>
        </div>
    </div>

    <!-- Intro -->
    <div class="hmdg-q-intro">
        <h2><?php esc_html_e( 'Tell us about your project', 'hmdg-site-planner' ); ?></h2>
        <p><?php esc_html_e( 'Fill in the details below. The more you share, the better your site plan.', 'hmdg-site-planner' ); ?></p>
    </div>

    <!-- Form -->
    <form id="hmdg-questionnaire-form" novalidate>

        <!-- ---- Section 1: Business Overview ---- -->
        <div class="hmdg-q-section">
            <h3 class="hmdg-q-section__title">
                <span class="hmdg-q-section__num">01</span>
                <?php esc_html_e( 'Business Overview', 'hmdg-site-planner' ); ?>
            </h3>
            <div class="row g-3">

                <div class="col-12">
                    <label class="hmdg-q-label" for="hmdg-description">
                        <?php esc_html_e( 'Project Description', 'hmdg-site-planner' ); ?>
                    </label>
                    <textarea
                        id="hmdg-description"
                        name="description"
                        class="hmdg-q-textarea"
                        rows="3"
                        placeholder="<?php esc_attr_e( 'Briefly describe your project…', 'hmdg-site-planner' ); ?>"
                    ></textarea>
                </div>

                <div class="col-md-6">
                    <label class="hmdg-q-label" for="hmdg-business-name">
                        <?php esc_html_e( 'Business / Client Name', 'hmdg-site-planner' ); ?> <span class="hmdg-req">*</span>
                    </label>
                    <input type="text" id="hmdg-business-name" name="business_name" class="hmdg-q-input" required
                        placeholder="<?php esc_attr_e( 'e.g. Acme Agency', 'hmdg-site-planner' ); ?>">
                </div>

                <div class="col-md-6">
                    <label class="hmdg-q-label" for="hmdg-business-type">
                        <?php esc_html_e( 'Business Type', 'hmdg-site-planner' ); ?> <span class="hmdg-req">*</span>
                    </label>
                    <select id="hmdg-business-type" name="business_type" class="hmdg-q-select" required>
                        <option value=""><?php esc_html_e( 'Select type…', 'hmdg-site-planner' ); ?></option>
                        <?php
                        $types = [
                            'Agency', 'E-commerce', 'Restaurant / Food', 'Medical / Healthcare',
                            'Real Estate', 'Law Firm', 'Fitness / Wellness', 'Salon / Beauty',
                            'Tech Startup', 'Education', 'Non-Profit', 'Construction',
                            'Finance / Accounting', 'Photography / Creative', 'Consulting', 'Other',
                        ];
                        foreach ( $types as $t ) :
                        ?>
                            <option value="<?php echo esc_attr( $t ); ?>"><?php echo esc_html( $t ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="hmdg-q-label" for="hmdg-location">
                        <?php esc_html_e( 'Business Location', 'hmdg-site-planner' ); ?> <span class="hmdg-req">*</span>
                    </label>
                    <input type="text" id="hmdg-location" name="location" class="hmdg-q-input" required
                        placeholder="<?php esc_attr_e( 'City, Country', 'hmdg-site-planner' ); ?>">
                </div>

                <div class="col-md-6">
                    <label class="hmdg-q-label" for="hmdg-language">
                        <?php esc_html_e( 'Site Language', 'hmdg-site-planner' ); ?>
                    </label>
                    <select id="hmdg-language" name="language" class="hmdg-q-select">
                        <?php
                        $langs = [ 'English', 'Spanish', 'French', 'German', 'Portuguese', 'Italian', 'Dutch', 'Filipino', 'Other' ];
                        foreach ( $langs as $l ) :
                        ?>
                            <option value="<?php echo esc_attr( $l ); ?>"><?php echo esc_html( $l ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
        </div>

        <!-- ---- Section 2: Services & Goals ---- -->
        <div class="hmdg-q-section">
            <h3 class="hmdg-q-section__title">
                <span class="hmdg-q-section__num">02</span>
                <?php esc_html_e( 'Services & Goals', 'hmdg-site-planner' ); ?>
            </h3>
            <div class="row g-3">

                <div class="col-12">
                    <label class="hmdg-q-label" for="hmdg-services">
                        <?php esc_html_e( 'Services / Products', 'hmdg-site-planner' ); ?> <span class="hmdg-req">*</span>
                    </label>
                    <textarea id="hmdg-services" name="services" class="hmdg-q-textarea" rows="3" required
                        placeholder="<?php esc_attr_e( 'List your main services or products…', 'hmdg-site-planner' ); ?>"></textarea>
                </div>

                <div class="col-12">
                    <label class="hmdg-q-label" for="hmdg-goals">
                        <?php esc_html_e( 'Business Goals', 'hmdg-site-planner' ); ?> <span class="hmdg-req">*</span>
                    </label>
                    <textarea id="hmdg-goals" name="goals" class="hmdg-q-textarea" rows="3" required
                        placeholder="<?php esc_attr_e( 'e.g. Generate leads, increase bookings, sell products…', 'hmdg-site-planner' ); ?>"></textarea>
                </div>

                <div class="col-12">
                    <label class="hmdg-q-label" for="hmdg-audience">
                        <?php esc_html_e( 'Target Audience', 'hmdg-site-planner' ); ?> <span class="hmdg-req">*</span>
                    </label>
                    <textarea id="hmdg-audience" name="audience" class="hmdg-q-textarea" rows="2" required
                        placeholder="<?php esc_attr_e( 'Who are your ideal customers?', 'hmdg-site-planner' ); ?>"></textarea>
                </div>

            </div>
        </div>

        <!-- ---- Section 3: Brand & Design ---- -->
        <div class="hmdg-q-section">
            <h3 class="hmdg-q-section__title">
                <span class="hmdg-q-section__num">03</span>
                <?php esc_html_e( 'Brand & Design', 'hmdg-site-planner' ); ?>
            </h3>
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="hmdg-q-label" for="hmdg-tone">
                        <?php esc_html_e( 'Tone & Voice', 'hmdg-site-planner' ); ?>
                    </label>
                    <select id="hmdg-tone" name="tone" class="hmdg-q-select">
                        <option value="Professional">Professional</option>
                        <option value="Friendly & Approachable">Friendly &amp; Approachable</option>
                        <option value="Bold & Modern">Bold &amp; Modern</option>
                        <option value="Minimalist">Minimalist</option>
                        <option value="Luxury & Premium">Luxury &amp; Premium</option>
                        <option value="Playful & Fun">Playful &amp; Fun</option>
                        <option value="Authoritative & Trustworthy">Authoritative &amp; Trustworthy</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="hmdg-q-label" for="hmdg-brand-style">
                        <?php esc_html_e( 'Brand Style', 'hmdg-site-planner' ); ?>
                    </label>
                    <select id="hmdg-brand-style" name="brand_style" class="hmdg-q-select">
                        <option value="Modern & Clean">Modern &amp; Clean</option>
                        <option value="Bold & Colorful">Bold &amp; Colorful</option>
                        <option value="Minimal & White Space">Minimal &amp; White Space</option>
                        <option value="Corporate">Corporate</option>
                        <option value="Creative & Artistic">Creative &amp; Artistic</option>
                        <option value="Dark & Edgy">Dark &amp; Edgy</option>
                        <option value="Warm & Earthy">Warm &amp; Earthy</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="hmdg-q-label">
                        <?php esc_html_e( 'Showcase / Portfolio', 'hmdg-site-planner' ); ?>
                    </label>
                    <div class="hmdg-q-toggle-group">
                        <label class="hmdg-q-toggle">
                            <input type="radio" name="showcase" value="Yes"> Yes
                        </label>
                        <label class="hmdg-q-toggle">
                            <input type="radio" name="showcase" value="No" checked> No
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <!-- ---- Section 4: Features & Integrations ---- -->
        <div class="hmdg-q-section">
            <h3 class="hmdg-q-section__title">
                <span class="hmdg-q-section__num">04</span>
                <?php esc_html_e( 'Features & Integrations', 'hmdg-site-planner' ); ?>
            </h3>
            <div class="row g-3">

                <div class="col-12">
                    <label class="hmdg-q-label"><?php esc_html_e( 'Key Features', 'hmdg-site-planner' ); ?></label>
                    <div class="hmdg-q-checkboxes">
                        <?php
                        $features = [
                            'Contact Form', 'Portfolio / Gallery', 'Blog', 'Booking System',
                            'Live Chat', 'E-commerce / Shop', 'Client Portal', 'FAQ Section',
                            'Testimonials', 'Team Page', 'Events', 'Newsletter Signup',
                        ];
                        foreach ( $features as $f ) :
                        ?>
                            <label class="hmdg-q-checkbox">
                                <input type="checkbox" name="features[]" value="<?php echo esc_attr( $f ); ?>">
                                <span><?php echo esc_html( $f ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-12">
                    <label class="hmdg-q-label"><?php esc_html_e( 'Integrations', 'hmdg-site-planner' ); ?></label>
                    <div class="hmdg-q-checkboxes">
                        <?php
                        $integrations = [
                            'CRM', 'Email Marketing', 'Payment Gateway', 'Booking Software',
                            'Google Analytics', 'Facebook Pixel', 'WhatsApp', 'Social Media',
                        ];
                        foreach ( $integrations as $i ) :
                        ?>
                            <label class="hmdg-q-checkbox">
                                <input type="checkbox" name="integrations[]" value="<?php echo esc_attr( $i ); ?>">
                                <span><?php echo esc_html( $i ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- ---- Section 5: Additional Info ---- -->
        <div class="hmdg-q-section">
            <h3 class="hmdg-q-section__title">
                <span class="hmdg-q-section__num">05</span>
                <?php esc_html_e( 'Additional Info', 'hmdg-site-planner' ); ?>
                <span class="hmdg-q-optional"><?php esc_html_e( '(optional)', 'hmdg-site-planner' ); ?></span>
            </h3>
            <div class="row g-3">

                <div class="col-12">
                    <label class="hmdg-q-label" for="hmdg-competitors">
                        <?php esc_html_e( 'Competitors', 'hmdg-site-planner' ); ?>
                    </label>
                    <textarea id="hmdg-competitors" name="competitors" class="hmdg-q-textarea" rows="2"
                        placeholder="<?php esc_attr_e( 'List competitor websites or businesses…', 'hmdg-site-planner' ); ?>"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="hmdg-q-label" for="hmdg-hosting">
                        <?php esc_html_e( 'Hosting Platform', 'hmdg-site-planner' ); ?>
                    </label>
                    <input type="text" id="hmdg-hosting" name="hosting" class="hmdg-q-input"
                        placeholder="<?php esc_attr_e( 'e.g. SiteGround, WP Engine…', 'hmdg-site-planner' ); ?>">
                </div>

                <div class="col-12">
                    <label class="hmdg-q-label" for="hmdg-requirements">
                        <?php esc_html_e( 'Additional Requirements', 'hmdg-site-planner' ); ?>
                    </label>
                    <textarea id="hmdg-requirements" name="requirements" class="hmdg-q-textarea" rows="3"
                        placeholder="<?php esc_attr_e( 'Any other details, must-haves, or special requests…', 'hmdg-site-planner' ); ?>"></textarea>
                </div>

            </div>
        </div>

        <!-- ---- Submit ---- -->
        <div class="hmdg-q-footer">
            <button type="submit" class="hmdg-btn hmdg-btn--primary hmdg-q-submit" id="hmdg-q-submit">
                <span class="hmdg-q-submit__icon" aria-hidden="true">✦</span>
                <?php esc_html_e( 'Generate Site Plan', 'hmdg-site-planner' ); ?>
            </button>
            <p class="hmdg-q-footer__note">
                <?php esc_html_e( 'Your plan will be generated using AI — this may take 15–30 seconds.', 'hmdg-site-planner' ); ?>
            </p>
        </div>

    </form>
</div><!-- .hmdg-q-wrap -->
