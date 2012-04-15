<?php
/* milliBlog post deletion
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
mysql_connect($blogdb_host, $blogdb_username, $blogdb_password) or die("Deathly Fatal Error! Cannot connect to the database.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>"); 
mysql_select_db($blogdb_name)or die("Deathly Fatal Error! Cannot select the database.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>");

// Retrieve crap.
$mypost = $_GET['id'];

if (!session_is_registered(myusername))
{
    /*header("Location: fail.php?t=login") or */die("Deathly Fatal Error! Login credentials not supplied.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>");
}

if (!$mypost)
{
    die("Deathly Fatal Error! Was not supplied with an ID to delete.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>");
}/*header("Location: fail.php?t=delid") or */

// Kill the wonder of SQL injection
$myusername = stripslashes($myusername);
$mypost = stripslashes($mypost);
$myusername = mysql_real_escape_string($myusername);
$mypost = mysql_real_escape_string($mypost);

$sql="SELECT * FROM milliblog_posts WHERE id=$mypost";
$result=mysql_query($sql);

if (!mysql_fetch_array($result))
{
    /*header("Location: fail.php?t=delid") or */die("Deathly Fatal Error! Could not find post with id $mypost to delete.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>");
}

$sql="DELETE FROM milliblog_posts WHERE id=$mypost";
$result=mysql_query($sql);

header("Location: index.php");
?>