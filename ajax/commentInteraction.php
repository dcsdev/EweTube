<?php
    require_once("../includes/config.php"); 
    require_once("../includes/classes/DataAccess.php");
    require_once("../includes/classes/Comment.php");
    require_once("../includes/classes/User.php");

    $username   = $_SESSION["userLoggedIn"];
    $videoId    =  $_POST["videoId"];
    $commentId  =  $_POST["commentId"];
    $isLike     =  $_POST["isLike"];

    $userObj = new User($con, $username);
    $comment = new Comment($con, $commentId, $userObj, $videoId);


    if ($isLike) {
        echo $comment->like();    
    } else {
        echo $comment->dislike();    
    }
?>