<?
/////////////////////////////////////
// tags.php
// steve massey
//
// lostorbit.net page file
///////////////////////////////////// 

ini_set('display_errors', 1);

// bring in our functions
require_once("fns.php");

// find out how long it takes to make this page
$starttime = pagetimer();

// grab all the tags
$alltags = taglinks( array('blog'));

// are we showing all the tags or just the one selected?
if(isset($_GET['t']))
    $page = $_GET['t'];
else
    $page = NULL;
?>

<html>
    <head>
        <title>lostorbit.net</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
        <style>
            body {background-color:#000;  background-image:url('images/rings1.jpg'); background-repeat:repeat-x; }
        </style>
        <script type="text/javascript">

          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-3496022-1']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

        </script>


    </head>
<body>

<div id="content">
    <div id="topleft">
        <a href="./">back to home</a> - <a href="./tags.php">see all tags</a>
    </div>

    <h2>Tags</h2>

    <div id="head">
        Below is an index sorted by taggings.
    </div>

    <div id="tagpage">
<?
    if(isset($alltags[$page])) {
        echo "<h3 style='border-bottom:1px solid #555;'>".$page."</h3>";
        foreach($alltags[$page] as $tag) {
            echo "<p><a href='page.php?f=".$tag['home']."&e=".$tag['name']."'><b>".$tag['title']."</b></a>"; // is ".$tag['name']." @ ".$tag['home']."</p>";
            echo "<p>".$tag['preview']."...</p>";
        }
    } else {
        $tagnames = array_keys($alltags);
        $itr=0;
        foreach($alltags as $tag) {
            echo "<h3 style='border-bottom:1px solid #555;'>".$tagnames[$itr]."</h3>"; $itr++;
            foreach($tag as $page) {
                echo "<p><b>".$page['title']."</b> is ".$page['name']." @ ".$page['home']."</p>";
                echo "<p>".$page['preview']."...</p>";
            }
        }
        // end default
    }
?>
    </div>

    <p class="genstat">
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
