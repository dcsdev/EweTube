<?php

require_once("includes/header.php");
require_once("includes/Classes/LikedVideoProvider.php");

if (!User::isLoggedIn()) {
    header("Location: signin.php");
}

$likedVideoProvider = new LikedVideoProvider($con, $userLoggedInInstance);
$videos = $likedVideoProvider->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInInstance);
?>

<div class="largeVideoGridContainer">
    <?php
        if (sizeof($videos) >0) {
            echo $videoGrid->createLarge($videos, "Videos You Have Liked", false);
        }
    ?>
</div>

<?php
require_once("includes/footer.php");
?>