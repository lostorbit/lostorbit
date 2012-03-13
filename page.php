<?
/////////////////////////////////////
// page.php
// steve massey
//
// lostorbit.net page file
///////////////////////////////////// 

ini_set('display_errors', 1);

// bring in our functions
require_once("fns.php");

// find out how long it takes to make this page
$starttime = pagetimer();

// bring in our data
// getfolder(DIRECTORY) returns an array per-item with
//  title, body, tags (array)

if(isset($_GET['f']) && isset($_GET['e'])) {
    $page = getpage($_GET['f'], $_GET['e']);
} else {
    $page = get404();
}
?>

<html>
    <title>lostorbit.net</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <style>
        body {background-color:#000;  background-image:url('images/rings1.jpg'); background-repeat:repeat-x; }
    </style>
<body>

<div id="content">
    <div id="topleft">
        <a href="./">back to home</a>
    </div>

    <h2><? print $page['title']; ?></h2>

    <div id="head">
        <? print $page['body']; ?>
    </div>

    <div id="tags">
        <? print gentags($page['tags']); ?>
    </div>
    <p style="genstat">
        page generated in <? echo pagetimer_done($starttime); ?> seconds.
    </p>
</div>


<?

if(isset($_GET['debug'])) {
    echo "<hr /><h3>debug out</h3><h4>page</h4><pre>";
    print_r($page);
    echo "</pre>";
}
?>
</body></html>
