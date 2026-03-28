/**
 * HMDG Site Planner — Phase 1 JS
 * Multi-step: Hero → Questionnaire → Loading → Results
 * Vanilla JavaScript only.
 * Version: 1.0.0
 */

'use strict';

const HMDG_Phase1 = (() => {

    // -------------------------------------------------------------------------
    // State
    // -------------------------------------------------------------------------
    let planData = null;

    // -------------------------------------------------------------------------
    // Init
    // -------------------------------------------------------------------------
    function init() {
        bindHero();
        bindQuestionnaire();
        bindResults();
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
    // Step 1 — Hero
    // -------------------------------------------------------------------------
    function bindHero() {
        const startBtn    = document.getElementById('hmdg-start');
        const heroPrompt  = document.getElementById('hmdg-hero-prompt');

        if (!startBtn) return;

        const goToQuestionnaire = () => {
            const prompt = heroPrompt ? heroPrompt.value.trim() : '';
            // Pre-fill description field in questionnaire
            const descField = document.getElementById('hmdg-description');
            if (descField && prompt) descField.value = prompt;
            showStep('hmdg-step-questionnaire');
        };

        startBtn.addEventListener('click', goToQuestionnaire);

        if (heroPrompt) {
            heroPrompt.addEventListener('keydown', e => {
                if (e.key === 'Enter') goToQuestionnaire();
            });
        }
    }

    // -------------------------------------------------------------------------
    // Step 2 — Questionnaire
    // -------------------------------------------------------------------------
    function bindQuestionnaire() {
        const backBtn = document.getElementById('hmdg-q-back');
        const form    = document.getElementById('hmdg-questionnaire-form');

        if (backBtn) {
            backBtn.addEventListener('click', () => showStep('hmdg-step-hero'));
        }

        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!validateForm(form)) return;

            // Collect form data
            const formData = new FormData(form);
            const data = {};

            // Scalar fields
            for (const [key, value] of formData.entries()) {
                if (!key.endsWith('[]')) {
                    data[key] = value;
                }
            }

            // Array fields
            data['features']     = formData.getAll('features[]');
            data['integrations'] = formData.getAll('integrations[]');

            // Show loading state
            setSubmitLoading(true);
            await generatePlan(data);
            setSubmitLoading(false);
        });
    }

    function validateForm(form) {
        const required = form.querySelectorAll('[required]');
        let valid = true;

        required.forEach(field => {
            field.classList.remove('hmdg-q-input--error');
            if (!field.value.trim()) {
                field.classList.add('hmdg-q-input--error');
                valid = false;
            }
        });

        if (!valid) {
            const first = form.querySelector('.hmdg-q-input--error');
            if (first) first.focus();
        }

        return valid;
    }

    function setSubmitLoading(loading) {
        const btn = document.getElementById('hmdg-q-submit');
        if (!btn) return;
        btn.disabled = loading;
        btn.innerHTML = loading
            ? '<span class="hmdg-spinner" aria-hidden="true"></span> Generating…'
            : '<span class="hmdg-q-submit__icon" aria-hidden="true">✦</span> Generate Site Plan';
    }

    // -------------------------------------------------------------------------
    // AJAX — Generate Plan
    // -------------------------------------------------------------------------
    async function generatePlan(data) {
        const body = new FormData();
        body.append('action', 'hmdg_generate_site_plan');
        body.append('nonce', HMDG.nonce);

        for (const [key, val] of Object.entries(data)) {
            if (Array.isArray(val)) {
                val.forEach(v => body.append(key + '[]', v));
            } else {
                body.append(key, val);
            }
        }

        try {
            const response = await fetch(HMDG.ajaxUrl, {
                method: 'POST',
                body,
                credentials: 'same-origin',
            });

            const json = await response.json();

            if (!json.success) {
                showError(json.data?.message || 'Something went wrong. Please try again.');
                return;
            }

            planData = json.data;
            renderResults(planData);
            showStep('hmdg-step-results');

        } catch (err) {
            showError('Network error. Please check your connection and try again.');
            console.error('[HMDG Phase1]', err);
        }
    }

    function showError(message) {
        const btn = document.getElementById('hmdg-q-submit');
        let errEl = document.getElementById('hmdg-q-error');

        if (!errEl) {
            errEl = document.createElement('p');
            errEl.id        = 'hmdg-q-error';
            errEl.className = 'hmdg-q-error';
            btn?.parentElement?.insertBefore(errEl, btn);
        }

        errEl.textContent = message;
        errEl.hidden = false;
        errEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // -------------------------------------------------------------------------
    // Step 3 — Results rendering
    // -------------------------------------------------------------------------
    function bindResults() {
        // Tab switching
        document.addEventListener('click', e => {
            const tab = e.target.closest('.hmdg-results-tab');
            if (!tab) return;
            switchTab(tab.dataset.tab);
        });

        // Start over
        document.getElementById('hmdg-start-over')?.addEventListener('click', () => {
            planData = null;
            showStep('hmdg-step-hero');
        });
    }

    function switchTab(tabId) {
        document.querySelectorAll('.hmdg-results-tab').forEach(t => {
            t.classList.toggle('active', t.dataset.tab === tabId);
            t.setAttribute('aria-selected', t.dataset.tab === tabId);
        });
        document.querySelectorAll('.hmdg-results-panel').forEach(p => {
            const isActive = p.id === `hmdg-tab-${tabId}`;
            p.classList.toggle('active', isActive);
            p.hidden = !isActive;
        });
    }

    function renderResults(data) {
        const nameEl = document.getElementById('hmdg-results-business-name');
        if (nameEl && data.project_brief?.business_overview) {
            nameEl.textContent = '';
        }

        renderBrief(data.project_brief);
        renderSitemap(data.sitemap);
        renderWireframes(data.wireframes);
        renderPhase2(data.phase2_notes);
    }

    // --- Project Brief ---
    function renderBrief(brief) {
        const el = document.getElementById('hmdg-brief-content');
        if (!el || !brief) return;

        const cards = [
            { icon: '🏢', label: 'Business Overview', value: brief.business_overview },
            { icon: '🎯', label: 'Target Audience',   value: brief.target_audience },
            { icon: '💡', label: 'Unique Value Prop', value: brief.unique_value_proposition },
            { icon: '🗣️', label: 'Tone & Voice',      value: brief.tone },
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
                </div>
            </div>`;
        });

        listCards.forEach(c => {
            if (!c.items?.length) return;
            const items = c.items.map(i => `<li>${esc(i)}</li>`).join('');
            html += `<div class="hmdg-brief-card">
                <div class="hmdg-brief-card__icon">${c.icon}</div>
                <div class="hmdg-brief-card__body">
                    <h4 class="hmdg-brief-card__label">${esc(c.label)}</h4>
                    <ul class="hmdg-brief-card__list">${items}</ul>
                </div>
            </div>`;
        });

        el.innerHTML = html;
    }

    // --- Sitemap ---
    function renderSitemap(sitemap) {
        const el = document.getElementById('hmdg-sitemap-content');
        if (!el || !sitemap?.pages) return;

        const buildTree = (pages, depth = 0) => {
            if (!pages?.length) return '';
            const indent = depth === 0 ? 'hmdg-sitemap-root' : 'hmdg-sitemap-children';
            let html = `<ul class="hmdg-sitemap-tree ${indent}">`;
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
            html += '</ul>';
            return html;
        };

        el.innerHTML = `<div class="hmdg-sitemap-wrap">${buildTree(sitemap.pages)}</div>`;
    }

    // --- Wireframes ---
    function renderWireframes(wireframes) {
        const el = document.getElementById('hmdg-wireframes-content');
        if (!el || !wireframes?.length) return;

        let html = '<div class="hmdg-wf-accordion" id="hmdg-wf-accordion">';

        wireframes.forEach((page, i) => {
            const id = `hmdg-wf-${i}`;
            const isFirst = i === 0;

            const sections = (page.sections || []).map(s => `
                <div class="hmdg-wf-section">
                    <div class="hmdg-wf-section__header">
                        <span class="hmdg-wf-section__name">${esc(s.name)}</span>
                        ${s.cta ? `<span class="hmdg-wf-section__cta">CTA: ${esc(s.cta)}</span>` : ''}
                    </div>
                    ${s.key_message ? `<p class="hmdg-wf-section__msg">"${esc(s.key_message)}"</p>` : ''}
                    ${s.description ? `<p class="hmdg-wf-section__desc">${esc(s.description)}</p>` : ''}
                </div>
            `).join('');

            html += `
            <div class="hmdg-wf-item ${isFirst ? 'open' : ''}">
                <button class="hmdg-wf-toggle" type="button" aria-expanded="${isFirst}" aria-controls="${id}">
                    <span class="hmdg-wf-toggle__page">${esc(page.page)}</span>
                    <span class="hmdg-wf-toggle__slug">${esc(page.slug)}</span>
                    <span class="hmdg-wf-toggle__count">${page.sections?.length || 0} sections</span>
                    <span class="hmdg-wf-toggle__arrow" aria-hidden="true">›</span>
                </button>
                <div class="hmdg-wf-body" id="${id}" ${isFirst ? '' : 'hidden'}>
                    <div class="hmdg-wf-sections">${sections}</div>
                </div>
            </div>`;
        });

        html += '</div>';
        el.innerHTML = html;

        // Accordion logic
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

    // --- Phase 2 Notes ---
    function renderPhase2(notes) {
        const el = document.getElementById('hmdg-phase2-content');
        if (!el || !notes) return;

        const lists = [
            { label: 'Key Integrations',       items: notes.key_integrations },
            { label: 'Conversion Priorities',  items: notes.conversion_priorities },
        ];

        let html = '<div class="hmdg-phase2-grid">';

        if (notes.content_strategy) {
            html += `<div class="hmdg-phase2-card">
                <h4 class="hmdg-phase2-card__title">Content Strategy</h4>
                <p>${esc(notes.content_strategy)}</p>
            </div>`;
        }

        if (notes.seo_focus) {
            html += `<div class="hmdg-phase2-card">
                <h4 class="hmdg-phase2-card__title">SEO Focus</h4>
                <p>${esc(notes.seo_focus)}</p>
            </div>`;
        }

        lists.forEach(l => {
            if (!l.items?.length) return;
            html += `<div class="hmdg-phase2-card">
                <h4 class="hmdg-phase2-card__title">${esc(l.label)}</h4>
                <ul class="hmdg-brief-card__list">${l.items.map(i => `<li>${esc(i)}</li>`).join('')}</ul>
            </div>`;
        });

        html += `<div class="hmdg-phase2-ready">
            <span class="hmdg-phase2-ready__dot"></span>
            Automation Ready for Phase 2
        </div>`;

        html += '</div>';
        el.innerHTML = html;
    }

    // -------------------------------------------------------------------------
    // Utility
    // -------------------------------------------------------------------------
    function esc(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // -------------------------------------------------------------------------
    // Public
    // -------------------------------------------------------------------------
    return { init };

})();

document.addEventListener('DOMContentLoaded', () => HMDG_Phase1.init());
