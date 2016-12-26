<?php

session_start();

$link=mysqli_connect("localhost", "root", "", "twitter");

if(mysqli_connect_error())
{
    print_r(mysqli_connect_error());
    exit();
}

if(isset($_GET['function']))
{
    session_unset();
}

function time_since($since) {
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
        array(1 , 'second')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    return $print;
}

function displayTweets($type)
{
    global $link;

    if($type == "public")
    {
        $whereClause="";
    }

    else if($type == "isFollowing")
    {

        $query= "SELECT * FROM isfollowing WHERE follower_id = " . mysqli_real_escape_string($link, $_SESSION['id']) . " ";

        $result=mysqli_query($link, $query);

        $whereClause="";

        while($row = mysqli_fetch_assoc($result))
        {
            if($whereClause == "")
                $whereClause= " WHERE ";
            else
                $whereClause.= " OR ";

            $whereClause.= " user_id= " . $row['isFollowing_id'] . " ";
        }

        if($whereClause=="")
        {

            $whereClause=" WHERE user_id = 0";
        }

    }
    else if($type == "yourtweets")
    {

            $whereClause= " WHERE user_id= " . mysqli_real_escape_string($link, $_SESSION['id']) . " ";

    }
    else if($type == "search")
    {

        echo "<p>Showing search results for '" . mysqli_real_escape_string($link, $_GET['q']) . "' :</p>";

        $whereClause= " WHERE tweet LIKE '%" . mysqli_real_escape_string($link, $_GET['q']) . "%' ";

    }
    else if(is_numeric($type))
    {
        $query= "SELECT * FROM users WHERE id= " . mysqli_real_escape_string($link, $type) . " LIMIT 1";

        $result=mysqli_query( $link, $query);

        $resultUser=mysqli_fetch_assoc($result);

        echo "<p>Showing tweet's from " . $resultUser['email'] . " :</p>";

        $whereClause= " WHERE user_id = " . mysqli_real_escape_string($link, $type) . " ";

    }

    $query= "SELECT * FROM tweets" . $whereClause . " LIMIT 10";

    $result=mysqli_query($link, $query);

    if(mysqli_num_rows($result)== 0)
    {
        echo "There are no tweets to dispaly.";
    }
    else{
        while($row = mysqli_fetch_assoc($result))
        {
            $userQuery= "SELECT * FROM users WHERE id =" . mysqli_real_escape_string($link, $row['user_id']) . " LIMIT 1";
            $userQueryResult = mysqli_query($link, $userQuery);


            $user =  mysqli_fetch_assoc($userQueryResult);



            echo "<div id='tweetBox'> <p><a href='?page=publicprofiles&user_id=" . $user['id'] . "'>" . $user['email'] . "</a> <span class='time'>" . time_since(time() - strtotime($row['date'])) . " ago</span></p>";

            echo "<p>" . $row['tweet'] . "</p>";

            echo "<a href='#' class='follow' data-userId= ' " . $row['user_id'] . "' >";

            $isFollowingQuery= "SELECT * FROM isfollowing WHERE follower_id = " . mysqli_real_escape_string($link, $_SESSION['id']) .
                " AND  isFollowing_id= " . mysqli_real_escape_string($link,  $row['user_id']) . " ";

            $isFollowingResult=mysqli_query($link, $isFollowingQuery);

            if(mysqli_num_rows($isFollowingResult) > 0 )
            {
                echo "Unfollow";
            }
            else
            {
                echo "Follow";
            }

            echo "</a></div>";
        }
    }
}

function displaySearchBox()
{
    echo '<form class="form-inline">
    <div class="form-group">
      <input type="hidden" name="page" value="search">
      <input type="text" name="q" class="form-control" id="search" placeholder="Search">
    </div>
    <button type="submit"  class="btn btn-primary">search</button>
  </form>';
}

function displayTweetBox()
{
    if (isset($_SESSION['id'])) {
            echo '<div>
            <div id="postError" class="alert alert-danger"></div>
            <div id="postSuccess" class="alert alert-success"></div>
        <div class="form-group">
          <textarea class="form-control" id="postTweetArea" placeholder="Tweet to the world"></textarea>
        </div>
        <button id="postTweetButton" class="btn btn-primary">Post Tweet</button>
  </div>';
    }
}
function displayUsers()
{
    global $link;

    echo $query = " SELECT * FROM users LIMIT 10";
    $result = mysqli_query($link, $query);

    while($row=mysqli_fetch_assoc($result))
    {
        echo "<p><a href='index.php?page=publicprofiles&user_id=" . $row['id'] . "'>" . $row['email'] . "</a></p>";
    }
}

?>