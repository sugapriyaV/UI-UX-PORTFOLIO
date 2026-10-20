(function ($) {
  'use strict';

  function initAddressAutocomplete() {
    const $input = $('input[name="_job_address"], input#_job_address');
    if (!$input.length) return;

    // Wrap input for positioning
    $input.css('position', 'relative');
    const $wrapper = $('<div>').css({
      position: 'relative',
      display: 'inline-block',
      width: '100%'
    });
    $input.wrap($wrapper);

    // Create dropdown
    const $dropdown = $('<ul id="address-suggestions">').css({
      position:   'absolute',
      top:        '100%',
      left:       0,
      right:      0,
      zIndex:     9999,
      background: '#fff',
      border:     '1px solid #ddd',
      borderTop:  'none',
      borderRadius: '0 0 4px 4px',
      listStyle:  'none',
      margin:     0,
      padding:    0,
      maxHeight:  '220px',
      overflowY:  'auto',
      display:    'none',
      boxShadow:  '0 4px 12px rgba(0,0,0,.1)'
    });
    $input.after($dropdown);

    let debounceTimer;

    $input.on('input', function () {
      const query = $(this).val().trim();
      clearTimeout(debounceTimer);
      $dropdown.hide().empty();

      if (query.length < 3) return;

      debounceTimer = setTimeout(function () {
        $.getJSON(
          'https://api-adresse.data.gouv.fr/search/',
          { q: query, limit: 6 },
          function (data) {
            $dropdown.empty();

            if (!data.features || !data.features.length) {
              $dropdown.hide();
              return;
            }

            data.features.forEach(function (feature) {
              const label = feature.properties.label;
              const $li = $('<li>').text(label).css({
                padding:  '10px 14px',
                cursor:   'pointer',
                fontSize: '13px',
                borderBottom: '1px solid #f0f0f0'
              });

              $li.on('mouseenter', function () {
                $(this).css('background', '#f5f5f5');
              }).on('mouseleave', function () {
                $(this).css('background', '#fff');
              }).on('mousedown', function (e) {
                e.preventDefault(); // prevent blur before click
                $input.val(label);
                $dropdown.hide().empty();
              });

              $dropdown.append($li);
            });

            $dropdown.show();
          }
        );
      }, 300);
    });

    // Hide on blur
    $input.on('blur', function () {
      setTimeout(function () { $dropdown.hide(); }, 150);
    });

    // Keyboard navigation
    $input.on('keydown', function (e) {
      const $items = $dropdown.find('li');
      const $active = $items.filter('.active');

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (!$active.length) {
          $items.first().addClass('active').css('background', '#e8f0fe');
        } else {
          $active.removeClass('active').css('background', '#fff');
          const $next = $active.next();
          ($next.length ? $next : $items.first()).addClass('active').css('background', '#e8f0fe');
        }
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        $active.removeClass('active').css('background', '#fff');
        const $prev = $active.prev();
        ($prev.length ? $prev : $items.last()).addClass('active').css('background', '#e8f0fe');
      } else if (e.key === 'Enter' && $active.length) {
        e.preventDefault();
        $input.val($active.text());
        $dropdown.hide().empty();
      } else if (e.key === 'Escape') {
        $dropdown.hide();
      }
    });
  }

  $(document).ready(function () {
    initAddressAutocomplete();

    // Also init after Elementor dynamic content loads (if using Elementor forms)
    $(document).on('elementor/frontend/init', function () {
      initAddressAutocomplete();
    });
  });

}(jQuery));