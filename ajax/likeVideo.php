<?php
    require_once("../includes/config.php");
    require_once("../includes/classes/DataAccess.php"); 
    require_once("../includes/classes/Video.php");
    require_once("../includes/classes/User.php");

    $username   = $_SESSION["userLoggedIn"];
    $videoId    =  $_POST["videoId"];

    $userObj    = new User($con, $username);
    $video      = new Video($con, $videoId, $userObj);

    echo $video->like();

?>