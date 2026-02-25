(function ($) {
  'use strict';

  function getSliderWrappers($root) {
    var selectors = [
      '.mphb_sc_rooms-wrapper.slider .rooms-wrapper',
      '.mphb_sc_rooms-wrapper.slide .rooms-wrapper',
      '.slider .mphb_sc_rooms-wrapper .rooms-wrapper',
      '.slide .mphb_sc_rooms-wrapper .rooms-wrapper',
      '.mphb_widget_rooms-wrapper.slider',
      '.mphb_widget_rooms-wrapper.slide',
      '.slider .mphb_widget_rooms-wrapper',
      '.slide .mphb_widget_rooms-wrapper'
    ];

    var $wrappers = $();
    selectors.forEach(function (selector) {
      $wrappers = $wrappers.add($root.find(selector));
    });

    return $wrappers.filter(function () {
      return $(this).children('.type-mphb_room_type').length > 1;
    });
  }

  function initRoomsSliders($context) {
    var $root = $context && $context.length ? $context : $(document);
    var $wrappers = getSliderWrappers($root);

    if (!$wrappers.length) {
      return;
    }

    $wrappers.each(function () {
      var $wrapper = $(this);

      var $shortcodeWrapper = $wrapper.closest('.mphb_sc_rooms-wrapper');
      if (
        $shortcodeWrapper.length &&
        !$shortcodeWrapper.hasClass('slider') &&
        !$shortcodeWrapper.hasClass('slide') &&
        $shortcodeWrapper.closest('.slider, .slide').length
      ) {
        // Normalize class placement so existing slider CSS hooks apply reliably.
        $shortcodeWrapper.addClass('slider');
      }

      if ($wrapper.hasClass('slick-initialized')) {
        return;
      }

      $wrapper.slick({
        infinite: false,
        slidesToShow: 3,
        fade: false,
        speed: 1000,
        autoplay: false,
        dots: false,
        arrows: false,
        rows: 0,
        adaptiveHeight: false,
        swipeToSlide: true,
        responsive: [
          {
            breakpoint: 991,
            settings: {
              slidesToShow: 2
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1
            }
          }
        ]
      });
    });
  }

  function normalizeSearchPlaceholders($context) {
    var $root = $context && $context.length ? $context : $(document);
    $root.find('.home-search-form .mphb-datepick').each(function () {
      var $input = $(this);
      var placeholder = $input.attr('placeholder');

      if (!placeholder) {
        return;
      }

      $input.attr('placeholder', placeholder.replace(/\s*Date\b/i, '').trim());
    });
  }

  function handleSearchResultsLoading() {
    var $body = $('body');
    if (!$body.hasClass('book-inn-search-loading')) {
      return;
    }

    var done = function () {
      $body.removeClass('book-inn-search-loading');
    };

    if (document.readyState === 'complete') {
      done();
      return;
    }

    $(window).on('load', done);
  }

  $(function () {
    initRoomsSliders($(document));
    normalizeSearchPlaceholders($(document));
    handleSearchResultsLoading();
  });

  $(window).on('elementor/frontend/init', function () {
    if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
      return;
    }

    elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
      initRoomsSliders($scope);
      normalizeSearchPlaceholders($scope);
    });
  });
})(jQuery);
