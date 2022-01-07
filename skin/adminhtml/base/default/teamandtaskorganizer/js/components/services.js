'use strict';

tto.services = (function($, tto, document) {
    var
        beforeEvent = function() {
            $(document).trigger('tto/loader/start');
        },
        afterEvent = function(response) {
            var d = $.Deferred();
            
            $(document).trigger('tto/loader/end');
            d.resolve(response);
            return d.promise();
        };
    
    return {
        post: function(url, params) {
            beforeEvent();
            
            return $.post(url, params).then(afterEvent);
        },
        get: function(url, params) {
            beforeEvent();
            
            return $.get(url, params).then(afterEvent);
        },
        getTasks: function(limit) {
            return $.get(tto.listUrl, {
                limit: limit || 10
            });
        },
        updateTaskStatus: function(taskId, status) {
            return this.get(tto.updateStatusUrl, {
                task_id: taskId,
                status: status
            });
        },
        getComments: function(taskId) {
            return this.get(tto.commentsListUrl, {
                task_id: taskId
            });
        },
        postComment: function(taskId, content, formKey) {
            return this.post(tto.addCommentUrl, {
                task_id: taskId,
                content: content,
                form_key: formKey,
                isAjax: 1
            });
        }
    };
})(jQuery, tto, document);
