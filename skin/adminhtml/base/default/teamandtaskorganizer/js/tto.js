'use strict';

(function($, tto, document, window) {
    
    $(document).ready(function() {
        
        Tasks.populateList();
        
        var
            $loader = $('#loading-mask'),
            $container = $('#tto-container');
        
        $(document)
            .on('resize', function() {
                $(window).trigger('resize.modal');
            })
            .on('tto/loader/start', function() {
                $loader.show();
            })
            .on('tto/loader/end', function() {
                $loader.hide();
            })
            .on('tto/task/created', function(e, task, message) {
                Tasks.stopAutoRefresh();
            
                Tasks.populateList().then(function() {
                    Tasks.startAutoRefresh(tto.options.refreshFrequency);
                    
                    if(message) {
                        tto.popup.openContent('<div>' + message + '</div>', message);
                    }
                });
            })
            .on('tto/task/updated', function(e, task) {
                Tasks.stopAutoRefresh();
                Tasks.find(task).data('task').updateData(task).updateDom().highlight(); 
                Tasks.startAutoRefresh(tto.options.refreshFrequency);
            })
            .on('click', '#edit_task_ajax_form_submit', function() {
                Tasks.populateList();
            })
            .on('click', '.ajax-link', function(e) {
                var
                    $this = $(this),
                    $taskEntity = $this.closest('.tto_task_entity'),
                    url = $this.attr('href'),
                    title = undefined;
                    
                if( $taskEntity.length ) {
                    title = $taskEntity.data('task').title;
                }
            
                tto.popup.open(url, title);
                
                e.preventDefault();
            })
            .on('focus blur keyup', '#quick_comment_content', function() {
                var $this = $(this);
            
                if( $this.hasClass('error') && $this.val().length ) {
                    $this.removeClass('error');
                }
            })
            .on('click', '#save_comments_form', function(e) {
                var
                    $this = $(this),
                    $content = $('#quick_comment_content');
            
                if( $content.val().length ) {
                    var
                        $form = $content.closest('form'),
                        taskId = $form.find('#quick_comment_task_id').val(),
                        formKey = $form.find('#quick_comment_form_key').val(),
                        content = $content.val();
                        
                    $('body').css('cursor', 'wait');
                    $this.prop('disabled', true);
                        
                    tto.services.postComment(taskId, content, formKey).done(function(response) {
                        if(response.success) {
                            $('#no-comments-info').remove();
                            $('#comments-list').prepend( tto.templates.compiled.taskComment(response.comment) );
                            $content.val('');
                            $('body').css('cursor', 'default');
                            $this.prop('disabled', false);
                            $(document).trigger('tto/task/saved', [response.task]);
                        }
                    });
                }
                else {
                    $content.addClass('error');
                }
                
                e.preventDefault();
            });
            
        $('#slide-down-button').on('click', function(e) {
            tto.grid.slide();
        });
        
        $container
            .on('click', '.tto_new_task', function(e) {
                tto.popup.open(tto.newTaskUrl, 'New Task');
		e.preventDefault();
            })
            .on('click', '.show-comments', function(e) {
                $(this).closest('.tto_task_entity').data('task').showComments();
            
                e.preventDefault(); 
            })
            ;
            
        Tasks.startAutoRefresh(tto.options.refreshFrequency);
    });
    
    var Tasks = (function() {
        var
            $tasks = $('#task-list-live'),
            _autoRefresh;
            
        tto.grid.setPlacement( $tasks.closest('.task-list').data('placement') ).updateTasksList( $tasks );

        $tasks.on('click', '.status-change', function() {
            var
                $this   = $(this),
                status  = $this.data('status');

            $this.closest('.tto_task_entity').data('task').changeStatus(status);
        });
        
        return {
            all: function() {
                return $tasks.find('tr.tto_task_entity');
            },
            getUnreaded: function() {
                return $tasks.find('tr.tto_task_entity.tto_unread');
            },
            hasUnreaded: function() {
                return this.getUnreaded().size();
            },
            stopNotificationIfAllReaded: function() {
                if( this.hasUnreaded() ) {
                    tto.notification.stop();
                }
            },
            find: function(task) {
                return $('#tto_task_' + task.id);
            },
            bindDom: function(tasks) {
                var $tasks = this.all();
                
                $.each(tasks, function() {
                    var task = this;
                    
                    $tasks.filter(function() {
                        return $(this).data('task-id') == task.id;
                    }).data('task', task);
                });
                
                return this;
            },
            updateDom: function(task) {
                if(task) {
                    this.find(task).data('task').updateDom();
                }
                else {
                    this.all().each(function() {
                        $(this).data('task').updateDom();
                    });
                }
                
                return this;
            },
            addTask: function(task) {
                $tasks.find('#tasks-list-body').append(task.getHtml());
            },
            makeTaskFromArray: function(dataArray) {
                var data = {};
                
                $(dataArray).each(function(i, v) {
                    data[v.name] = v.value;
                });
                
                return new Task(data);
            },
            updateList: function() {
                var self = this;
                
                return tto.services.getTasks(10).done(function(response) {
                    var unreadTasks = 0;
                    
                    if(response.tasks.length) {
                        var urlBase = response.perm_editOwnTask === 1 ? tto.editTaskUrl : tto.viewTaskUrl;
                        
                        $.each(response.tasks, function(i, task) {
                            response.tasks[i] = new Task(task);
                            response.tasks[i].setUrl(urlBase);
                            
                            if( response.tasks[i].isUnread() ) {
                                tto.notification.pulse();
                                unreadTasks++;
                            }
                        });
                        
                        response.tto = tto;
                        
                        var template = tto.templates.compiled.tasksList(response);
                        
                        $tasks.html(template);
                    }
                    else {
                        $tasks.html('<div class="no-tasks-assigned-label">' + tto.lang.noTasks + '</div>');
                    }
                    
                    tto.notification.setCountOfUnreaded(unreadTasks);
                    self.bindDom(response.tasks).updateDom();
                });
            },
            populateList: function() {
                var def = $.Deferred();
                
                this.updateList().then(function() {
                    tto.grid.updateTasksList( $tasks );
                    def.resolve();
                });
                
                return def.promise();
            },
            startAutoRefresh: function(frequency) {
                var self = this;
                
                _autoRefresh = setTimeout(function() {
                    self.updateList().then(function() {
                        self.startAutoRefresh(frequency);
                    });
                }, frequency);
            },
            stopAutoRefresh: function() {
                clearTimeout(_autoRefresh);
            }
        };
    })();
    
})(jQuery, tto, document, window);