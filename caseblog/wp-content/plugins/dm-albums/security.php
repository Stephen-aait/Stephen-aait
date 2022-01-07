<?php 
global $userdata;
global $current_user;
global $user_level;
global $CAPTION_EDITORS;

// If it's not defined, define it
if(!isset($user_level))	$user_level = 0;
if(empty($CAPTION_EDITORS))	$CAPTION_EDITORS = 2;

// If the function get_currentuserinfo is available, use it to get user level
if(function_exists("get_currentuserinfo"))	get_currentuserinfo();
$user_level = $current_user->user_level;

$PARENT_IS_WRITABLE = false;

if(function_exists("current_user_can"))
{
	//if($user_level >= $CAPTION_EDITORS) 
	if(current_user_can('upload_files'))
	{
		$PARENT_IS_WRITABLE = true;
	}
}
?>