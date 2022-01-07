'use strict';

(function($, document) {
    
    $(document).ready(function() {
        var
            $startDate = $('input[name="startdate"]', '#tto-startdate-row'),
            $endDate = $('input[name="enddate"]', '#tto-enddate-row'),
            $progress = $('input[name="progress"]', '#tto-progress-row');
            
        setupDatepickers($startDate, $endDate);
        setupProgress($progress);
    });
    
    $(document).on('tto/popup/opened', function(e, $popup) { 
        var
            $startDate = $('input[name="startdate"]', $popup),
            $endDate = $('input[name="enddate"]', $popup),
            $progress = $('input[name="progress"]', $popup),
            $form = $('form', $popup);
            
        $startDate.attr('id', 'popup-' + $startDate.attr('id'));
        $endDate.attr('id', 'popup-' + $endDate.attr('id'));
        $progress.attr('id', 'popup-' + $progress.attr('id'));
        $form.attr('id', 'popup-' + $form.attr('id'));
        
        setupDatepickers($startDate, $endDate);
        setupProgress($progress);
    });
    
    var setupProgress = function(progress) {
        progress.spinner({
            min: 0,
            max: 100
        });
        
        if(progress && ! progress.val()) {
            progress.spinner('value', 0);
        }
    };
    
    var setupDatepickers = function(startDate, endDate) {
        startDate.datepicker({
            dateFormat: 'yy-mm-dd',
            onClose: function( selectedDate ) {
                endDate.datepicker("option", "minDate", selectedDate );
            }
        });

        endDate.datepicker({
            dateFormat: 'yy-mm-dd',
            onClose: function( selectedDate ) {
                startDate.datepicker("option", "maxDate", selectedDate );
            }
        });
    };
})(jQuery, document);