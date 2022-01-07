;(function ($) {
    'use strict';
    
    $(document).ready(function () {
        var $ttoForm = $("#edit_form");
        // on click on fieldset header, select all options
        $ttoForm.find(".entry-edit-head").click(function () {
            var
                $this = $(this),
                $fieldset = $this.next(),
                toselect = ! $fieldset.find('input[type=checkbox]:first').is(':checked');
            
            $fieldset.find('input[type=checkbox]').prop('checked', toselect);
            $fieldset.find('select option').prop('selected', toselect);
            $this.find('input').prop('checked', toselect);
        });
    });

})($jq);