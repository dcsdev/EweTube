<?php

require_once("../includes/config.php"); 
require_once("../includes/classes/DataAccess.php");
require_once("../includes/classes/User.php");
require_once("../includes/classes/Comment.php");

echo $_POST;
exit();
    
    if (!isset($_POST["commentText"]) || !isset($_POST["postedBy"]) || !isset($_POST["videoId"]))  {
        echo $_POST;
        exit();
        return;
    } else {
        echo json_encode($_POST);
    }
    
    $commentText        = $_POST["commentText"];
    $postedBy           = $_POST["postedBy"];
    $videoId            = $_POST["videoId"];
    $replyTo            = isset($_POST["replyTo"]) ? $_POST["replyTo"] : "";
    

    $values = array(
        "postedBy"      => $postedBy,
        "videoId"       => $videoId,
        "responseTo"    => $replyTo,
        "body"          => $commentText
    );

    $lastID = DataAccess::Insert($con, "comments", $values);

    $userLoggedInObj    = new User($con, $_SESSION["userLoggedIn"]);
    $newComment         = new Comment($con, $lastID, $userLoggedInObj, $videoId);
    echo $newComment->create();
?>