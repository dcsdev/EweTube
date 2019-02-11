<?php
require_once("includes/header.php");
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/VideoDetailsFormProvider.php");
require_once("includes/classes/VideoUploadData.php");
require_once("includes/classes/selectThumbnails.php");

if (!User::isLoggedIn()) {
    header("Location: signin.php");
}

if (!isset($_GET["videoId"])) {
    echo "No Video Selected";
    exit();
}

$detailsMessage = "";

$video = new Video($con, $_GET["videoId"], $userLoggedInInstance->getUserName());

if (isset($_POST["saveButton"])) {
    $videoData = new VideoUploadData(null, $_POST["titleInput"],$_POST["descriptionInput"], $_POST["privacyInput"], $_POST["categoryInput"], $userLoggedInInstance->getUserName());

    if ($videoData->updateDetails($con,$video->getId())) {
        $detailsMessage = "<div class='alert alert-success'>
                            <strong>SUCCESS! Saved Video Details</strong>
                          </div>";

        $video = new Video($con, $_GET["videoId"], $userLoggedInInstance->getUserName());

    } else {        
        $detailsMessage = "<div class='alert alert-danger'>
        <strong>Unable To Save Video Details</strong>
      </div>";
    }
}

if ($video->getUploadedBy() != $userLoggedInInstance->getUserName()) {
    echo "Nice Try, This is not Your Video!";
    exit();
}

?>

<script src="assets/js/editVideoActions.js"></script>

<div class="editVideoContainer column">
    <div class="message">
        <?php

        echo $detailsMessage;

        ?>
    </div>
    <div class="topSection">
        <?php        
        $videoPlayer = new VideoPlayer($video);
        echo $videoPlayer->create(false);

        $selectThumbnail = new SelectThumbnail($con,$video);
        echo $selectThumbnail->create();
        ?>
    </div>
    
    <div class="bottomSection">
        <?php
            $formProvider = new VideoDetailsFormProvider($con);
            echo $formProvider->createEditDetailsForm($video);
        ?>
    </div>
</div>