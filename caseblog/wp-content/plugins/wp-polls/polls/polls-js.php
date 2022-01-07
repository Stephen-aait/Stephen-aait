<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.1 Plugin: WP-Polls 2.14										|
|	Copyright (c) 2007 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://www.lesterchan.net													|
|																							|
|	File Information:																	|
|	- Polls Javascript File															|
|	- wp-content/plugins/polls/polls-js.js										|
|																							|
+----------------------------------------------------------------+
*/


### Include wp-config.php
@require('../../../wp-config.php');
cache_javascript_headers();

### Determine polls.php Path
$polls_ajax_url = dirname($_SERVER['PHP_SELF']);
if(substr($polls_ajax_url, -1) == '/') {
	$polls_ajax_url  = substr($polls_ajax_url, 0, -1);
}
?>

// Variables
var polls_ajax_url = "<?php echo $polls_ajax_url; ?>/polls.php";
var polls_text_wait = "<?php _e('Your last request is still being processed. Please wait a while ...', 'wp-polls'); ?>";
var polls_text_valid = "<?php _e('Please choose a valid poll answer.', 'wp-polls'); ?>";
var polls = new sack(polls_ajax_url);
var poll_id = 0;
var poll_answer_id = 0;
var poll_fadein_opacity = 0;
var poll_fadeout_opacity = 100;
var is_ie = (document.all && document.getElementById);
var is_moz = (!document.all && document.getElementById);
var is_opera = (navigator.userAgent.indexOf("Opera") > -1);
var is_being_voted = false;


// When User Vote For Poll
function poll_vote(current_poll_id) {
	if(!is_being_voted) {
		is_being_voted = true;
		poll_id = current_poll_id;
		poll_form = document.getElementById('polls_form_' + poll_id);
		poll_answer = eval("poll_form.poll_" + poll_id);
		poll_answer_id = 0;
		if(poll_answer.length != null) {
			for(i = 0; i < poll_answer.length; i++) {
				if (poll_answer[i].checked) {
					poll_answer_id = poll_answer[i].value;
				}
			}
		} else {
			poll_answer_id = poll_answer.value;
		}
		if(poll_answer_id > 0) {
			poll_loading_text();
			poll_process();
		} else {
			is_being_voted = false;
			alert(polls_text_valid);
		}
	} else {
		alert(polls_text_wait);
	}
}


// When User View Poll's Result
function poll_result(current_poll_id) {
	if(!is_being_voted) {
		is_being_voted = true;
		poll_id = current_poll_id;
		poll_loading_text();
		poll_process_result();
	} else {
		alert(polls_text_wait);
	}
}


// When User View Poll's Voting Booth
function poll_booth(current_poll_id) {
	if(!is_being_voted) {
		is_being_voted = true;
		poll_id = current_poll_id;
		poll_loading_text();
		poll_process_booth();
	} else {
		alert(polls_text_wait);
	}
}


// Poll Fade In Text
function poll_fadein_text() {
	if(poll_fadein_opacity == 90) {
		poll_unloading_text();
	}
	if(poll_fadein_opacity < 100) {
		poll_fadein_opacity += 10;
		if(is_opera) {
			poll_fadein_opacity = 100;
			poll_unloading_text();
		} else if(is_ie) {
			document.getElementById('polls-' + poll_id + '-ans').filters.alpha.opacity = poll_fadein_opacity;
		} else	 if(is_moz) {
			document.getElementById('polls-' + poll_id + '-ans').style.MozOpacity = (poll_fadein_opacity/100);
		}
		setTimeout("poll_fadein_text()", 100); 
	} else {
		poll_fadein_opacity = 100;
		is_being_voted = false;
	}
}


// Poll Loading Text
function poll_loading_text() {
	document.getElementById('polls-' + poll_id + '-loading').style.display = 'block';
}


// Poll Finish Loading Text
function poll_unloading_text() {
	document.getElementById('polls-' + poll_id + '-loading').style.display = 'none';
}


// Process The Poll
function poll_process() {
	if(poll_fadeout_opacity > 0) {
		poll_fadeout_opacity -= 10;
		if(is_opera) {
			poll_fadeout_opacity = 0;
		} else if(is_ie) {
			document.getElementById('polls-' + poll_id + '-ans').filters.alpha.opacity = poll_fadeout_opacity;
		} else if(is_moz) {
			document.getElementById('polls-' + poll_id + '-ans').style.MozOpacity = (poll_fadeout_opacity/100);
		}
		setTimeout("poll_process()", 100); 
	} else {
		poll_fadeout_opacity = 0;
		polls.reset();
		polls.setVar("vote", true);
		polls.setVar("poll_id", poll_id);
		polls.setVar("poll_" + poll_id, poll_answer_id);
		polls.method = 'POST';
		polls.element = 'polls-' + poll_id + '-ans';
		polls.onCompletion = poll_fadein_text;
		polls.runAJAX();
		poll_fadein_opacity = 0;
		poll_fadeout_opacity = 100;
	}
}


// Process Poll's Result
function poll_process_result() {
	if(poll_fadeout_opacity > 0) {
		poll_fadeout_opacity -= 10;
		if(is_opera) {
			poll_fadeout_opacity = 0;
		} else if(is_ie) {
			document.getElementById('polls-' + poll_id + '-ans').filters.alpha.opacity = poll_fadeout_opacity;
		} else if(is_moz) {
			document.getElementById('polls-' + poll_id + '-ans').style.MozOpacity = (poll_fadeout_opacity/100);
		}
		setTimeout("poll_process_result()", 100); 
	} else {
		poll_fadeout_opacity = 0;
		polls.reset();
		polls.setVar("pollresult", poll_id);
		polls.method = 'GET';
		polls.element = 'polls-' + poll_id + '-ans';
		polls.onCompletion = poll_fadein_text;
		polls.runAJAX();
		poll_fadein_opacity = 0;
		poll_fadeout_opacity = 100;
	}
}


// Process Poll's Voting Booth
function poll_process_booth() {
	if(poll_fadeout_opacity > 0) {
		poll_fadeout_opacity -= 10;
		if(is_opera) {
			poll_fadeout_opacity = 0;
		} else if(is_ie) {
			document.getElementById('polls-' + poll_id + '-ans').filters.alpha.opacity = poll_fadeout_opacity;
		} else if(is_moz) {
			document.getElementById('polls-' + poll_id + '-ans').style.MozOpacity = (poll_fadeout_opacity/100);
		}
		setTimeout("poll_process_booth()", 100); 
	} else {
		poll_fadeout_opacity = 0;
		polls.reset();
		polls.setVar("pollbooth", poll_id);
		polls.method = 'GET';
		polls.element = 'polls-' + poll_id + '-ans';
		polls.onCompletion = poll_fadein_text;
		polls.runAJAX();
		poll_fadein_opacity = 0;
		poll_fadeout_opacity = 100;
	}
}