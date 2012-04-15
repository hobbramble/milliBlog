<?php
/* milliBlog login handler
 * Copyright (c) 2011-2012 Troy Martin. All rights reserved.
 *
 * milliBlog is open source software. By downloading this software, you agree
 * to abide by the terms and conditions set forth in this license.
 *
 * You may modify the source code and distribute instructions to modify it,
 * but you may NOT distribute the source code or the software itself, modified
 * or unmodified, without written permission from the author(s). In addition,
 * you may not include or use milliBlog code, modified or unmodified, in your
 * own project(s) without written permission from the author(s). 
 *
 * You may not modify or remove the displayed milliBlog copyright notices in
 * the source code of the software, on a milliBlog itself, or anywhere else
 * where a copyright notice has been placed.
 *
 * milliBlog comes with no warranty. You are responsible for any physical or
 * electronic damages caused by using and/or modifying (including any form of
 * database modifications) the software. The author(s) of the software cannot
 * be held responsible for any damages caused by the end user of the software.
 */

session_start();
require_once("blog_config.php");

// Connect to the server and select the DB
//mysql_connect("$blogdb_host", "$blogdb_username", "$blogdb_password") or die("Deathly Fatal Error! Cannot connect to the database."); 
//mysql_select_db("$blogdb_name")or die("Deathly Fatal Error! Cannot select the database.");

// Retrieve crap.
$myusername = $_POST['myusername']; 
$mypassword = $_POST['mypassword'];
$myip = $_SERVER['REMOTE_ADDR'];

if (!$myusername || !$mypassword)
{
    /*header("Location: fail.php?t=login") or */die("Deathly Fatal Error! Login credentials not supplied.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>");
}

// Kill the wonder of SQL injection
//$myusername = stripslashes($myusername);
//$mypassword = stripslashes($mypassword);
//$myusername = mysql_real_escape_string($myusername);
//$mypassword = mysql_real_escape_string($mypassword);

// SHA-1 that bugger
$mypassword = sha1($mypassword);

if (!isset($blogpass[$myusername]))
{
    /*header("Location: fail.php?t=loginu") or */die("Deathly Fatal Error! Incorrect username.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>");
}

if ($blogpass[$myusername] != $mypassword)
{
    /*header("Location: fail.php?t=loginp") or */die("Deathly Fatal Error! Incorrect password.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>");
}

session_register("myusername");
session_register("mypassword");
header("Location: index.php");
?>