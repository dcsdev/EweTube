<?php
require_once("../includes/config.php");
require_once("../includes/classes/DataAccess.php");

if (!isset($_POST["videoId"]) || !isset($_POST["thumbnailId"]))  {
    return;
} else {
    $videoId        = $_POST["videoId"];
    $thumbnailId    = $_POST["thumbnailId"];

    $whereParams = array(
        "videoId" => $videoId
    );

    $columns = array(
        "selected" => "0"
    );

    DataAccess::Update($this->con, "thumbnails",$columns,$whereParams);

    $whereParams = array(
        "id" => $thumbnailId
    );

    DataAccess::Update($this->con, "thumbnails",$columns,$whereParams);

}

$commentText        = $_POST["commentText"];
$postedBy           = $_POST["postedBy"];
$videoId            = $_POST["videoId"];
$replyTo            = isset($_POST["replyTo"]) ? $_POST["replyTo"] : "";

$values = array(
    "postedBy" => $postedBy,
    "videoId"  => $videoId,
    "responseTo" => $replyTo,
    "body" => $commentText
);

$lastID = DataAccess::Insert($this->con,"comments", $values);

$userLoggedInObj = new User($con, $_SESSION["userLoggedIn"]);
$newComment = new Comment($con, $lastID, $userLoggedInObj, $videoId);
echo $newComment->create();


?>