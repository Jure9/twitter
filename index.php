<?php

include ("functions.php");

include ("views/header.php");

if(isset($_GET['page']))
{
    if($_GET['page'] == "timeline") {
        include("views/timeline.php");
    }
    else if($_GET['page'] == "yourtweets") {
        include("views/yourTweets.php");
    }
    else if($_GET['page'] == "search") {
        include("views/search.php");
    }
    else if($_GET['page'] == "publicprofiles") {
        include("views/publicprofiles.php");
    }
}
else {
    include("views/main.php");
}
include ("views/footer.php");

?>