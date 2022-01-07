var estimateddeliverydate = {
    save: function(url) {
        data = $('leadtimes_form').serialize(true);
        new Ajax.Request(url, {
            method: 'post',
            parameters: data,
            onFailure: function() {
                alert('An error occurred while saving the data.');

            },
            onSuccess: function(response) {
                data = response.responseText.evalJSON();

                if (typeof data != 'object')
                    alert('An error occurred while saving the data.');
                data.each(function(d) {

                    
                })
            }
        });
    }
}