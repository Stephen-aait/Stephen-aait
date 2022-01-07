/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2013 Amasty (http://www.amasty.com)
*/

pointerX = 0;
pointerY = 0;

Object.extend(Prototype.Browser, {
    IE9: (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) ? (Number(RegExp.$1) == 9 ? true : false) : false
});

var amOgrid = new Class.create();

amOgrid.prototype = {
    initialize: function(properties, saveUrl, /*saveAllUrl,*/ storeId, dateFormat, calendarUrl, options)
    {
        this.saveUrl       = saveUrl;
//        this.saveAllUrl    = saveAllUrl;
        this.properties    = properties;
        this.storeId       = storeId;
        this.dateFormat    = dateFormat;
        this.calendarUrl   = calendarUrl;
        this.options       = options;
        
        this.saveAllButtonId = 'ampgrid_saveall_button';
        
        this.dataToSave    = new Hash();
        
        this.init();
    },
    
    init: function()
    {
        this.values     = new Array();
        this.orderIds = new Array();
        this.table = $('sales_order_grid_table');
        if (!this.table)
        {
            return false;
        }
        this.colnames = new Array();
        // attaching listeners to grid td-s
        $$('#sales_order_grid_table tr.headings th').each(function(th){
            this.colnames[this.colnames.length] = '';

            var classes = th.className == "" ? [] : th.className.split(/\s+/);
            for (var i = 0; i < classes.length; i++)
            {
                var match = false;
                if (match = classes[i].match(/^fieldname-(.+)$/))
                    this.colnames[this.colnames.length-1] = match[1];
            }
/*
            th.getElementsBySelector('td').each(function(a){
                this.colnames[this.colnames.length-1] = a.name; // -1 because we already saved empty, and if element found, we should replace
            }.bind(this));
*/
        }.bind(this));
        
        $$('#sales_order_grid_table tbody tr').each(function(row){
            var checkbox = row.select('.massaction-checkbox');
            var orderId = 0;

            if (checkbox.length > 0)
                orderId = checkbox[0].value;

            row.childElements().each(function(td, i){
                this.orderIds[td.identify()] = orderId;
                if (field = this.properties[this.colnames[i]])
                {
                    td.observe('click', this.cellClick.bindAsEventListener(this, field, td)); // attaching onClick event to each TD, need to pass current field and td into the listener scope
                    td.observe('mouseover', this.cellMouseOver.bindAsEventListener(this, td)); // will change cursor
                    td.observe('mouseout', this.cellMouseOut.bindAsEventListener(this, td)); // will change cursor
                }
            }.bind(this));
        }.bind(this));
    },
    
    cellMouseOver: function(event)
    {
        var args  = $A(arguments);
        var td    = args[1];
        td.style.cursor = 'text';
        
        if (!td.hasClassName('clicked'))
        {
            pointerX = event.pointerX();
            pointerY = event.pointerY();
            notifyTimeout = setTimeout(oeditGrid.notifyEdit, 400);
            setTimeout("$('amorderattach_edit_note').style.display = 'none';", 2000);
        }
    },

    cellMouseOut: function(event)
    {
        if ('undefined' != typeof(notifyTimeout))
        {
            clearTimeout(notifyTimeout);
        }
    },

    cellClick: function(event)
    {
        var tag = event.target.tagName.toLowerCase();
        if (['a', 'input', 'button'].indexOf(tag) == -1)
            Event.stop(event);
        else
            return;

        var args  = $A(arguments);
        var field = args[1];
        var td    = args[2];
        if (!td.hasClassName('clicked'))
        {
            td.addClassName('clicked');
            var value = td.innerHTML.strip();
            this.values[td.identify()] = value;
            if ('&nbsp;' == value)
            {
                value = "";
            }
            value = value.replace('&nbsp;',' ');
            switch (field.type)
            {
                case 'file':
                case 'file_multiple':
                    td.down('.values').hide();
                    td.down('.edit').show();

                    break;

                case 'string':
                case 'link':
                    input = document.createElement('input');
                    input.type = 'text';
                    input.value = value;

                    if (Prototype.Browser.IE && !Prototype.Browser.IE9) {
                        input.setAttribute('class', "ampgrid_input_text editable");
                    } else
                    {
                        input.addClassName('ampgrid_input_text');
                        input.addClassName('editable');
                    }
                    td.innerHTML = '';
                    td.appendChild(input);
                    var element = input;
                    element.style.position = 'relative';
                    element.style.top = '-1px';
                    element.style.left = '-2px';
                    $(element).observe('keypress', this.cellKeyPress.bindAsEventListener(this, field, td));
                    $(element).observe('blur',     this.cellSave.bindAsEventListener(this, field, td));
                    element.focus();

                break;
                case 'text':
                	area = document.createElement('textarea');
                    value=value.replace(new RegExp('&lt;','g'), '<');
                    value=value.replace(new RegExp('&gt;','g'), '>');
                    value=value.replace(new RegExp('<br>','g'), "");

                    this.values[td.identify()] = value;

                	area.value = value;
                    if (Prototype.Browser.IE && !Prototype.Browser.IE9) {
                    	area.setAttribute('class', "ampgrid_input_text editable");
                    } else
                    {
                    	area.addClassName('ampgrid_input_text');
                    	area.addClassName('editable');
                    }
                    td.innerHTML = '';
                    td.appendChild(area);
                    var element = area;
                    element.style.position = 'relative';
                    element.style.top = '-1px';
                    element.style.left = '-2px';
                    element.style.height = '300px';
//                    $(element).observe('keypress', this.cellKeyPress.bindAsEventListener(this, field, td));
                    $(element).observe('blur',     this.cellSave.bindAsEventListener(this, field, td));
                    element.focus();
                break;
                case 'select':
                    sel = document.createElement('select');
                    if (Prototype.Browser.IE && !Prototype.Browser.IE9) {
                        sel.setAttribute('class', "ampgrid_input_select editable");
                    } else
                    {
                        sel.addClassName('ampgrid_input_select');
                        sel.addClassName('editable');
                    }

					var sortable = [];
					for (var option in field.options)
						  sortable.push([option, field.options[option]]);

					sortable.sort(function (a, b) {
						if (a[1] > b[1])
							return 1;
						else if (a[1] < b[1])
							return -1;
						else
							return 0;
					});

					var h = sortable; //var h = $H(field.options);

                    h.each(function(optionItem){
                        var val   = optionItem[0];
                        var label = optionItem[1];
                        if ('string' == typeof(label))
                        {
	                        option    = document.createElement('option');
	                        option.value = val;
	                        option.text  = label;
	                        option.innerText  = label;
	                        if (value == label)
	                        {
	                            option.selected = true;
	                        }
	                        sel.appendChild(option);
                        }
                    });
                    td.innerHTML = '';
                    td.appendChild(sel);
                    var element = sel;
                    $(element).observe('change',   this.cellSave.bindAsEventListener(this, field, td));
                    $(element).observe('blur',     this.cellSave.bindAsEventListener(this, field, td));
                    element.focus();
                break;
                case 'date':
                    input = document.createElement('input');
                    input.type = 'text';
                    input.value = value;
                    if (Prototype.Browser.IE && !Prototype.Browser.IE9) {
                        input.setAttribute('class', "ampgrid_input_text ampgrid_input_date editable");
                    } else
                    {
                        input.addClassName('ampgrid_input_text');
                        input.addClassName('ampgrid_input_date');
                        input.addClassName('editable');
                    }
                    td.innerHTML = '';
                    td.appendChild(input);
                    var element = input;
                    element.style.position = 'relative';
                    element.style.top = '-1px';
                    element.style.left = '-2px';
                    element.readOnly = true;

                    // creating calendar button
                    calendarBtn = document.createElement('img');
                    calendarBtn.src = this.calendarUrl;
                    calendarBtn.alt = "";
                    calendarBtn.style.cursor = 'pointer';
                    td.appendChild(calendarBtn);
                    calendarBtn.style.position = 'relative';
                    calendarBtn.style.top = '2px';
                    calendarBtn.style.left = '1px';

                    Calendar.setup({
                        inputField : element.identify(),
                        ifFormat : this.dateFormat,
                        button : calendarBtn.identify(),
                        align : "Bl",
                        singleClick : true,
                        onClose : function(cal) {
                            cal.hide();
                            var date = new Date(cal.date);
                            td.down('input').setAttribute(
                                'data-value',
                                date.getFullYear() + '-' + (date.getMonth()+1) + '-' + date.getDate()
                            );
                            this.cellSave(this, field, td);
                        }.bind(this)
                    });

                    calendarBtn.click();

                break;
            }
        }
        //Event.stop(event);
    },

    cellSave: function(event)
    {
        var args = $A(arguments);
        var field = args[1];
        var td    = args[2];

        var element = td.select('.editable')[0];

        if (!element)
        {
            return;
        }

        element.addClassName('progressing');
        element.removeClassName('editable');

        var displayValue;
        var saveValue;

        switch (element.type)
        {
            case 'select-one':
                displayValue = element.options[element.selectedIndex].text;
                break;
            default:
                displayValue = element.value;
                break;
        }

        if (displayValue != this.values[td.identify()])
        {
            orderId     = this.orderIds[td.identify()];

            var attrValue = element.readAttribute('data-value');
            if (attrValue) {
                saveValue = attrValue;
            }
            else {
                saveValue = displayValue;
            }

            postData  = 'form_key=' + FORM_KEY + '&order_id=' + orderId + '&store=' + this.storeId + '&field=' + field.col + '&value=' + encodeURIComponent(saveValue);

            new Ajax.Request(this.saveUrl, {
                method: 'post',
                postBody : postData,
                onSuccess: function(transport) {
                    var element = td.select('.progressing')[0];
                    if (transport.responseText.isJSON()) {
                        var response = transport.responseText.evalJSON();
                        if (response.error) {
                            alert(response.message);
                            element.addClassName('editable');
                            element.removeClassName('progressing');
                        }
                        if (response.success) {
                            if (displayValue != saveValue) {
                                td.innerHTML = displayValue;
                            } else {
                                td.innerHTML = response.value;
                            }
                            td.removeClassName('clicked');
                        }
                    }
                },
                onFailure: function()
                {
                    var element = td.select('.progressing')[0];
                    alert('Request failed. Please retry.');
                    element.addClassName('editable');
                    element.removeClassName('progressing');
                }
            });
        } else
        {
            td.innerHTML = this.values[td.identify()];
            td.removeClassName('clicked');
        }

    },

    cellKeyPress: function(event)
    {
        if (event.keyCode == 13) // ENTER key
        {
            var args = $A(arguments);
            var field = args[1];
            var td    = args[2];
            this.cellSave(event, field, td);
        }
    },

    notifyEdit: function()
    {
        $('amorderattach_edit_note').style.left = 45 + pointerX + 'px';
        $('amorderattach_edit_note').style.top = -60 + pointerY + 'px';
        $('amorderattach_edit_note').style.display = 'block';
    }
};


Event.observe(window, 'load', function(){
    
    sales_order_gridJsObject.initGridAjaxReloaded = sales_order_gridJsObject.initGridAjax;

    sales_order_gridJsObject.initGridAjax = function()
    {
        this.initGridAjaxReloaded();
        if ('oeditGrid' in window)
            oeditGrid.init();
    }
    
    sales_order_gridJsObject.doExport = function (){
        if($(this.containerId+'_export')){
            var exportUrl = $(this.containerId+'_export').value;
//            if(this.massaction && this.massaction.checkedString) {
//                exportUrl = this._addVarToUrl(exportUrl, this.massaction.formFieldNameInternal, this.massaction.checkedString);
//            }
            location.href = exportUrl;
        }
    }
    
});
