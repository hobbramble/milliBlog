<?php
/* milliBlog installer
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

$version = "0.20120420";

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type" />
<link rel="stylesheet" type="text/css" href="themes/default.css" />
<title>milliBlog installer</title>
</head>

<body>
<body>
<div id="content">
<div class="header">
<h1 class="title"><span class="title">milliBlog installer</span></h1>
<h3 class="subtitle"><span class="subtitle">version <?php echo $version ?> - requires PHP 5</span></h3>
</div>

<hr class="big" />

<?php

if (file_exists("blog_config.php"))
    die ("Deathly Fatal Error! milliBlog has already been installed and configured.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> (&copy; 2011-2012 Troy Martin).</h6>");
    
if (!isset($_POST['install']))
{
?>

<form name="installer" method="post">
<h3 class="subtitle">Database configuration</h3>
<p>Please note that the database described here must have already been created and a username and password must be supplied with all permissions for that database.</p>
<table border="0">
<tr><td>Database host:</td><td><input type="text" name="db_host" value="localhost" size="50"></td></tr>
<tr><td>Database name:</td><td><input type="text" name="db_name" value="" size="30"></td></tr>
<tr><td>Database username:</td><td><input type="text" name="db_username" value="" size="30"></td></tr>
<tr><td>Database password:</td><td><input type="password" name="db_password" value="" size="30"></td></tr>
</table>

<hr class="small" />
<h3 class="subtitle">User configuration</h3>
<p>This is your login and extended user information. You can later create more users in the blog configuration file.</p>
<table border="0">
<tr><td>User username:</td><td><input type="text" name="user_username" value="" size="30"></td></tr>
<tr><td>User password:</td><td><input type="password" name="user_password" value="" size="30"></td></tr>
<tr><td>Confirm password:</td><td><input type="password" name="user_confirm_password" value="" size="30"></td></tr>
<tr><td>User real name (optional):</td><td><input type="text" name="user_realname" value="" size="50"></td></tr>
<tr><td>User email address (optional):</td><td><input type="text" name="user_email" value="" size="50"></td></tr>
</table>

<hr class="small" />
<h3 class="subtitle">milliBlog configuration</h3>
<p>This is the name and information about your milliBlog.</p>
<table border="0">
<tr><td>milliBlog name:</td><td><input type="text" name="gen_title" value="My milliBlog" size="30"></td></tr>
<tr><td>milliBlog subtitle (optional):</td><td><input type="text" name="gen_subtitle" value="" size="50"></td></tr>
</table>

<hr class="small" />
<textarea rows="10" readonly="true" style="width: 794px; min-width: 794px; max-width: 794px;"><?php readfile("license.txt"); ?></textarea>
I hereby agree to abide by the milliBlog license: <input type="checkbox" name="license_agree"><br />
<input type="submit" name="install" value="Install">
</form>
<p />
<p />
<h6 style="text-align: center;">Powered by <a href="http://www.milliblog.org/">milliBlog</a> (&copy; 2011 Troy Martin).</h6> 

<?php
die();
}

else
{
    $installerror = false;
    if (!($_POST['db_host']) ||
        !($_POST['db_name']) ||
        !($_POST['db_username']) ||
        !($_POST['db_password']) ||
        !($_POST['user_username']) ||
        !($_POST['user_password']) ||
        !($_POST['user_confirm_password']) ||
        !($_POST['gen_title']))
    {
        echo '<div class="error">Required field(s) missing.</div>';
        $installerror = true;
    }
    
    if ($_POST['user_password'] != $_POST['user_confirm_password'])
    {
        echo '<div class="error">User password and confirmation did not match.</div>';
        $installerror = true;
    }
    
    if (!isset($_POST['license_agree']))
    {
        echo '<div class="error">You must agree to the milliBlog license to use milliBlog.</div>';
        $installerror = true;
    }
    
    if ($installerror)
    {
        echo '<form name="installer" method="post">
<h3 class="subtitle">Database configuration</h3>
<p>Please note that the database described here must have already been created and a username and password must be supplied with all permissions for that database.</p>
<table border="0">
<tr><td>Database host:</td><td><input type="text" name="db_host" value="'.$_POST['db_host'].'" size="50"></td></tr>
<tr><td>Database name:</td><td><input type="text" name="db_name" value="'.$_POST['db_name'].'" size="30"></td></tr>
<tr><td>Database username:</td><td><input type="text" name="db_username" value="'.$_POST['db_username'].'" size="30"></td></tr>
<tr><td>Database password:</td><td><input type="password" name="db_password" value="" size="30"></td></tr>
</table>

<hr class="small" />
<h3 class="subtitle">User configuration</h3>
<p>This is your login and extended user information. You can later create more users in the blog configuration file.</p>
<table border="0">
<tr><td>User username:</td><td><input type="text" name="user_username" value="'.$_POST['user_username'].'" size="30"></td></tr>
<tr><td>User password:</td><td><input type="password" name="user_password" value="" size="30"></td></tr>
<tr><td>Confirm password:</td><td><input type="password" name="user_confirm_password" value="" size="30"></td></tr>
<tr><td>User real name (optional):</td><td><input type="text" name="user_realname" value="'.$_POST['user_realname'].'" size="50"></td></tr>
<tr><td>User email address (optional):</td><td><input type="text" name="user_email" value="'.$_POST['user_email'].'" size="50"></td></tr>
</table>

<hr class="small" />
<h3 class="subtitle">milliBlog configuration</h3>
<p>This is the name and information about your milliBlog.</p>
<table border="0">
<tr><td>milliBlog name:</td><td><input type="text" name="gen_title" value="'.$_POST['gen_title'].'" size="30"></td></tr>
<tr><td>milliBlog subtitle (optional):</td><td><input type="text" name="gen_subtitle" value="'.$_POST['gen_subtitle'].'" size="50"></td></tr>
</table>

<hr class="small" />
<textarea rows="10" readonly="true" style="width: 794px; min-width: 794px; max-width: 794px;">';
        readfile("license.txt");
        echo '</textarea>
I hereby agree to abide by the milliBlog license: <input type="checkbox" name="license_agree">
<input type="submit" name="install" value="Install">
</form>
<p />
<p />
<h6 style="text-align: center;">Powered by <a href="http://www.milliblog.org/">milliBlog</a> (&copy; 2011 Troy Martin).</h6>';
        die();
    }
    
    mysql_connect(stripslashes($_POST['db_host']), stripslashes($_POST['db_username']), stripslashes($_POST['db_password'])) or die("Deathly Fatal Error! Cannot connect to the database.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> (&copy; 2011-2012 Troy Martin).</h6>"); 
    mysql_select_db(stripslashes($_POST['db_name'])) or die("Deathly Fatal Error! Cannot select the database.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> (&copy; 2011-2012 Troy Martin).</h6>");
    
    $sql = "CREATE TABLE milliblog_posts
            (
            id int AUTO_INCREMENT,
            PRIMARY KEY(id),
            name varchar(64),
            post longtext,
            time int
            )";
    mysql_query($sql) or die ("Deathly Fatal Error! Cannot create table milliblog_posts.<p />" . mysql_error() . "<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> (&copy; 2011-2012 Troy Martin).</h6>");
    
    $configfile = fopen("blog_config.php","w") or die("Deadly Fatal Error! Can't open blog_config.php for writing.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> (&copy; 2011-2012 Troy Martin).</h6>");
    fwrite($configfile, "<?php
/* milliBlog configuration file
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

// Not settings - do not change!
\$milliblog_copyright = \"milliBlog\";
\$milliblog_version   = \"$version\";
\$milliblog_installed = true;

// Blog database
\$blogdb_host        = \"$_POST[db_host]\";
\$blogdb_name        = \"$_POST[db_name]\";
\$blogdb_username    = \"$_POST[db_username]\";
\$blogdb_password    = \"$_POST[db_password]\";

// User info
\$blogpass['$_POST[user_username]'] = \"" . sha1($_POST['user_password']) . "\";
\$blogname['$_POST[user_username]'] = \"$_POST[user_realname]\";
\$blogmail['$_POST[user_username]'] = \"$_POST[user_email]\";

// General settings
\$bloggen_title      = \"$_POST[gen_title]\";
\$bloggen_subtitle   = \"$_POST[gen_subtitle]\";
\$bloggen_showemail  = true;
\$bloggen_theme      = \"default\";
\$bloggen_postspage  = 10;

?>");
    fclose($configfile);
    
?>

<h3 class="subtitle">Congratulations!</h3>
<p>milliBlog has successfully been installed and configured for use. Start your milliBlog by logging in <a href="index.php">at its main page</a>!</p>

<p />
<p />
<h6 style="text-align: center;">Powered by <a href="http://www.milliblog.org/">milliBlog</a> <?php echo $version; ?> (&copy; 2011-2012 Troy Martin).</h6>

<?php
}
die();
?>
