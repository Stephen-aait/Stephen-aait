'use strict';

tto.templates = (function($, Handlebars) {
    var templates = {
        taskEntity: $('#tto-task-entity').html(),
        tasksList: $('#tto-tasks-list').html(),
        popup: $('#tto-popup').html(),
        taskStatusButton: $('#tto-task-status-change-button').html(),
        taskComment: $('#tto-task-comment').html()
    };
    
    templates.compiled = {
        taskEntity: Handlebars.compile(templates.taskEntity),
        tasksList: Handlebars.compile(templates.tasksList),
        popup: Handlebars.compile(templates.popup),
        taskStatusButton: Handlebars.compile(templates.taskStatusButton),
        taskComment: Handlebars.compile(templates.taskComment)
    };
    
    Handlebars.registerPartial('taskEntity', templates.taskEntity);
    
    return templates;
})(jQuery, Handlebars);