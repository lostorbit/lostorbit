<?
/////////////////////////////////////
// index.html
// steve massey
//
// lostorbit.net front page demo file
///////////////////////////////////// 

ini_set('display_errors', 1);

// bring in our functions
require_once("fns.php");

// find out how long it takes to make this page
$starttime = pagetimer();

// bring in our data
// getfolder(DIRECTORY) returns an array per-item with
//  title, body, tags (array)
$blogs = getfolder('blog',1);
$projects = getfolder('project',1);
$sizeof['blogs'] = sizeof($blogs);
$sizeof['projects'] = sizeof($projects);
?>

<html>
    <head>
        <title>lostorbit.net</title>
        <link href="style.css" rel="stylesheet" type="text/css" />

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

    <h1>Steve Massey</h1>

    <div id="head">
        <p>Hi. I'm Steve Massey, a senior in Computer Engineering and Computer
        Engineering at <a href="http://parks.slu.edu">Parks College</a> at
        Saint Louis University. I'm a programmer, an engineer-in-training, and
        super interested in outer space. I also enjoy
        <!--<a href="books.html">-->reading<!--</a>-->, biking, swing dancing,
        and exploring the wide world of coffee!
        </p> 
    </div>

    <div id="projs">
        <h2>Projects</h2>
    <? print genfpblock($projects, $sizeof['projects'], 'projects', 3, 2); ?>
    </div>

    <div id="blogs">
        <h2>Blogs</h2>
    <? print genfpblock($blogs, $sizeof['blogs'], 'blogs', 5, 1); ?>

        <div class="entry"><p>
        For older entries, you can <a href="tags.html">browse the archive</a>
        sorted by taggings.
        </p></div>
    </div>

    <div id="links">
        <h2>Links</h2>
        <div><a href="resume-long.pdf">resume</a></div>
        <div><a href="http://www.linkedin.com/in/lostorbit">linkedin</a></div>
        <div><a href="http://www.twitter.com/orbitlost">twitter</a></div>
        <div><a href="http://www.facebook.com/lostorbit">facebook</a></div>
    </div>
    <p class="genstat">
        page generated in <? echo pagetimer_done($starttime); ?> seconds.
    </p>
</div>


<?

if(isset($_GET['debug'])) {
    echo "<hr /><h3>debug out</h3><h4>blogs</h4><pre>";
    print_r($blogs);
    echo "</pre><hr /><h4>projects</h4><pre>";
    print_r($projects);
    echo "</pre>";
}
?>
</body></html>
