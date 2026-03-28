<?php
/**
 * Admin Settings view — HMDG Site Planner.
 *
 * @package HMDG_Site_Planner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$settings = HMDG_Settings::instance()->get_all();
$models   = HMDG_Settings::models();
?>

<div class="hmdg-wrap">

    <header class="hmdg-header">
        <div class="hmdg-header__inner">
            <h1 class="hmdg-header__title">HMDG <span>Settings</span></h1>
            <p class="hmdg-header__sub">Configure your AI provider and API key.</p>
        </div>
    </header>

    <main class="hmdg-main container-fluid">
        <div class="row justify-content-start">
            <div class="col-xl-6 col-lg-8 col-12">

                <div class="hmdg-card">

                    <!-- API Provider -->
                    <div class="mb-4">
                        <label class="hmdg-settings-label" for="hmdg-provider">AI Provider</label>
                        <select id="hmdg-provider" class="hmdg-settings-select form-select">
                            <option value="claude" <?php selected( $settings['provider'], 'claude' ); ?>>Anthropic (Claude)</option>
                            <option value="openai" <?php selected( $settings['provider'], 'openai' ); ?>>OpenAI (GPT)</option>
                        </select>
                    </div>

                    <!-- Model -->
                    <div class="mb-4">
                        <label class="hmdg-settings-label" for="hmdg-model">Model</label>
                        <select id="hmdg-model" class="hmdg-settings-select form-select">
                            <?php foreach ( $models as $provider => $provider_models ) : ?>
                                <optgroup label="<?php echo esc_attr( ucfirst( $provider ) ); ?>" data-provider="<?php echo esc_attr( $provider ); ?>">
                                    <?php foreach ( $provider_models as $value => $label ) : ?>
                                        <option
                                            value="<?php echo esc_attr( $value ); ?>"
                                            data-provider="<?php echo esc_attr( $provider ); ?>"
                                            <?php selected( $settings['model'], $value ); ?>
                                        ><?php echo esc_html( $label ); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- API Key -->
                    <div class="mb-4">
                        <label class="hmdg-settings-label" for="hmdg-api-key">API Key</label>
                        <div class="hmdg-settings-key-wrap">
                            <input
                                type="password"
                                id="hmdg-api-key"
                                class="hmdg-settings-input form-control"
                                value="<?php echo esc_attr( $settings['api_key'] ); ?>"
                                placeholder="sk-ant-... or sk-..."
                                autocomplete="new-password"
                            />
                            <button type="button" class="hmdg-settings-toggle-key" id="hmdg-toggle-key" title="Show / Hide">
                                <span class="dashicons dashicons-visibility"></span>
                            </button>
                        </div>
                        <small class="hmdg-settings-hint">
                            Claude: get your key at console.anthropic.com &nbsp;|&nbsp;
                            OpenAI: platform.openai.com/api-keys
                        </small>
                    </div>

                    <!-- Max Tokens -->
                    <div class="mb-4">
                        <label class="hmdg-settings-label" for="hmdg-max-tokens">Max Tokens</label>
                        <input
                            type="number"
                            id="hmdg-max-tokens"
                            class="hmdg-settings-input form-control"
                            value="<?php echo esc_attr( $settings['max_tokens'] ); ?>"
                            min="1000"
                            max="8192"
                            step="256"
                        />
                        <small class="hmdg-settings-hint">Recommended: 4096. Higher = more detail, slower response.</small>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <button type="button" class="hmdg-btn hmdg-btn--primary" id="hmdg-save-settings">
                            Save Settings
                        </button>
                        <button type="button" class="hmdg-btn hmdg-btn--secondary" id="hmdg-test-api">
                            Test API Connection
                        </button>
                        <span id="hmdg-settings-msg" class="hmdg-settings-msg"></span>
                    </div>

                </div><!-- .hmdg-card -->

            </div>
        </div>
    </main>

</div><!-- .hmdg-wrap -->

<script>
(function () {
    'use strict';

    const adminNonce = '<?php echo esc_js( wp_create_nonce( 'hmdg_admin_nonce' ) ); ?>';

    // Show/hide key
    document.getElementById('hmdg-toggle-key').addEventListener('click', function () {
        const input = document.getElementById('hmdg-api-key');
        input.type = input.type === 'password' ? 'text' : 'password';
    });

    // Filter model options by provider
    const providerSelect = document.getElementById('hmdg-provider');
    const modelSelect    = document.getElementById('hmdg-model');

    function filterModels() {
        const provider = providerSelect.value;
        Array.from( modelSelect.options ).forEach( opt => {
            opt.hidden = opt.dataset.provider && opt.dataset.provider !== provider;
        });
    }

    providerSelect.addEventListener('change', filterModels);
    filterModels();

    // Save settings
    document.getElementById('hmdg-save-settings').addEventListener('click', function () {
        const msg = document.getElementById('hmdg-settings-msg');
        msg.textContent = 'Saving…';
        msg.className   = 'hmdg-settings-msg';

        const body = new FormData();
        body.append('action',     'hmdg_save_settings');
        body.append('nonce',      adminNonce);
        body.append('provider',   providerSelect.value);
        body.append('api_key',    document.getElementById('hmdg-api-key').value);
        body.append('model',      modelSelect.value);
        body.append('max_tokens', document.getElementById('hmdg-max-tokens').value);

        fetch('<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', {
            method: 'POST', body, credentials: 'same-origin'
        })
        .then( r => r.json() )
        .then( data => {
            msg.textContent = data.data?.message ?? ( data.success ? 'Saved.' : 'Error.' );
            msg.className   = 'hmdg-settings-msg ' + ( data.success ? 'is-success' : 'is-error' );
        })
        .catch( () => {
            msg.textContent = 'Network error.';
            msg.className   = 'hmdg-settings-msg is-error';
        });
    });

    // Test API
    document.getElementById('hmdg-test-api').addEventListener('click', function () {
        const msg = document.getElementById('hmdg-settings-msg');
        msg.textContent = 'Testing connection…';
        msg.className   = 'hmdg-settings-msg';

        const body = new FormData();
        body.append('action', 'hmdg_test_api');
        body.append('nonce',  adminNonce);

        fetch('<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', {
            method: 'POST', body, credentials: 'same-origin'
        })
        .then( r => r.json() )
        .then( data => {
            msg.textContent = data.data?.message ?? ( data.success ? 'Connected!' : 'Failed.' );
            msg.className   = 'hmdg-settings-msg ' + ( data.success ? 'is-success' : 'is-error' );
        })
        .catch( () => {
            msg.textContent = 'Network error.';
            msg.className   = 'hmdg-settings-msg is-error';
        });
    });
})();
</script>
