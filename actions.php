<?php

include("functions.php");

if ($_GET['action'] == "loginSignup") {
    $error = "";


    if (!$_POST['email']) {
        $error = "Email is required. ";
    } else if (!$_POST['password']) {
        $error = "Password is required. ";
    } else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
        $error = "Not a valid email address.";
    }


    if ($_POST['loginActive'] == "0" && $error == "") {
        $query = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($link, $_POST['email']) . "'LIMIT 1";
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0)
            $error = "That email address is already taken.";

        else {

            $query = "INSERT INTO users (email, password) VALUES ('" . mysqli_real_escape_string($link, $_POST['email']) . "','" . mysqli_real_escape_string($link, $_POST['password']) . "')";

            if (mysqli_query($link, $query)) {

                $_SESSION['id']=mysqli_insert_id($link);

                $query = "UPDATE users SET password='" . md5(md5($_SESSION['id']) . $_POST['password']) . "' WHERE id = " . $_SESSION['id'] . " ";
                mysqli_query($link, $query);



                echo "1";
            } else {
                $error = "Couldn't create user. Please try again later.";
            }

        }

    }
    if ($_POST['loginActive'] == "1" && $error == "")
    {
        $query = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($link, $_POST['email']) . "'LIMIT 1";
        $result = mysqli_query($link, $query);

        $row=mysqli_fetch_assoc($result);

        if($row['password'] == md5(md5($row['id']) . $_POST['password']))
        {
            $_SESSION['id']=$row['id'];

            echo "1";
        }
        else{
            $error= "You got this wrong ma friend";
        }
    }

    if ($error != "")
        echo $error;
}
if($_GET['action']== "toggleFollow")
{
    $query="SELECT * FROM isFollowing WHERE follower_id = " . mysqli_real_escape_string($link, $_SESSION['id']) .
        " AND isFollowing_id = " . mysqli_real_escape_string($link, $_POST['userId']) . " LIMIT 1 ";

    $result= mysqli_query($link, $query);

    if(mysqli_num_rows($result) > 0)
    {
        $row=mysqli_fetch_assoc($result);

        mysqli_query($link, "DELETE FROM isFollowing WHERE id = " . mysqli_real_escape_string($link, $row['id']) . " LIMIT 1 ");

        echo "1";
    }
    else
    {
        mysqli_query($link, "INSERT INTO isFollowing (follower_id, isFollowing_id) VALUES ("
            . mysqli_real_escape_string($link, $_SESSION['id']) . "," . mysqli_real_escape_string($link, $_POST['userId']) . ")  ");

        echo "2";
    }
}
if($_GET['action'] == "postTweet")
{

    if(!$_POST['tweet'])
    {
        echo "Your post is empty.";
    }
    else if(strlen($_POST['tweet']) > 140)
    {
        echo "No go. Tooo long!";
    }
    else {

        $query = "INSERT INTO tweets ( tweet, user_id ) VALUES (' " . mysqli_real_escape_string($link, $_POST['tweet']) . "' , '" .
            mysqli_real_escape_string($link, $_SESSION['id']) . " ' ) ";

        mysqli_query($link, $query);

        echo "1";
    }
}
?>