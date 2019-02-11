<?php
require_once("includes/classes/ButtonProvider.php");
    
class VideoInfoControls {
        private $video;
        private $userLoggedInObj;

    public function __construct($video, $userLoggedInObj) {
        $this->video = $video;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDislikeButton();
        return "<div class='controls'>
                $likeButton
                $dislikeButton
                </div>";
    }

    private function createLikeButton() {
        $text       = $this->video->getLikes();
        $videoId    = $this->video->getId();
        $action     = "likeVideo(this,$videoId)";
        $class      = "likeButton";

        $imgsrc = "assets/images/icons/thumb-up.png";

        if($this->video->wasLikedBy()) {
            $imgsrc = "assets/images/icons/thumb-up-active.png";
        }

        return ButtonProvider::create($class,$action,$imgsrc,$text);
    }

    private function createDislikeButton() {
        $text       = $this->video->getDislikes();
        $videoId    = $this->video->getId();
        $action     = "dislikeVideo(this,$videoId)";
        $class      = "dislikeButton";

        $imgsrc = "assets/images/icons/thumb-down.png";

        if($this->video->wasdislikedBy()) {
            $imgsrc = "assets/images/icons/thumb-down-active.png";
        }

        return ButtonProvider::create($class,$action,$imgsrc,$text);
    }
}
?>