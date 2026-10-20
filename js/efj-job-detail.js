(function () {

    // /* ══ APPLY BUTTON ══════════════════════════════════════ */
    // var btn = document.getElementById('efj-apply-btn');
    // var msg = document.getElementById('efj-apply-msg');

    // if (btn && typeof window.EFJ_Apply !== 'undefined') {
    //     var cfg = window.EFJ_Apply;

    //     btn.addEventListener('click', function () {
    //         if (btn.disabled) return;

    //         btn.disabled = true;
    //         btn.classList.add('efj-apply-btn--loading');
    //         btn.textContent = cfg.i18n.applying;
    //         msg.textContent = '';

    //         var body = new URLSearchParams({
    //             action : 'efj_apply_job',
    //             nonce  : cfg.nonce,
    //             job_id : cfg.jobId,
    //         });

    //         fetch(cfg.ajaxUrl, {
    //             method  : 'POST',
    //             headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
    //             body    : body.toString(),
    //         })
    //         .then(function (r) { return r.json(); })
    //         .then(function (data) {
    //             btn.classList.remove('efj-apply-btn--loading');
    //             if (data.success) {
    //                 btn.classList.add('efj-apply-btn--done');
    //                 btn.textContent = cfg.i18n.applied;
    //                 msg.textContent = data.data.message;
    //             } else {
    //                 btn.disabled = false;
    //                 btn.classList.add('efj-apply-btn--error');
    //                 btn.textContent = cfg.i18n.error;
    //                 msg.textContent = data.data ? data.data.message : '';
    //                 setTimeout(function () {
    //                     btn.classList.remove('efj-apply-btn--error');
    //                     btn.textContent = cfg.i18n.apply;
    //                 }, 3000);
    //             }
    //         })
    //         .catch(function () {
    //             btn.disabled = false;
    //             btn.classList.remove('efj-apply-btn--loading');
    //             btn.classList.add('efj-apply-btn--error');
    //             btn.textContent = cfg.i18n.error;
    //             setTimeout(function () {
    //                 btn.classList.remove('efj-apply-btn--error');
    //                 btn.textContent = cfg.i18n.apply;
    //             }, 3000);
    //         });
    //     });
    // }

    /* ══ MATCH RING ANIMATION ══════════════════════════════ */
    var banner  = document.getElementById('efj-match-banner');
    var ring    = document.getElementById('efj-ring');
    var counter = document.getElementById('efj-pct-counter');

    if (banner && ring && counter && typeof window.EFJ_Match !== 'undefined') {
        var target   = window.EFJ_Match.pct;
        var offset   = parseFloat(ring.getAttribute('data-target-offset') || 0);
        var animated = false;

        function animate() {
            if (animated) return;
            animated = true;
            ring.style.strokeDashoffset = offset;

            var start    = null;
            var duration = 1400;

            function step(ts) {
                if (!start) start = ts;
                var progress = Math.min((ts - start) / duration, 1);
                var ease = 1 - Math.pow(1 - progress, 3);
                counter.textContent = Math.round(ease * target) + '%';
                if (progress < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }

        if ('IntersectionObserver' in window) {
            var obs = new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting) {
                    animate();
                    obs.disconnect();
                }
            }, { threshold: 0.3 });
            obs.observe(banner);
        } else {
            animate();
        }
    }

}());