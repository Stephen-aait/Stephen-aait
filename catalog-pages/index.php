<?php

if ($handle = opendir('.')) {

    while (false !== ($entry = readdir($handle))) {
        if(preg_match('!\.(jpg|png|jpeg)$!i',$entry)) {
            echo "<img src=\"$entry\"><br><br>\n";
        }
    }
closedir($handle);
}

if ($handle = opendir('.')) {

    while (false !== ($entry = readdir($handle))) {
        if(preg_match('!\.(jpg|png|jpeg)$!i',$entry)) {
            echo '[img]http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' . $entry . "[/img]<br>\n";
        }
    }
closedir($handle);
}