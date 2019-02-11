<?php

require_once("includes/header.php");
require_once("includes/classes/TrendingProvider.php");

$trendingProvider = new TrendingProvider($con, $userLoggedInInstance);
$videos = $trendingProvider->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInInstance);
?>

<div class="largeVideoGridContainer">
    <?php
        if (sizeof($videos) >0) {
            echo $videoGrid->createLarge($videos, "Trending Videos", false);
        }
    ?>
</div>

<?php
require_once("includes/footer.php");
?>