if ("undefined" == typeof(FORM_KEY))
{
    FORM_KEY = '';
}

/*
$("sales_order_grid_table").on("click", ".drop", function(event, element) {
    console.log(event, element);
});
*/

function attachAddProgress(td, f)
{
    var container = td.down('.progress');
    container.show();

    var progressBar = document.createElement('div');
    progressBar.addClassName('progress-bar');
    progressBar.setAttribute('data-name', f.name);
    var complete = document.createElement('div');
    complete.addClassName('complete');
    complete.innerHTML = '&nbsp;';
    complete.style.width = '0%';
    progressBar.appendChild(complete);

    var caption = document.createElement('div');
    caption.addClassName('caption');
    caption.innerHTML = f.name;

    container.appendChild(caption);
    container.appendChild(progressBar);
}

function attachRemoveProgress(td)
{
    td.down('progress').hide();
    td.down('progress').innerHTML('');
}

function attachUpload5(files, form)
{
    var td = form.up('td');
    var input = form.down('input[type=file]');
    var field = input.name;
    input.remove();

    var uploads = files.length;
    for (var i = 0; i < files.length; i++)
    {
        var fd = new FormData(form);
        var f = files[i];
        fd.append(field, f);


        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener('progress', function(i, e){
//            console.log('update:', e, i);
            if (e.lengthComputable) {
                var percent = Math.round(e.loaded * 100 / e.total);
                console.log('progress: ' + percent);
                td.select('.complete')[i].style.width = percent + '%';
            }
        }.bind(this, i), false);

        xhr.addEventListener('load', function(){
            if (--uploads == 0) // last file uploaded;
            {
                var postData = {
                    form_key:   FORM_KEY,
                    order_id:   form['order_id'].value,
                    field:      form['field'].value,
                    grid:       1
                };

                new Ajax.Request(attachReloadUrl, {
                    method: 'post',
                    parameters : postData,
                    onSuccess: function(transport) {
                        td.innerHTML = transport.responseText;
                        td.removeClassName('clicked');

                        attachRemoveProgress(td);
                    }
                });
            }
        }, false);

        xhr.open('POST', form.action);
        xhr.send(fd);
    }
}

function DropFile(e)
{
    e.stopPropagation();
    e.preventDefault();
    // cancel event and hover styling
    FileDragHover(e);
    var multiple = +e.target.getAttribute('data-multiple') == 1;
    // fetch FileList object
    var files = e.target.files || e.dataTransfer.files;

    var td = e.target.up('td');
    var form = td.down('form');

    if (!multiple)
    {
        attachAddProgress(td, files[0]);
        attachUpload5([files[0]], form);
    }
    else
    {
        for (var i = 0, f; f = files[i]; i++) {
            attachAddProgress(td, f);
        }
        attachUpload5(files, form);
    }
}

function FileDragHover(e) {
    e.stopPropagation();
    e.preventDefault();
    if (e.target.tagName == 'DIV')
    {
        if (e.type == 'dragover')
            e.target.addClassName('hover');
        else
            e.target.removeClassName('hover');
    }
}

function attachCancel(sender)
{
    var td = sender.up('td');

    td.removeClassName('clicked');

    td.down('.values').show();
    td.down('.edit').hide();
}


function attachUpload(sender, field)
{
    var td = sender.up('td');
    var iframe = td.down('iframe');
    var form = td.down('form');

    form.target = iframe.name;
    iframe.observe('load', function(){
        
        var orderId  = document.getElementsByName('order_id')[0].value;

        var postData = {
            form_key:   FORM_KEY,
            order_id:   orderId,
            field:      field,
            grid:       1
        };
        
        new Ajax.Request(attachReloadUrl, {
            method: 'post',
            parameters : postData,
            onSuccess: function(transport) {
                td.innerHTML = transport.responseText;
                td.removeClassName('clicked');
            }
        });
        
    });
    form.submit();
}

function attachDeleteFile(sender, orderId, field, file, type)
{
    var td = sender.up('td');

    var postData = {
        form_key:   FORM_KEY,
        order_id:   orderId,
        field:      field,
        type:       type,
        file:       file,
        grid:       1
    };
    
    new Ajax.Request(attachDeleteUrl, {
        method: 'post',
        parameters : postData,
        onSuccess: function(transport) {
            td.innerHTML = transport.responseText;
            td.removeClassName('clicked');
        }
    });
}

function attachDeleteFileProd(sender, field, file, type)
{
    var td = sender.up('td');

    var postData = {
        form_key:   FORM_KEY,
        product_form:   1,
        field:      field,
        type:       type,
        file:       file,
        grid:       1
    };

    new Ajax.Request(attachDeleteUrl, {
        method: 'post',
        parameters : postData,
        onSuccess: function(transport) {
            td.innerHTML = transport.responseText;
            td.removeClassName('clicked');
        }
    });
}




function attachUploadProd(sender, field)
{
    var td = sender.up('td');
    var iframe = td.down('iframe');
    var form = td.down('form');

    form.target = iframe.name;
    iframe.observe('load', function()
    {
        var postData = {
            form_key:   FORM_KEY,
            product_form:   1,
            field:      field,
            grid:       1
        };

        new Ajax.Request(attachReloadUrl, {
            method: 'post',
            parameters : postData,
            onSuccess: function(transport) {
                td.innerHTML = transport.responseText;
                td.removeClassName('clicked');
            }
        });

    });
    form.submit();
}
