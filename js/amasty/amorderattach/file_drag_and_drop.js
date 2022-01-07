FileDragAndDrop = Class.create();

FileDragAndDrop.prototype =
{
    initialize: function (uploadUrl, box) {
        this.uploadUrl = uploadUrl;
        this.box = box;
        this.initDragAndDrop();
    },

    initDragAndDrop: function()
    {
        var drop = this.box.down('.drop');
        drop.show();

        this.box.down('input[type=file]').observe('change', function(e){
            this.submitFile(this.box);
        }.bind(this));

        drop.observe('click', function(event){
            event.stopPropagation();
            event.preventDefault();
            this.box.down('input[type=file]').click();
        }.bind(this));

        drop.observe('drop', this.dropFile.bind(this));

        drop.observe('dragover', this.updateDrag);
        drop.observe('dragenter', this.updateDrag);
        drop.observe('dragleave', this.updateDrag);

        this.box.down('input[type=file]').hide();
    },

    submitFile: function(box, file)
    {
        this.showPreloader(box.down('.drop'));

        var fd = new FormData;

        var elements = box.select('input:not([type=file]), select');

        elements.each(function(element){
            fd.append(element.name, element.value);
        });
        var fileInput = box.down('input[type=file]');
        fd.append(fileInput.name, file ? file : fileInput.files[0]);
        fd.append('form_key', FORM_KEY);

        var xhr = new XMLHttpRequest();
        var self = this;
        xhr.addEventListener('load', function(e){
            var response = e.target.response.evalJSON();
            if (response.errors.length > 0)
            {
                if(box.down('.preloader')) {
                    self.removePreloader(box.down('.drop'));
                    self.blinkSuccessIcon(box.down('.drop'));
                }
                Effect.Shake(box);
                alert(response.errors[0]);
            }
            else
            {
                if(box.down('.preloader')) {
                    self.removePreloader(box.down('.drop'));
                    self.blinkSuccessIcon(box.down('.drop'));
                }
                box.down('.drop').show();
                fileInput.value = '';
            }
        }, false);

        xhr.open('POST', this.uploadUrl);
        xhr.send(fd);
    },

    blinkSuccessIcon: function(box, callback) {
        var success = new Element('div', {class: 'success-upload-image'});
        box.appendChild(success);
        Effect.Fade(success, {duration: 1.2, afterFinish: function () {box.removeChild(success); new Effect.Opacity(box.down('span'), { from: 0.0, to: 1.0, duration:0 }); }});
    },

    showPreloader:function(box)
    {
        var preloader = new Element('div', {class: 'preloader'});
        preloader.appendChild(new Element('img', {src: $('loading-mask').down('img').readAttribute('src')}));

        box.appendChild(preloader);
    },

    removePreloader: function(box)
    {
        box.down('.preloader').remove();
    },

    updateDrag: function(e)
    {
        e.stopPropagation();
        e.preventDefault();

        if (e.target.tagName == 'DIV')
        {
            if (e.type == 'dragover')
                e.target.addClassName('hover');
            else
                e.target.removeClassName('hover');
        }
    },

    dropFile: function(e)
    {
        e.stopPropagation();
        e.preventDefault();
        this.updateDrag(e);
        if (e.dataTransfer.files.length > 0)
        {
            this.submitFile(this.box, e.dataTransfer.files[0]);
        }
        return e.dataTransfer.files.length > 0;
    }

}