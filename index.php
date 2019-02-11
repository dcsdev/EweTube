<?php require_once("includes/header.php"); ?>

<div class="videoSection">
    <?php

        $subscriptionsProvider  = new SubscriptionProvider($con, $userLoggedInInstance);
        $subscriptionVideos     = $subscriptionsProvider->getVideos();

        $videoGrid              = new VideoGrid($con, $userLoggedInInstance->getusername());
        
        if (User::isLoggedIn() && sizeof($subscriptionVideos) > 0) {
            echo $videoGrid->create(null,"Subscribed",false);
        }
        
        echo $videoGrid->create(null,"For You",false);
    ?>
</div>

<?php require_once("includes/footer.php");?>