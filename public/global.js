/**
 * Allow toggling display of password fields on register/change pass.
 */
(function ($) {
    $.toggleShowPassword = function (options) {
        var settings = $.extend({
            field: "#password",
            control: "#toggle_show_password",
        }, options);

        var control = $(settings.control);
        var field = $(settings.field)

        control.bind('click', function () {
            nextIcon = control.next().children('i');
            if (control.is(':checked')) {
                nextIcon.text('visibility_off');
                field.attr('type', 'text');
            } else {
                nextIcon.text('visibility');
                field.attr('type', 'password');
            }
        })
    };
}(jQuery));


/**
 * Inline SVG so we can use CSS on it.
 */
$(document).ready(function() {
    $('img.inline-svg[src$=".svg"]').each(function() {
        var $img = jQuery(this);
        var imgURL = $img.attr('src');
        var attributes = $img.prop("attributes");

        $.get(imgURL, function(data) {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');

            // Remove any invalid XML tags
            $svg = $svg.removeAttr('xmlns:a');

            // Loop through IMG attributes and apply on SVG
            $.each(attributes, function() {
                $svg.attr(this.name, this.value);
            });

            // Replace IMG with SVG
            $img.replaceWith($svg);

            EventBus.dispatch('svg:ready');
        }, 'xml');
    });
});

/**
 * Generic window resize event.
 */
var lastWindowWidth  = window.innerWidth,
    lastWindowHeight = window.innerHeight;

$(window).resize(function() {

    EventBus.dispatch('window:resize');

    if (lastWindowWidth != window.innerWidth) {
        EventBus.dispatch('window:resize:width');
        lastWindowWidth = window.innerWidth;
    }

    if (lastWindowHeight != window.innerHeight) {
        EventBus.dispatch('window:resize:height');
        lastWindowHeight = window.innerHeight;
    }

});


/**
 * Detect browser suspensions...
 */
(function($){

var TIMEOUT = 10000;
var lastTime = Date.now();

setInterval(function() {
  var currentTime = Date.now();
  if (currentTime > (lastTime + TIMEOUT + 1000)) {
    $(document).wake();
  }
  lastTime = currentTime;
}, TIMEOUT);

$.fn.wake = function(callback) {
  if (typeof callback === 'function') {
    return $(this).on('wake', callback);
  } else {
    return $(this).trigger('wake');
  }
};

})(jQuery);

/**
 * Set cursor position in input fields.
 *
 * Note, the field needs to be focused first, which this method does not do.
 *
 * From: http://stackoverflow.com/a/841121
 */
$.fn.selectRange = function(start, end) {
    if(end === undefined) {
        end = start;
    }
    return this.each(function() {
        if('selectionStart' in this) {
            this.selectionStart = start;
            this.selectionEnd = end;
        } else if(this.setSelectionRange) {
            this.setSelectionRange(start, end);
        } else if(this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
};
