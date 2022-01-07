'use strict';

tto.notification = (function($) {
    var
        $toggleBtn = $('#slide-down-button'),
        pulseTimeout = 500,
        pulseInterval = null;

    return {
        pulse: function() {
            if( ! pulseInterval) {
                pulseInterval = setInterval(function() {
                    $toggleBtn.toggleClass('tto_unread');
                }, pulseTimeout);
            }
        },
        stop: function() {
            $toggleBtn.removeClass('tto_unread');
            clearInterval(pulseInterval);
            pulseInterval = null;
        },
        setCountOfUnreaded: function(count) {
            var $obj = $toggleBtn.find('span span span');

            $obj.find('.tto_unread_count').remove();

            if (count > 0){
                $obj.append('<span class="tto_unread_count">(' + count + ')</span>');
            }
        }
    };
})(jQuery);