/**
 * HMDG Site Planner — Global JS
 * Vanilla JavaScript only (no jQuery).
 * Version: 1.0.0
 */

'use strict';

const HMDG_App = (() => {

    // -------------------------------------------------------------------------
    // Init
    // -------------------------------------------------------------------------
    function init() {
        bindDashboardEvents();
    }

    // -------------------------------------------------------------------------
    // Dashboard Events
    // -------------------------------------------------------------------------
    function bindDashboardEvents() {
        const openPlannerBtn = document.getElementById('hmdg-open-planner');
        if (openPlannerBtn) {
            openPlannerBtn.addEventListener('click', () => {
                // Placeholder — Phase 1 Site Planner will open here.
                console.log('[HMDG] Site Planner opened.');
            });
        }
    }

    // -------------------------------------------------------------------------
    // AJAX helper
    // -------------------------------------------------------------------------
    async function request(action, data = {}) {
        const body = new FormData();
        body.append('action', action);
        body.append('nonce', HMDG.nonce);

        for (const [key, val] of Object.entries(data)) {
            body.append(key, val);
        }

        const response = await fetch(HMDG.ajaxUrl, {
            method: 'POST',
            body,
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error(`[HMDG] Request failed: ${response.status}`);
        }

        return response.json();
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------
    return { init, request };

})();

document.addEventListener('DOMContentLoaded', () => HMDG_App.init());
