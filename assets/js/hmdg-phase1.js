/**
 * HMDG Site Planner — Phase 1 JS
 *
 * Flow:
 *   1. Hero
 *   2. Questionnaire
 *   3. Results + Client Approval (with Revision / Regenerate)
 *   4. Send to Project Manager
 *   5. Confirmation
 *
 * Vanilla JavaScript only. No jQuery.
 * Version: 1.0.0
 */

'use strict';

const HMDG_Phase1 = (() => {

    // -------------------------------------------------------------------------
    // State
    // -------------------------------------------------------------------------
    let planData       = null;   // Current generated plan
    let lastFormData   = null;   // Last submitted questionnaire data
    let lastClientEmail = '';    // Client email from questionnaire

    // -------------------------------------------------------------------------
    // Init
    // -------------------------------------------------------------------------
    function init() {
        bindHero();
        bindQuestionnaire();
        bindResults();
        bindSend();
        bindConfirmation();
    }

    // -------------------------------------------------------------------------
    // Step navigation
    // -------------------------------------------------------------------------
    function showStep(id) {
        document.querySelectorAll('.hmdg-step').forEach(el => {
            el.hidden = (el.id !== id);
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // -------------------------------------------------------------------------
    // STEP 1 — Hero
    // -------------------------------------------------------------------------
    function bindHero() {
        const startBtn   = document.getElementById('hmdg-start');
        const heroPrompt = document.getElementById('hmdg-hero-prompt');

        if (!startBtn) return;

        const goToQuestionnaire = () => {
            const prompt = heroPrompt?.value.trim() || '';
            const descField = document.getElementById('hmdg-description');
            if (descField && prompt) descField.value = prompt;
            showStep('hmdg-step-questionnaire');
        };

        startBtn.addEventListener('click', goToQuestionnaire);
        heroPrompt?.addEventListener('keydown', e => {
            if (e.key === 'Enter') goToQuestionnaire();
        });
    }

    // -------------------------------------------------------------------------
    // STEP 2 — Questionnaire
    // -------------------------------------------------------------------------
    function bindQuestionnaire() {
        const backBtn = document.getElementById('hmdg-q-back');
        const form    = document.getElementById('hmdg-questionnaire-form');

        backBtn?.addEventListener('click', () => showStep('hmdg-step-hero'));

        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            clearError('hmdg-q-error');
            if (!validateForm(form)) return;

            // Collect data
            const formData  = new FormData(form);
            const data      = {};
            for (const [key, value] of formData.entries()) {
                if (!key.endsWith('[]')) data[key] = value;
            }
            data['features']     = formData.getAll('features[]');
            data['integrations'] = formData.getAll('integrations[]');

            lastFormData    = data;
            lastClientEmail = data['client_email'] || '';

            setSubmitLoading(true);
            await generatePlan(data);
            setSubmitLoading(false);
        });
    }

    function validateForm(form) {
        let valid = true;
        form.querySelectorAll('[required]').forEach(field => {
            field.classList.remove('hmdg-q-input--error');
            if (!field.value.trim()) {
                field.classList.add('hmdg-q-input--error');
                valid = false;
            }
        });
        if (!valid) form.querySelector('.hmdg-q-input--error')?.focus();
        return valid;
    }

    function setSubmitLoading(on) {
        const btn = document.getElementById('hmdg-q-submit');
        if (!btn) return;
        btn.disabled  = on;
        btn.innerHTML = on
            ? '<span class="hmdg-spinner" aria-hidden="true"></span> Generating your plan…'
            : '<span class="hmdg-q-submit__icon" aria-hidden="true">✦</span> Generate Site Plan';
    }

    // -------------------------------------------------------------------------
    // STEP 3 — Results + Approval
    // -------------------------------------------------------------------------
    function bindResults() {
        // Tab switching
        document.addEventListener('click', e => {
            const tab = e.target.closest('.hmdg-results-tab');
            if (tab) switchTab(tab.dataset.tab);
        });

        // Start over
        document.getElementById('hmdg-start-over')?.addEventListener('click', resetApp);

        // Approve plan → go to send step
        document.getElementById('hmdg-approve-plan')?.addEventListener('click', () => {
            prefillSendStep();
            showStep('hmdg-step-send');
        });

        // Request changes → reveal revision panel
        document.getElementById('hmdg-request-changes')?.addEventListener('click', () => {
            const panel = document.getElementById('hmdg-revision-panel');
            if (panel) {
                panel.hidden = false;
                panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                document.getElementById('hmdg-revision-notes')?.focus();
            }
        });

        // Cancel revision
        document.getElementById('hmdg-cancel-revision')?.addEventListener('click', () => {
            const panel = document.getElementById('hmdg-revision-panel');
            if (panel) panel.hidden = true;
        });

        // Regenerate
        document.getElementById('hmdg-regenerate')?.addEventListener('click', async () => {
            const feedback = document.getElementById('hmdg-revision-notes')?.value.trim();
            if (!feedback) {
                document.getElementById('hmdg-revision-notes')?.focus();
                return;
            }

            const btn = document.getElementById('hmdg-regenerate');
            btn.disabled  = true;
            btn.innerHTML = '<span class="hmdg-spinner" aria-hidden="true"></span> Regenerating…';

            await regeneratePlan(feedback);

            btn.disabled  = false;
            btn.innerHTML = '<span aria-hidden="true">✦</span> Regenerate Plan';
        });
    }

    function switchTab(tabId) {
        document.querySelectorAll('.hmdg-results-tab').forEach(t => {
            t.classList.toggle('active', t.dataset.tab === tabId);
            t.setAttribute('aria-selected', t.dataset.tab === tabId);
        });
        document.querySelectorAll('.hmdg-results-panel').forEach(p => {
            const active = p.id === `hmdg-tab-${tabId}`;
            p.classList.toggle('active', active);
            p.hidden = !active;
        });
    }

    // -------------------------------------------------------------------------
    // STEP 4 — Send to PM
    // -------------------------------------------------------------------------
    function bindSend() {
        document.getElementById('hmdg-send-back')?.addEventListener('click', () => {
            showStep('hmdg-step-results');
        });

        const form = document.getElementById('hmdg-send-form');
        if (!form) return;

        // Watch email input to update CC display
        const emailInput = document.getElementById('hmdg-send-client-email');
        emailInput?.addEventListener('input', () => {
            const recipient = document.getElementById('hmdg-client-recipient');
            const display   = document.getElementById('hmdg-client-email-display');
            if (!recipient || !display) return;
            const val = emailInput.value.trim();
            recipient.hidden     = !val;
            display.textContent  = val;
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearError('hmdg-send-error');

            const btn      = document.getElementById('hmdg-send-submit');
            const email    = emailInput?.value.trim() || '';
            const notes    = document.getElementById('hmdg-send-pm-notes')?.value.trim() || '';

            btn.disabled  = true;
            btn.innerHTML = '<span class="hmdg-spinner" aria-hidden="true"></span> Sending…';

            await sendToPM(email, notes);

            btn.disabled  = false;
            btn.innerHTML = '<span aria-hidden="true">✉</span> Send to HMDG to proceed to website development';
        });
    }

    function prefillSendStep() {
        const emailInput = document.getElementById('hmdg-send-client-email');
        if (emailInput && lastClientEmail) {
            emailInput.value = lastClientEmail;
            // Trigger display update
            const recipient = document.getElementById('hmdg-client-recipient');
            const display   = document.getElementById('hmdg-client-email-display');
            if (recipient && display && lastClientEmail) {
                recipient.hidden    = false;
                display.textContent = lastClientEmail;
            }
        }
    }

    // -------------------------------------------------------------------------
    // STEP 5 — Confirmation
    // -------------------------------------------------------------------------
    function bindConfirmation() {
        document.getElementById('hmdg-new-plan')?.addEventListener('click', resetApp);
    }

    // -------------------------------------------------------------------------
    // AJAX — Generate Plan
    // -------------------------------------------------------------------------
    async function generatePlan(data) {
        const body = buildFormBody(data, { action: 'hmdg_generate_site_plan' });

        try {
            const json = await postAjax(body);
            if (!json.success) { showError('hmdg-q-error', json.data?.message || 'Something went wrong. Please try again.'); return; }
            planData = json.data;
            renderResults(planData);
            showStep('hmdg-step-results');
            // Reset revision panel
            const panel = document.getElementById('hmdg-revision-panel');
            if (panel) { panel.hidden = true; }
            const notes = document.getElementById('hmdg-revision-notes');
            if (notes) notes.value = '';
        } catch (err) {
            showError('hmdg-q-error', 'Network error. Please check your connection and try again.');
            console.error('[HMDG Phase1]', err);
        }
    }

    // -------------------------------------------------------------------------
    // AJAX — Regenerate Plan
    // -------------------------------------------------------------------------
    async function regeneratePlan(feedback) {
        const body = buildFormBody(lastFormData, {
            action:        'hmdg_regenerate_site_plan',
            feedback:      feedback,
            previous_plan: JSON.stringify(planData),
        });

        try {
            const json = await postAjax(body);
            if (!json.success) { showError('hmdg-q-error', json.data?.message || 'Regeneration failed. Please try again.'); return; }
            planData = json.data;
            renderResults(planData);
            // Hide revision panel + scroll to top of results
            const panel = document.getElementById('hmdg-revision-panel');
            if (panel) panel.hidden = true;
            document.getElementById('hmdg-results-wrap')?.scrollIntoView({ behavior: 'smooth' });
        } catch (err) {
            showError('hmdg-q-error', 'Network error.');
            console.error('[HMDG Phase1]', err);
        }
    }

    // -------------------------------------------------------------------------
    // AJAX — Send to PM
    // -------------------------------------------------------------------------
    async function sendToPM(clientEmail, pmNotes) {
        const body = new FormData();
        body.append('action',       'hmdg_send_to_pm');
        body.append('nonce',        HMDG.nonce);
        body.append('client_email', clientEmail);
        body.append('pm_notes',     pmNotes);
        body.append('plan',         JSON.stringify(planData));

        try {
            const json = await postAjax(body);
            if (!json.success) { showError('hmdg-send-error', json.data?.message || 'Failed to send. Please try again.'); return; }
            showStep('hmdg-step-confirmation');
        } catch (err) {
            showError('hmdg-send-error', 'Network error. Please try again.');
            console.error('[HMDG Phase1]', err);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------
    function buildFormBody(data, extra = {}) {
        const body = new FormData();
        body.append('nonce', HMDG.nonce);
        for (const [key, val] of Object.entries({ ...data, ...extra })) {
            if (Array.isArray(val)) {
                val.forEach(v => body.append(key + '[]', v));
            } else {
                body.append(key, val ?? '');
            }
        }
        return body;
    }

    async function postAjax(body) {
        const response = await fetch(HMDG.ajaxUrl, {
            method: 'POST', body, credentials: 'same-origin',
        });
        return response.json();
    }

    function resetApp() {
        planData = lastFormData = null;
        lastClientEmail = '';
        document.getElementById('hmdg-hero-prompt') && (document.getElementById('hmdg-hero-prompt').value = '');
        document.getElementById('hmdg-questionnaire-form')?.reset();
        showStep('hmdg-step-hero');
    }

    function showError(id, message) {
        const el = document.getElementById(id);
        if (!el) return;
        el.textContent = message;
        el.hidden = false;
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearError(id) {
        const el = document.getElementById(id);
        if (el) { el.textContent = ''; el.hidden = true; }
    }

    // -------------------------------------------------------------------------
    // Results rendering
    // -------------------------------------------------------------------------
    function renderResults(data) {
        switchTab('brief');
        renderBrief(data.project_brief);
        renderSitemap(data.sitemap);
        renderWireframes(data.wireframes);
        renderPhase2(data.phase2_notes);
    }

    function renderBrief(brief) {
        const el = document.getElementById('hmdg-brief-content');
        if (!el || !brief) return;

        const cards = [
            { icon: '🏢', label: 'Business Overview', value: brief.business_overview },
            { icon: '🎯', label: 'Target Audience',   value: brief.target_audience },
            { icon: '💡', label: 'Unique Value Prop',  value: brief.unique_value_proposition },
            { icon: '🗣️', label: 'Tone & Voice',       value: brief.tone },
        ];
        const listCards = [
            { icon: '✅', label: 'Goals',             items: brief.goals },
            { icon: '⚙️', label: 'Features',          items: brief.features },
            { icon: '📄', label: 'Recommended Pages', items: brief.recommended_pages },
        ];

        let html = '';
        cards.forEach(c => {
            if (!c.value) return;
            html += `<div class="hmdg-brief-card">
                <div class="hmdg-brief-card__icon">${c.icon}</div>
                <div class="hmdg-brief-card__body">
                    <h4 class="hmdg-brief-card__label">${esc(c.label)}</h4>
                    <p class="hmdg-brief-card__value">${esc(c.value)}</p>
                </div></div>`;
        });
        listCards.forEach(c => {
            if (!c.items?.length) return;
            html += `<div class="hmdg-brief-card">
                <div class="hmdg-brief-card__icon">${c.icon}</div>
                <div class="hmdg-brief-card__body">
                    <h4 class="hmdg-brief-card__label">${esc(c.label)}</h4>
                    <ul class="hmdg-brief-card__list">${c.items.map(i => `<li>${esc(i)}</li>`).join('')}</ul>
                </div></div>`;
        });
        el.innerHTML = html;
    }

    function renderSitemap(sitemap) {
        const el = document.getElementById('hmdg-sitemap-content');
        if (!el || !sitemap?.pages) return;

        const buildTree = (pages, depth = 0) => {
            if (!pages?.length) return '';
            let html = `<ul class="hmdg-sitemap-tree ${depth === 0 ? 'hmdg-sitemap-root' : 'hmdg-sitemap-children'}">`;
            pages.forEach(page => {
                html += `<li class="hmdg-sitemap-node">
                    <div class="hmdg-sitemap-node__inner">
                        <span class="hmdg-sitemap-node__name">${esc(page.name)}</span>
                        <span class="hmdg-sitemap-node__slug">${esc(page.slug)}</span>
                        ${page.purpose ? `<span class="hmdg-sitemap-node__purpose">${esc(page.purpose)}</span>` : ''}
                    </div>
                    ${page.children?.length ? buildTree(page.children, depth + 1) : ''}
                </li>`;
            });
            return html + '</ul>';
        };

        el.innerHTML = `<div class="hmdg-sitemap-wrap">${buildTree(sitemap.pages)}</div>`;
    }

    function renderWireframes(wireframes) {
        const el = document.getElementById('hmdg-wireframes-content');
        if (!el || !wireframes?.length) return;

        let html = '<div class="hmdg-wf-accordion">';
        wireframes.forEach((page, i) => {
            const id      = `hmdg-wf-${i}`;
            const isFirst = i === 0;
            const sections = (page.sections || []).map(s => `
                <div class="hmdg-wf-section">
                    <div class="hmdg-wf-section__header">
                        <span class="hmdg-wf-section__name">${esc(s.name)}</span>
                        ${s.cta ? `<span class="hmdg-wf-section__cta">CTA: ${esc(s.cta)}</span>` : ''}
                    </div>
                    ${s.key_message ? `<p class="hmdg-wf-section__msg">"${esc(s.key_message)}"</p>` : ''}
                    ${s.description ? `<p class="hmdg-wf-section__desc">${esc(s.description)}</p>` : ''}
                </div>`).join('');

            html += `<div class="hmdg-wf-item ${isFirst ? 'open' : ''}">
                <button class="hmdg-wf-toggle" type="button" aria-expanded="${isFirst}" aria-controls="${id}">
                    <span class="hmdg-wf-toggle__page">${esc(page.page)}</span>
                    <span class="hmdg-wf-toggle__slug">${esc(page.slug)}</span>
                    <span class="hmdg-wf-toggle__count">${page.sections?.length || 0} sections</span>
                    <span class="hmdg-wf-toggle__arrow" aria-hidden="true">›</span>
                </button>
                <div class="hmdg-wf-body" id="${id}" ${isFirst ? '' : 'hidden'}>
                    <div class="hmdg-wf-sections">${sections}</div>
                </div></div>`;
        });
        html += '</div>';
        el.innerHTML = html;

        el.querySelectorAll('.hmdg-wf-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const item = btn.closest('.hmdg-wf-item');
                const body = item.querySelector('.hmdg-wf-body');
                const open = item.classList.toggle('open');
                body.hidden = !open;
                btn.setAttribute('aria-expanded', open);
            });
        });
    }

    function renderPhase2(notes) {
        const el = document.getElementById('hmdg-phase2-content');
        if (!el || !notes) return;

        let html = '<div class="hmdg-phase2-grid">';

        if (notes.content_strategy) {
            html += `<div class="hmdg-phase2-card"><h4 class="hmdg-phase2-card__title">Content Strategy</h4><p>${esc(notes.content_strategy)}</p></div>`;
        }
        if (notes.seo_focus) {
            html += `<div class="hmdg-phase2-card"><h4 class="hmdg-phase2-card__title">SEO Focus</h4><p>${esc(notes.seo_focus)}</p></div>`;
        }
        [
            { label: 'Key Integrations',      items: notes.key_integrations },
            { label: 'Conversion Priorities', items: notes.conversion_priorities },
        ].forEach(l => {
            if (!l.items?.length) return;
            html += `<div class="hmdg-phase2-card"><h4 class="hmdg-phase2-card__title">${esc(l.label)}</h4>
                <ul class="hmdg-brief-card__list">${l.items.map(i => `<li>${esc(i)}</li>`).join('')}</ul></div>`;
        });

        html += `<div class="hmdg-phase2-ready"><span class="hmdg-phase2-ready__dot"></span>Automation Ready for Phase 2</div>`;
        html += '</div>';
        el.innerHTML = html;
    }

    function esc(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    return { init };

})();

document.addEventListener('DOMContentLoaded', () => HMDG_Phase1.init());
