if ("undefined" == typeof(FORM_KEY)) {
    FORM_KEY = '';
}

function attachEdit(field) {
    $('viewblock_' + field).style.display = 'none';
    $('editblock_' + field).style.display = 'block';
}

function attachCancel(field) {
    $('editblock_' + field).style.display = 'none';
    $('viewblock_' + field).style.display = 'block';
}

function attachSave(field, type) {
    attachShowProcess();

    orderId = document.getElementsByName('order_id')[0].value;
    value = $('value_' + field).value;

    postData = 'form_key=' + FORM_KEY + '&order_id=' + orderId + '&field=' + field + '&value=' + value + '&type=' + type;

    new Ajax.Request(attachSaveUrl, {
        method: 'post',
        postBody: postData,
        onSuccess: function (transport) {
            $('field_' + field).innerHTML = transport.responseText;
            if ('date' == type) {
                // should re-setup calendar as it adds observers
                Calendar.setup({
                    inputField: 'value_' + field,
                    ifFormat: "%Y/%m/%d",
                    showsTime: false,
                    button: 'value_' + field + '_trig',
                    align: "Bl",
                    singleClick: true
                });
            }
        },
        onComplete: function () {
            attachHideProcess();
        }
    });
}

function attachSaveProd(field, type) {
    attachShowProcess();

    orderId = document.getElementsByName('order_id')[0].value;
    value = $('value_' + field).value;

    postData = 'form_key=' + FORM_KEY + '&order_id=' + orderId + '&product_form=1' + '&field=' + field + '&value=' + value + '&type=' + type;

    new Ajax.Request(attachSaveUrl, {
        method: 'post',
        postBody: postData,
        onSuccess: function (transport) {
            $('field_' + field).innerHTML = transport.responseText;
            if ('date' == type) {
                // should re-setup calendar as it adds observers
                Calendar.setup({
                    inputField: 'value_' + field,
                    ifFormat: "%Y/%m/%d",
                    showsTime: false,
                    button: 'value_' + field + '_trig',
                    align: "Bl",
                    singleClick: true
                });
            }
        },
        onComplete: function () {
            attachHideProcess();
        }
    });
}

function attachUpload(field) {
    attachShowProcess();
    $('upload_form_' + field).target = 'upload_target_' + field;
    $('upload_target_' + field).observe('load', function () {

        orderId = document.getElementsByName('order_id')[0].value;
        postData = 'form_key=' + FORM_KEY + '&order_id=' + orderId + '&field=' + field;

        new Ajax.Request(attachReloadUrl, {
            method: 'post',
            postBody: postData,
            onSuccess: function (transport) {
                $('field_' + field).innerHTML = transport.responseText;
            },
            onComplete: function () {
                attachHideProcess();
            }
        });

    });
    $('upload_form_' + field).submit();
}


function attachUploadProd(field) {
    attachShowProcess();
    $('upload_form_' + field).target = 'upload_target_' + field;
    $('upload_target_' + field).observe('load', function () {
        orderId = document.getElementsByName('order_id')[0].value;
        postData = 'form_key=' + FORM_KEY + '&order_id=' + orderId + '&product_form=1' + '&field=' + field;
        new Ajax.Request(attachReloadUrl, {
            method: 'post',
            postBody: postData,
            onSuccess: function (transport) {
                $('field_' + field).innerHTML = transport.responseText;
            },
            onComplete: function () {
                attachHideProcess();
            }
        });

    });
    $('upload_form_' + field).submit();
}

function attachDeleteFile(field, file, type) {
    attachShowProcess();
    orderId = document.getElementsByName('order_id')[0].value;
    postData = 'form_key=' + FORM_KEY + '&order_id=' + orderId + '&field=' + field + '&type=' + type + '&file=' + file;

    new Ajax.Request(attachDeleteUrl, {
        method: 'post',
        postBody: postData,
        onSuccess: function (transport) {
            $('field_' + field).innerHTML = transport.responseText;
        },
        onComplete: function () {
            attachHideProcess();
        }
    });
}

function attachDeleteFileProd(field, file, type) {
    attachShowProcess();
    orderId = document.getElementsByName('order_id')[0].value;
    postData = 'form_key=' + FORM_KEY + '&order_id=' + orderId + '&product_form=1' + '&field=' + field + '&type=' + type + '&file=' + file;

    new Ajax.Request(attachDeleteUrl, {
        method: 'post',
        postBody: postData,
        onSuccess: function (transport) {
            $('field_' + field).innerHTML = transport.responseText;
        },
        onComplete: function () {
            attachHideProcess();
        }
    });
}

function attachShowProcess() {
    if ($('amattach-block')) {
        $('amattach-block').setOpacity(0.2);
    }
    if ($('my-orders-table')) {
        $('my-orders-table').setOpacity(0.2);
    }
    if ($('amattach-pleasewait')) {
        $('amattach-pleasewait').show();
    }
}

function attachHideProcess() {
    if ($('amattach-block')) {
        $('amattach-block').setOpacity(1);
    }
    if ($('my-orders-table')) {
        $('my-orders-table').setOpacity(1);
    }
    if ($('amattach-pleasewait')) {
        $('amattach-pleasewait').hide();
    }
}

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