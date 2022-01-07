'use strict';

tto.popup = (function($, tto, document, window) {
    var
        $popup,
        _isOpen = false;

    $(document)
        .on('keyup', function(e) {
            if (e.which === 27) {
                options.close();
            }
        })
        .on('click', '.message-popup-close', function(e) {
            options.close();
            e.preventDefault();
        });
    
    var options = {
        isOpen: function() {
            return _isOpen;
        },
        openContent: function(content, title) {
            $(document).trigger('tto/popup/opening');
            
            var
                $content = $(content),
                $contentTitle = $content.find('h2'),
                windowHeight = $(window).height();

            $contentTitle.remove();
            title = title || $contentTitle.text();

            var popup = tto.templates.compiled.popup({
                title: title,
                content: $content.html()
            });

            $('body').append(popup);

            $popup = $('#tto-ajax-popup').css({
                width: '800px',
                display: 'none',
                position: 'fixed',
                zIndex: 999,
                margin: 0
            });
            
            $popup.fadeIn().find('.message-popup-content').css('max-height', (windowHeight - 140) + 'px');
            $('#message-popup-window-mask').fadeIn();
            
            this.center();
            
            $(window).on('resize.modal', this.center);
            
            _isOpen = true;
            
            $(document).trigger('tto/popup/opened', [$popup]);
        },
        open: function(href, title) {
            tto.services.get(href).done(function(response) {
                options.openContent(response, title);
            });
        },
        close: function() {
            $(window).off('resize.modal');
            
            $popup.closest('.tto').fadeOut(function() {
                $(this).remove();
                _isOpen = false;
                $(document).trigger('tto/popup/closed');
            });
        },
        center: function() {
            $popup = $('#tto-ajax-popup');
            
            if( $popup.length === 0 ) {
                return this;
            }
            
            var
                top = Math.max( $(window).height() - $popup.outerHeight(), 0) / 2,
                left = Math.max( $(window).width() - $popup.outerWidth(), 0) / 2;
            
            $popup.css({
                top: top,
                left: left + $(window).scrollLeft()
            });
        }
    };
    
    return options;
})(jQuery, tto, document, window);