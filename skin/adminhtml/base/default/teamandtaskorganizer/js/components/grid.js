'use strict';

tto.grid = (function($, tto) {
    var
        height = 100,
        validPlacements = ['bottom', 'right', 'corner'],
        usedPlacement,
        $_tasksList;
        
    var setupTasksListAsBottom = function($tasksList) {
        var $tasksListContainer = $tasksList.closest('.task-list');
        
        height = $tasksList.height();
        $tasksListContainer.height(height + 2);
        
        var hidden = ($.cookie('taskListOpen') === '1' ? 0 : 0 - height - 2);
        
        if(!hidden) {
            $tasksListContainer.css('right', 0).css('bottom', 0);
        }
        
        if($tasksListContainer.css('bottom').replace('px', '') !== '0') {
            $tasksListContainer.css('bottom', hidden);
        }
    };
    
    var setupTasksListAsRight = function($tasksList) {
        var
            $tasksListContainer = $tasksList.closest('.task-list'),
            width = $tasksList.width(),
            hidden = ($.cookie('taskListOpen') === '1' ? 0 : 0 - width);
        
        $tasksListContainer.css('height', height + 'px');
        
        if (!hidden) {
                $tasksListContainer.css('right', 0).css('bottom', 0);
        }
        
        if( $tasksListContainer.css('right').replace('px', '') !== '0') {
            $tasksListContainer.css('right', hidden);
        }
    };
    
    var setupTasksListAsCorner = function($tasksList) {
        var
            $tasksListContainer = $tasksList.closest('.task-list'),
            width = $tasksList.width() - 160;
        
        height = $tasksList.height();
        
	var hiddenBottom = ($.cookie('taskListOpen') === '1' ? 0 : 0 - height - 2);
	var hiddenRight = ($.cookie('taskListOpen') === '1' ? 0 : 0 - width - 6);
        
	$tasksListContainer.css('height', height);
        
        if(!hiddenBottom) {
            $tasksListContainer.css('right', 0).css('bottom', 0);
        }
        
        if( $tasksListContainer.css('right').replace('px', '') !== '0') {
            $tasksListContainer.css('right', hiddenRight).css('bottom', hiddenBottom);
        }
    };
    
    var _slide = function slide(elem, placement, v) {
        var cookieState = 0;
        if (placement === 'right') {
                elem.animate({
                        right: v
                });
                if (v === 0) {
                        cookieState = 1;
                }
        }
        if (placement === 'bottom') {
                elem.animate({
                        bottom: v
                });
                if (v === 0) {
                        cookieState = 1;
                }
        }
        if (placement === 'corner') {
                elem.animate({
                        right: v[0],
                        bottom: v[1]
                });
                if (v[0] === 0) {
                        cookieState = 1;
                }
        }
        
        tto.notification.stop();

        $.cookie('taskListOpen', cookieState);
    };
    
    var options = {
        isValidPlacement: function(placement) {
            return $.inArray(placement, validPlacements) >= 0;
        },
        isBottom: function() {
            return usedPlacement === 'bottom';
        },
        isRight: function() {
            return usedPlacement === 'right';
        },
        isCorner: function() {
            return usedPlacement === 'corner';
        },
        setPlacement: function(placement) {
            if( ! this.isValidPlacement(placement) ) {
                console.error('Used "' + placement + "' placement is not valid.");
                return;
            }
            
            usedPlacement = placement;
            
            return this;
        },
        updateTasksList: function($tasksList) {
            $_tasksList = $tasksList;
            
            if( this.isBottom() ) {
                setupTasksListAsBottom($tasksList);
            }
            else if( this.isRight() ) {
                setupTasksListAsRight($tasksList);
            }
            else if( this.isCorner() ) {
                setupTasksListAsCorner($tasksList);
            }
        },
        slide: function() {
            var list = $_tasksList.closest('.task-list');
            
            if ( this.isBottom() ) {
                    var bottom = parseInt(list.css('bottom').replace('px', ''));
                    //hide
                    if (bottom === 0) {
                            _slide(list, 'bottom', bottom - list.height());
                    } else { //show
                            _slide(list, 'bottom', 0);
                    }
            }
            if ( this.isRight() ) {
                    var right = parseInt(list.css('right').replace('px', ''));
                    //hide
                    if (right === 0) {
                            _slide(list, 'right', right - list.width());
                    } else { //show
                            _slide(list, 'right', 0);
                    }
            }
            if ( this.isCorner() ) {
                    var right = parseInt(list.css('right').replace('px', '')),
                    bottom = parseInt(list.css('bottom').replace('px', ''));
                    //hide
                    if (right === 0) {
                            _slide(list, 'corner', [right - list.width() + 155, bottom - list.height()]); // 180
                    } else { //show
                            _slide(list, 'corner', [0, 0]);
                    }
            }
        }
    };
    
    return options;
})(jQuery, tto);