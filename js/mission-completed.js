/**
 * Mark Mission as Completed — JS
 * File: wp-content/themes/superio/js/mission-completed.js
 */
jQuery(document).ready(function ($) {

    $(document).on('click', '.mark_completed', function (e) {
        e.preventDefault();

        var $btn   = $(this);
        var $row   = $btn.closest('tr');
        var job_id = $btn.data('job_id');
        var nonce  = $btn.data('nonce');

        if ( ! confirm(superioCompleted.confirmComplete) ) return;

        $btn.css({ 'pointer-events': 'none', 'opacity': '0.5' });

        $.ajax({
            url  : superioCompleted.ajaxUrl,
            type : 'POST',
            data : { action: 'superio_mark_job_completed', job_id: job_id, nonce: nonce },
            success: function (res) {
                if ( res.success ) {

                    // Update the Statut column label
                    $row.find('.job-table-actions-inner')
                        .removeClass('publish filled')
                        .addClass('completed')
                        .text(res.data.label);

                    // Hide the complete button — all other buttons stay
                    $btn.hide();

                } else {
                    alert(res.data.message || superioCompleted.error);
                    $btn.css({ 'pointer-events': '', 'opacity': '' });
                }
            },
            error: function () {
                alert(superioCompleted.ajaxError);
                $btn.css({ 'pointer-events': '', 'opacity': '' });
            }
        });
    });

});