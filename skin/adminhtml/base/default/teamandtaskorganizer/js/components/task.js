'use strict';

(function($, tto, window) {
    function Task(rawTask) {
        this.updateData(rawTask);
        this.url = undefined;
    }

    Task.prototype = {
        updateData: function(data) {
            for(var property in data) {
                this[property] = data[property];
            }

            return this;
        },
        setUrl: function(urlBase) {
            this.url = urlBase.replace('TASKID', this.id);
        },
        getDomElement: function() {
            return $('#tto_task_' + this.id);
        },
        getHtml: function() {
            var template = tto.templates.compiled.taskEntity;
            
            return template(this);
        },
        updateDom: function() {
            var 
                html = this.getHtml(),
                $element = this.getDomElement();
            
            if($element.length === 0) {
                return this;
            }
            
            $element.html( $(html).html());
            $element = this.getDomElement();
            
            if( this.isUnread() ) {
                $element.addClass('tto_unread').find('.tto_td_title').append('<span class="tto-new">New</span>');
            }

            if( this.hasUnreadComments() ) {
                $element.find('.tto_td_comments').addClass('tto_unread');
            }
            
            if( this.isPending() ) {
                var startButton = tto.templates.compiled.taskStatusButton({
                    status: tto.status.progress,
                    label: 'Start'
                });
                
                $element.find('.tto_td_buttons').html(startButton);
            }
            
            if( this.isProgress() ) {
                var stopButton = tto.templates.compiled.taskStatusButton({
                    status: tto.status.pending,
                    label: 'Stop'
                });
                
                var doneButton = tto.templates.compiled.taskStatusButton({
                    status: tto.status.done,
                    label: 'Done'
                });
                
                $element.find('.tto_td_buttons').html(stopButton + doneButton);
            }

            return this;
        },
        bindWithDomElement: function() {
            this.getDomElement().data('task', this);
        },
        getStatus: function() {
            return parseInt(this.status);
        },
        isProgress: function() {
            return this.getStatus() === tto.status.progress;
        },
        isPending: function() {
            return this.getStatus() === tto.status.pending;
        },
        isDone: function() {
            return this.getStatus() === tto.status.done;
        },
        isUnread: function() {
            return this.read_by_owner === "0";
        },
        hasUnreadComments: function() {
            return this.comments_unread > 0;
        },
        changeStatus: function(status) {
            if( (this.isPending() && status === tto.status.progress) || (this.isProgress() && (status === tto.status.pending || status === tto.status.done)) ) {
                var self = this;

                tto.services.updateTaskStatus(this.id, status).done(function(response) {
                    self.updateData(response.task).updateDom();
                });
            }
        },
        save: function() {
            var self = this;
            
            return tto.services.post(tto.saveTaskUrl, this).done(function(response) {
                self.updateData(response.task).updateDomElement();
            });
        },
        showComments: function() {
            return tto.services.getComments(this.id).done(function(response) {
                tto.popup.openContent(response.html, tto.lang.commentsForTask + ': <i>' + response.task.title + '</i>');
            });
        },
        highlight: function() {
            var $row = this.getDomElement().addClass('highlight-task');
            
            setTimeout(function() {
                $row.removeClass('highlight-task');
            }, 4000);
        }
    };
    
    window.Task = Task;
})(jQuery, tto, window);