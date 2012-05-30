<?php
/* milliBlog
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
 *
 * milliBlog RSS Feed mod by Patrick J. B. Simmons
 * http://alternative-internet.com/pjbs/
 *
 */
header("Content-Type: application/rss+xml");

$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
  === FALSE ? 'http' : 'https';
$host     = $_SERVER['HTTP_HOST'];
$directory = $_SERVER['SCRIPT_NAME'];
$siteurl = $protocol . '://' . $host . $directory;

$path_parts = pathinfo($siteurl);

session_start();
if (!file_exists("blog_config.php")) {
    die("
<rss version=\"2.0\">
    <channel>
    <title>Deathly Fatal Error!</title>
    <link>{$path_parts['dirname']}/</link>
    <description>The milliBlog-powered site associated with this feed has not been configured properly.</description>
    </channel>");
}
require_once("blog_config.php");
if (!$milliblog_installed) {
    die("
<rss version=\"2.0\">
    <channel>
    <title>Deathly Fatal Error!</title>
    <link>{$path_parts['dirname']}/</link>
    <description>The milliBlog-powered site associated with this feed has not been configured properly.</description>
    </channel>");
}

$use_textile = false;

echo "
      <rss version=\"2.0\">
      <channel>
      <title>$bloggen_title</title>
      <link>{$path_parts['dirname']}/</link>
      <description>$bloggen_subtitle</description>
      <language>en-us</language>
      <generator>milliBlog $milliblog_version</generator>
      ";

mysql_connect($blogdb_host, $blogdb_username, $blogdb_password) or die("
<rss version=\"2.0\">
    <channel>
    <title>Deathly Fatal Error!</title>
    <link>{$path_parts['dirname']}/</link>
    <description>The milliBlog-powered site associated with this feed has not been configured properly.</description>
    </channel>"); 
mysql_select_db($blogdb_name)or die("
<rss version=\"2.0\">
    <channel>
    <title>Deathly Fatal Error!</title>
    <link>{$path_parts['dirname']}/</link>
    <description>The milliBlog-powered site associated with this feed has not been configured properly.</description>
    </channel>");

// Get number of rows in post table
$sql = "SELECT  COUNT(*) FROM milliblog_posts";
$result = mysql_query($sql);
$r = mysql_fetch_row($result);
$numrows = $r[0];
$range = 3; // Number of pages linked to at a time.
$totalpages = ceil($numrows / $bloggen_postspage);
$listpage = 1;
// List offset
$offset = ($listpage - 1) * $bloggen_postspage;

$sql = "SELECT * FROM milliblog_posts ORDER BY id DESC LIMIT $offset, $bloggen_postspage";
$result = mysql_query($sql);

while (($post = mysql_fetch_array($result)) != FALSE) {
    $posttime = date('F jS, Y, H:i T', $post['time']);
    $postpage = $post['time'];
    $postbody = $post['post'];
    $pubdate = date('D, d M Y H:i:s O', $post['time']);
    $authormail = $blogmail[$post['name']];
    $authorname = $blogreal[$post['name']];
    if ($use_textile) {
        $textile = new Textile();
        $postbody = $textile->TextileThis($postbody);
    }
    echo "<item>
             <title>$posttime</title>
             <link>{$path_parts['dirname']}/index.php?pg=$postpage</link>
             <description>$postbody</description>
             <pubDate>$pubdate</pubDate>
             <guid>{$path_parts['dirname']}/index.php?pg=$postpage</guid>
             <author>$authormail ($authorname)</author>
           </item>";
           }
?>
   </channel>
   </rss>