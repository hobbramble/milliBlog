<?php
/* milliBlog main page
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

if (!file_exists("blog_config.php"))
    die("Deathly Fatal Error! milliBlog has not been configured properly. Please follow the instructions in the readme.txt file that accompanied the milliBlog files.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>");

require_once("blog_config.php");

if (!$milliblog_installed)
    die("Deathly Fatal Error! milliBlog has not been configured properly. Please follow the instructions in the readme.txt file that accompanied the milliBlog files.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>");

$use_textile = true;

if (file_exists("classTextile.php"))
    include_once("classTextile.php");
else
    $use_textile = false;
    
if (isset($_GET['logout']))
{
    $_SESSION = array();

    if (ini_get("session.use_cookies"))
    {
        $scookie = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $scookie["path"], $scookie["domain"], $scookie["secure"], $scookie["httponly"]);
    }
    
    session_destroy();
    session_start();
}

echo "<!doctype html> <!-- HTML5 standards mode -->
     <html xmlns=\"http://www.w3.org/1999/xhtml\">
     <head>
     <meta content=\"text/html; charset=ISO-8859-1\" http-equiv=\"content-type\" />
     <link rel=\"stylesheet\" type=\"text/css\" href=\"themes/$bloggen_theme.css\" />
     <link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"rssfeed.php\" />
     <title>$bloggen_title</title>
     </head>";
     
?>

<body>
<div id="content">
<div class="header">
<h1 class="title"><span class="title"><?php echo $bloggen_title; ?></span></h1>
<h3 class="subtitle"><span class="subtitle"><?php echo $bloggen_subtitle; ?></span></h3>
</div>

<hr class="big" />

<?php

if (!session_is_registered(myusername))
{
    echo '<div class="loginform" style="text-align:right;"><form name="loginform" method="post" action="/dologin.php">Username: <input name="myusername" type="text" id="myusername"> Password: <input name="mypassword" type="password" id="mypassword"> <input type="submit" name="submit" value="Login"></form></div>';
}
else
{
    echo '<div class="postform" style="text-align:right;"><form name="postform" method="post" action="/dopost.php"><textarea name="postbody" rows="3" columns="40" id="postbody"></textarea><br /><sup><a href="http://textile.thresholdstate.com/" target="_blank" title="includes sandbox (opens in new tab)">Formatting reference</a> - <input type="submit" name="submit" value="Post"></sup></form><br /><div class="loggedoptions"><sup style="text-align:right;"><a href="index.php?logout=true">Logout</a></sup></div></div>';
}
?>

<hr class="big" />

<?php

mysql_connect($blogdb_host, $blogdb_username, $blogdb_password) or die("Deathly Fatal Error! Cannot connect to the database.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>"); 
mysql_select_db($blogdb_name)or die("Deathly Fatal Error! Cannot select the database.<p /><p /><h6 style=\"text-align: center;\">Powered by <a href=\"http://www.milliblog.org/\">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6>");

if (isset($_GET['pg']))
{
    $pg = (int) $_GET['pg'];
}

// Get number of rows in post table
$sql = "SELECT  COUNT(*) FROM milliblog_posts";
$result = mysql_query($sql);
$r = mysql_fetch_row($result);
$numrows = $r[0];
$range = 3; // Number of pages linked to at a time.
$totalpages = ceil($numrows / $bloggen_postspage);
// Get the current page or display the front page
if (isset($_GET['listpage']) && is_numeric($_GET['listpage']))
{
    $listpage = (int) $_GET['listpage'];
} 
else 
{
    $listpage = 1;
}
if ($listpage > $totalpages)
{
    $listpage = $totalpages;
}
if ($listpage < 1)
{
    $listpage = 1;
}
$offset = ($listpage - 1) * $bloggen_postspage;

if ($pg)
{
    $sql = "SELECT * FROM milliblog_posts WHERE time=$pg LIMIT 1"; // OFFSET $startint LIMIT 15";
    $result = mysql_query($sql);
}
else {
    $sql = "SELECT * FROM milliblog_posts ORDER BY id DESC LIMIT $offset, $bloggen_postspage";
    $result = mysql_query($sql);
}

while (($post = mysql_fetch_array($result)) != FALSE)
{
    $posttime = date(DATE_RSS, $post['time']);
    $postbody = $post['post'];
    $postpage = $post['time'];
    
    if ($use_textile)
    {
        $textile = new Textile();
        $postbody = $textile->TextileThis($postbody);
    }
    
    echo "<div class=\"post\"><div class=\"postbody\">$postbody</div>
          <p />
          <div class=\"postoptions\"><sup>Posted  <a href='{$_SERVER['PHP_SELF']}?pg=$postpage'>$posttime</a> by " . $blogreal[$post['name']];
    
    if (session_is_registered(myusername))
    {
        echo " (" . $post['name'] . ") - <a href=\"delpost.php?id=" . $post['id'] . "\">Delete</a>";
    }
    
    echo "</sup></div></div><hr />";
}
// Show links to the previous and first page.
if ($listpage > 1)
{
    echo "<a href='{$_SERVER['PHP_SELF']}?listpage=1'>First</a> | ";
    $prevpage = $listpage - 1;
    echo "<a href='{$_SERVER['PHP_SELF']}?listpage=$prevpage'>Previous</a> | ";
}
else
{
    echo "First | Previous | "; // Show plain text instead of links in on the first page.
}
// Determine the current page number and the page numbers to show around it based on &range
for ($x = ($listpage - $range); $x < (($listpage + $range) + 1); $x++)
{
    if (($x > 0) && ($x <= $totalpages))
    {
       if ($x == $listpage)
       {
          echo " <b>$x</b> ";
       }
       else
       {
          echo " <a href='{$_SERVER['PHP_SELF']}?listpage=$x'>$x</a> ";
       }
    }
}
// Show links to the next and last page.
if ($listpage != $totalpages) 
{
    $nextpage = $listpage + 1;
    echo " | <a href='{$_SERVER['PHP_SELF']}?listpage=$nextpage'>Next</a> | ";
    echo "<a href='{$_SERVER['PHP_SELF']}?listpage=$totalpages'>Last</a>";
}
else 
{
    echo " | Next | Last"; // Show plain text instead of links in on the last page.
}
?>
&nbsp;&nbsp;<a href="rssfeed.php"><img src="rssicon.png" alt="RSS"></a>
<p />
<p />
<h6 style="text-align: center;">Powered by <a href="http://www.milliblog.org/">milliBlog</a> <?php echo $milliblog_version; ?> (&copy; 2011-2012 Troy Martin).</h6> 

</div>
</body>

</html>