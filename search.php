<?php

require_once("includes/header.php");
require_once("includes/classes/SearchResultsProvider.php");


if (!isset($_GET["term"]) || $_GET["term"] == "") {
    echo "You Must Have a search term";
    exit();
}

$term = $_GET["term"];

if (!isset($_GET["orderBy"]) || $_GET["orderBy"] == "views") {
    $orderBy = "views";
} else {
    $orderBy = "uploadDate";
}

$searchResultsProvider  = new SearchResultsProvider($con, $userLoggedInInstance);
$videos                 = $searchResultsProvider->getVideos($term, $orderBy);
$videoGrid              = new VideoGrid($con, $userLoggedInInstance);

?>

<div class="largeVideoGridContainer">
    <?php
        if (sizeof($videos) > 0) {
            $tensedResultName = sizeof($videos) == 1 ? " result" : " results";
            echo $videoGrid->createLarge($videos, sizeof($videos) .  $tensedResultName, true);
        } else {
            echo "No Results Found";
        }
    ?>
</div>


<?php
require_once("includes/footer.php");
?>