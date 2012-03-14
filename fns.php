<?
include_once "md/markdown.php";
////////////////////////////////
// taglinks($folders)
// generates array of all tags used
// in a given folder with links to
// their parent items
//
// returns:
//  $array of tags
//      -> array('home', 'name')
////////////////////////////////////
function taglinks($folder_in) { 
    $folderlist = $folder_in; 
    $taglist = array();

    foreach($folderlist as $folder) {

        if ($handle = opendir($folder)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $temp['name'] = $entry;
                    $temp['home'] = $folder;
                    $temp['raw'] = file_get_contents($folder.'/'.$entry);
                    ///////////////////////////
                    // load element title
                    $tok = strtok($temp['raw'], "^^^");
                    $temp['title'] = $tok;

                    // load element body
                    $tok = strtok("^^^");
                    $temp['body'] = Markdown($tok);
                    $temp['body'] = strip_tags($temp['body']);
                    $temp['body'] = substr($temp['body'],0,250);

                    // load element tags
                    $tok = strtok("^^^");
                    $temp['tags_raw'] = $tok;
    /*
                    // is presorted?
                    if($presorted==1) {
                        $tok = strtok("^^^");
                        $temp['presort'] = $tok;
                    }
                    ///////////////////////////
    */
                    // parse the tags
                    $tok = strtok($temp['tags_raw'], ", ");
                    while ($tok !== false) {
                        $taglist[$tok][] = array(
                            'home' => $temp['home'],
                            'name' => $temp['name'],
                            'title' => $temp['title'],
                            'preview' => $temp['body']
                            );
                        $tok = strtok(", ");
                    }
                }
            }
            closedir($handle);
        }

    }

    return $taglist;

}   // /function


/////////////////////////////////////
// pagetimer()
// returns the time the function was
// called
/////////////////////////////////////
function pagetimer()
{
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    return $mtime;
}

/////////////////////////////////////
// pagetimer_done($start)
// returns the difference between the
// time the function was called and
// the time passed into it
/////////////////////////////////////
function pagetimer_done($start)
{
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $outtime = $mtime - $start;

    $outstr = sprintf("%0.3f",$outtime);

    return $outstr;
}


/////////////////////////////////////
// getpage($folder, $entry)
// grab a page from a file named $entry
// from a folder $folder
// 
// returns an array containing single page information
/////////////////////////////////////

function getpage($folder, $entry)
{
    $returnme['raw'] = @file_get_contents($folder.'/'.$entry);
    
    if($returnme['raw'] == FALSE)
        return get404();

    ///////////////////////////
    // load element title
    $tok = strtok($returnme['raw'], "^^^");
    $returnme['title'] = $tok;

    // load element body
    $tok = strtok("^^^");
    $returnme['body_raw'] = $tok;
    $returnme['body'] = Markdown($returnme['body_raw']);

    // load element tags
    $tok = strtok("^^^");
    $returnme['tags_raw'] = $tok;
    ///////////////////////////

    // parse the tags
    $tagitr = 0;
    $tok = strtok($returnme['tags_raw'], ", ");
    while ($tok !== false) {
        $returnme['tags'][$tagitr] = $tok;
        $tok = strtok(", "); $tagitr++;
    }

    return $returnme;
}


/////////////////////////////////////
// gentags($tag_arr)
// generates "tag listing" from a string of tags in format
// "first, second item, third, fourth item, fith item, sixth"
// and so on.
// 
// returns an array
/////////////////////////////////////
function gentags($tag_arr)
{
    $returnMe = "<span class='tags'>";

    if($tag_arr != NULL) {
        $returnMe .= "<b>tags: </b>";
        foreach($tag_arr as $tag) {
            //$returnMe .= "<i><a href='tags.php?t=".trim($tag)."'>".trim($tag)."</a>,</i> ";
            $returnMe .= "<i><a href='/tags-".trim($tag).".html'>".trim($tag)."</a>,</i> ";
        }
    }
    $returnMe .= "</span>";

     return $returnMe;
}


function get404()
{

    $page['title'] = "404 Not Found";
    $page['body'] = "Nothing was found here. Head back to the <a href='/'>front page</a>";
    $page['tags'] = NULL;

    return $page;
}

function genlink($arr)
{
    return "<a href='/".$arr['home']."/".$arr['name'].".html'>";
    //return "<a href='page.php?f=".$arr['home']."&e=".$arr['name']."'>";
}

/////////////////////////////////////
// genfpblock(array, int, string, int, boolean)
// does a "front page" block, grabbing the first few elements from
// an array gotten from a getfolder
//
// returns a string with html elements
/////////////////////////////////////
function genfpblock($arr, $size, $title, $NUM_ELEM, $dotags)
{
    $returnMe = ""; //"<div id='".$title."'>\r\n";

//  $returnMe .= "<pre>counting from ".($size-1)." down to ".($size-$NUM_ELEM)."</pre>";
    if($NUM_ELEM >  $size-1) {
        $NUM_ELEM = $size;
//      $returnMe .= "<pre>set to match. now counting from ".($size-1)." down to ".($size-$NUM_ELEM)."</pre>";
    } 

    // print out blog entries
    for($itr = $size-1; $itr >= $size-$NUM_ELEM; $itr--) {
        $returnMe .= "\t<div class='entry'>\r\n";
        $returnMe .= "\t\t<!-- -->\r\n";
        if($dotags % 2 == 1)
            $returnMe .= "\t\t<h3>".genlink($arr[$itr]).trim($arr[$itr]['title'])."</a></h3>\r\n"; // titles
        else
            $returnMe .= "\t\t<h3>".trim($arr[$itr]['title'])."</h3>\r\n"; // titles
        $returnMe .= "\t\t<p>".trim($arr[$itr]['body'])."</p>\r\n";    // body
        if(isset($arr[$itr]['tags']) and $dotags) {
            $returnMe .= "\t\t" . gentags($arr[$itr]['tags']) . "\r\n";// tags
        }
        $returnMe .= "\t</div>\r\n";
    }

    $returnMe .= ""; // "</div>";

    return $returnMe;
}

/////////////////////////////////////
// getfolder(string, bool)
// looks in a folder and brings everything in it into an array
// each file is token delimited with "^^^" (no quotes)
// with items in each order:
// title
// body
// tags (comma delimited)
// forced sort (optional)
//
// returns an array
/////////////////////////////////////
function getfolder($folder, $presorted)
{
    $itr = 0; $returnme;

    if ($handle = opendir($folder)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $returnme[$itr]['name'] = $entry;
                $returnme[$itr]['home'] = $folder;
                $returnme[$itr]['raw'] = file_get_contents($folder.'/'.$entry);

                ///////////////////////////
                // load element title
                $tok = strtok($returnme[$itr]['raw'], "^^^");
                $returnme[$itr]['title'] = $tok;
                
                // load element body
                $tok = strtok("^^^");
                $returnme[$itr]['body_raw'] = $tok;
                $returnme[$itr]['body'] = Markdown($returnme[$itr]['body_raw']);

                // load element tags
                $tok = strtok("^^^");
                $returnme[$itr]['tags_raw'] = $tok;

                // is presorted?
                if($presorted==1) {
                    $tok = strtok("^^^");
                    $returnme[$itr]['presort'] = $tok;
                }
                ///////////////////////////

                // parse the tags
                $tagitr = 0;
                $tok = strtok($returnme[$itr]['tags_raw'], ", ");
                while ($tok !== false) {
                    $returnme[$itr]['tags'][$tagitr] = $tok;
                    $tok = strtok(", "); $tagitr++;
                }
                $itr++;
            }
        }
        closedir($handle);

        // is everything pre-sorted?
        if($presorted == 1) {
//            print "<pre>".print_r($returnme)."</pre>";
            while($itr > 0) {
                $itr--;
                $value = intval($returnme[$itr]['presort']);
//                print "[itr:".$itr ."]:[val:".$value."]  ";
                $sorted[$value] = $returnme[$itr];
            }
            return $sorted;
        }
        else
            return $returnme;

    } else
        return null;
}
?>
