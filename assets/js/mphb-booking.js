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

  function getGuestBounds() {
    var config = window.bookInnMphbConfig || {};
    var minAdults = parseInt(config.minAdults, 10);
    var maxAdults = parseInt(config.maxAdults, 10);
    var minChildren = parseInt(config.minChildren, 10);

    if (!Number.isFinite(minAdults) || minAdults < 1) {
      minAdults = 1;
    }

    if (!Number.isFinite(maxAdults) || maxAdults < minAdults) {
      maxAdults = minAdults;
    }

    if (!Number.isFinite(minChildren) || minChildren < 0) {
      minChildren = 0;
    }

    return {
      minAdults: minAdults,
      maxAdults: maxAdults,
      minChildren: minChildren
    };
  }

  function parseGuestCountFromText(text) {
    var value = String(text || '');
    var match = value.match(/(\d+)/);
    if (!match) {
      return null;
    }

    return parseInt(match[1], 10);
  }

  function clampGuests(value, bounds) {
    var guestCount = parseInt(value, 10);
    if (!Number.isFinite(guestCount)) {
      guestCount = bounds.minAdults;
    }

    return Math.max(bounds.minAdults, Math.min(bounds.maxAdults, guestCount));
  }

  function syncOccupancyFromGuests($adults, $children, guestsValue, bounds) {
    var guestCount = clampGuests(guestsValue, bounds);
    $adults.val(String(guestCount));
    $children.val(String(bounds.minChildren));
  }

  function initSearchFormGuests($context) {
    var $root = $context && $context.length ? $context : $(document);
    var bounds = getGuestBounds();

    $root.find('form.mphb_sc_search-form, form.mphb_widget_search-form').each(function () {
      var $form = $(this);
      var $adults = $form.find('input[type="hidden"][name="mphb_adults"]').first();
      var $children = $form.find('input[type="hidden"][name="mphb_children"]').first();

      if (!$adults.length || !$children.length) {
        return;
      }

      var $guestAttributeSelect = $form.find('select[name="mphb_attributes[guest]"], select[name="mphb_attributes[guests]"]').first();

      if ($guestAttributeSelect.length) {
        var applyGuestAttributeSelection = function () {
          var selectedText = $guestAttributeSelect.find('option:selected').text();
          var fromLabel = parseGuestCountFromText(selectedText);

          if (fromLabel === null) {
            return;
          }

          syncOccupancyFromGuests($adults, $children, fromLabel, bounds);
        };

        applyGuestAttributeSelection();
        $guestAttributeSelect.off('change.bookInnGuestAttribute').on('change.bookInnGuestAttribute', applyGuestAttributeSelection);
        return;
      }

      var guestsId = $form.attr('id') ? $form.attr('id') + '-guests' : 'mphb_guests_' + Math.random().toString(36).slice(2);
      var $wrapper = $form.find('.book-inn-guests-wrapper');
      var $select = $wrapper.find('select.book-inn-guests-select');

      if (!$wrapper.length) {
        $wrapper = $('<p class="book-inn-guests-wrapper mphb-guests-wrapper"></p>');
        $wrapper.append('<label for="' + guestsId + '">Guests</label><br />');
        $select = $('<select class="book-inn-guests-select" id="' + guestsId + '" aria-label="Guests"></select>');

        for (var guestValue = bounds.minAdults; guestValue <= bounds.maxAdults; guestValue += 1) {
          $select.append($('<option></option>').attr('value', String(guestValue)).text(String(guestValue)));
        }

        $wrapper.append($select);

        var $insertBefore = $form.find('.mphb_sc_search-submit-button-wrapper, .mphb_widget_search-submit-button-wrapper').first();
        if ($insertBefore.length) {
          $wrapper.insertBefore($insertBefore);
        } else {
          $form.append($wrapper);
        }
      }

      var currentAdults = clampGuests($adults.val(), bounds);
      $select.val(String(currentAdults));
      syncOccupancyFromGuests($adults, $children, currentAdults, bounds);

      $select.off('change.bookInnGuests').on('change.bookInnGuests', function () {
        syncOccupancyFromGuests($adults, $children, $(this).val(), bounds);
      });
    });
  }

  function initBookingFormGuests($context) {
    var $root = $context && $context.length ? $context : $(document);
    var bounds = getGuestBounds();

    $root.find('form.mphb-booking-form').each(function () {
      var $form = $(this);
      var $adults = $form.find('input[type="hidden"][name="mphb_adults"]').first();
      var $children = $form.find('input[type="hidden"][name="mphb_children"]').first();

      if (!$adults.length || !$children.length) {
        return;
      }

      var guestsId = $form.attr('id') ? $form.attr('id') + '-guests' : 'mphb_guests_' + Math.random().toString(36).slice(2);
      var $wrapper = $form.find('.book-inn-guests-wrapper');
      var $select = $wrapper.find('select.book-inn-guests-select');

      if (!$wrapper.length) {
        $wrapper = $('<p class="book-inn-guests-wrapper mphb-guests-wrapper mphb-capacity-wrapper"></p>');
        $wrapper.append('<label for="' + guestsId + '">Guests</label><br />');
        $select = $('<select class="book-inn-guests-select" id="' + guestsId + '" aria-label="Guests"></select>');

        for (var guestValue = bounds.minAdults; guestValue <= bounds.maxAdults; guestValue += 1) {
          $select.append($('<option></option>').attr('value', String(guestValue)).text(String(guestValue)));
        }

        $wrapper.append($select);

        var $insertBefore = $form.find('.mphb-reserve-btn-wrapper').first();
        if ($insertBefore.length) {
          $wrapper.insertBefore($insertBefore);
        } else {
          $form.append($wrapper);
        }
      }

      var currentAdults = clampGuests($adults.val(), bounds);

      $select.val(String(currentAdults));
      syncOccupancyFromGuests($adults, $children, currentAdults, bounds);

      $select.off('change.bookInnGuests').on('change.bookInnGuests', function () {
        syncOccupancyFromGuests($adults, $children, $(this).val(), bounds);
      });
    });
  }

  $(function () {
    initRoomsSliders($(document));
    normalizeSearchPlaceholders($(document));
    handleSearchResultsLoading();
    initSearchFormGuests($(document));
    initBookingFormGuests($(document));
  });

  $(window).on('elementor/frontend/init', function () {
    if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
      return;
    }

    elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
      initRoomsSliders($scope);
      normalizeSearchPlaceholders($scope);
      initSearchFormGuests($scope);
      initBookingFormGuests($scope);
    });
  });
})(jQuery);
