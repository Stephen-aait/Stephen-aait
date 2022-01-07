;(function ($) {
    'use strict';
    
    $(document).ready(function () {
        var $selectEvent = $('#event, #event_disabled');
            
        function changeVars($eventSelect) {
            var ev = $eventSelect.val();
            $('.auto_task_vars').hide();
            $('.vars_' + ev).show();
        }
        
        function toggleConditions(show) {
            $("#teamandtaskorganizer_autotasks_tabs_task_contitions").toggle(show);
        }
        
        function toggleOrderStatusConditions(show) {
            $("#teamandtaskorganizer_autotasks_tabs_task_order_status_conditions").toggle(show);
        }

        $selectEvent.change(function () {
            var
                $this = $(this),
                value = $this.val();
            
            changeVars($this);
            toggleConditions(value === 'after_order');
            toggleOrderStatusConditions(value === 'after_changed_order_status');
            
        }).trigger('change');
        
        var
            $primaryOrderStatus = $('#primary_order_status'),
            $targetOrderStatus  = $('#target_order_status');
            
        $primaryOrderStatus.change(function() {
            var
                value = $(this).val(),
                $options = $targetOrderStatus.find('option').prop('disabled', false);
            
            if(value !== 'any') {
                $options.filter('option[value="' + value + '"]').prop('disabled', true);
            }
        }).trigger('change');
        
        $targetOrderStatus.change(function() {
            var
                value = $(this).val(),
                $options = $primaryOrderStatus.find('option').prop('disabled', false);
            
            if(value !== 'any') {
                $options.filter('option[value="' + value + '"]').prop('disabled', true);
            }
        }).trigger('change');
        
    });

})(jQuery);

Ext.BLANK_IMAGE_URL = BLANK_IMG;
Ext.UpdateManager.defaults.loadScripts = false;
Ext.UpdateManager.defaults.disableCaching = true;