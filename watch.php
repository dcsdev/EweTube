<?php 
require_once("includes/header.php"); 
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/VideoInfoSection.php");
require_once("includes/classes/CommentSectionInfo.php");
require_once("includes/classes/Comment.php");

if (!isset($_GET["id"])) {
    echo "No Video ID Specified";
    exit();
} 

$video = new Video($con,$_GET["id"], $userLoggedInInstance);

//TODO: This should proably be moved to only increment if video is played
$video->incrementViews();
?>

<script src="assets/js/videoPlayerActions.js" ></script>
<script src="assets/js/commentActions.js" ></script>
<div class="watchLeftColumn">
    <?php 
        $videoPlayer = new VideoPlayer($video);
        echo $videoPlayer->create(true);

        $videoInfoSection = new VideoInfoSection($con, $video,$userLoggedInInstance);
        echo $videoInfoSection->create();

        $commentInfoSection = new CommentSection($con, $video,$userLoggedInInstance);
        echo $commentInfoSection->create();
    ?>
</div>

<div class="suggestions">
    <?php 
    $videoGrid = new VideoGrid($con, $userLoggedInInstance)  ;
    echo $videoGrid->create(null, null, false);
    ?>
</div>


<?php require_once("includes/footer.php");?>