<?php
require_once("includes/header.php");

if (!User::isLoggedIn()) {
    header("Location: signin.php");
}

$subscriptionsProvider = new SubscriptionProvider($con, $userLoggedInInstance);
$videos = $subscriptionsProvider->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInInstance);
?>

<div class="largeVideoGridContainer">
    <?php
        if (sizeof($videos) >0) {
            echo $videoGrid->createLarge($videos, "Vidoes of The People You Love", false);
        }
    ?>
</div>

<?php
require_once("includes/footer.php");
?>