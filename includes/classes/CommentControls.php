<?php
require_once("ButtonProvider.php");
    class CommentControls {
        private $con;
        private $comment;
        private $userLoggedInObj;

    public function __construct($con, $comment,$userLoggedInObj) {
        $this->con              = $con;
        $this->userLoggedInObj  = $userLoggedInObj;
        $this->comment          = $comment;
    }

    public function create() {
        $replyButton            = $this->createReplyButton();
        $likesCount             = $this->createLikesCount();
        $likeButton             = $this->createLikeButton();
        $dislikeButton          = $this->createDislikeButton();
        $replySection           = $this->createReplySection();

        return "<div class='controls'>
                $replyButton
                $likesCount
                $likeButton
                $dislikeButton
                </div>$replySection";
    }


    private function createReplyButton() {

        $text   = "REPLY";
        $action = "toggleReply(this)";
        return ButtonProvider::create(null,$action,null,"REPLY");
    }

    private function createLikesCount() {
        $text = $this->comment->getLikes();

        if ($text == 0) {
            $text = "";
        }

        return "<span class='likesCount'>$text</span>";
    }

    private function createReplySection() {
        $postedBy           = $this->userLoggedInObj->getuserName();
        $videoId            = $this->comment->getVideoId();
        $commentId          = $this->comment->getId();

        $profileButton      = ButtonProvider::createProfileButton($this->con, $postedBy);
       

        $action             = "postComment(this,\"$postedBy\",$commentId,$videoId,null,\"comments\")";
        $actionButton       = ButtonProvider::create("postComment", $action, null, "Reply");

        $cancelButtonAction ="toggleReply(this)";
        $cancelButton       = ButtonProvider::create("cancelComment", $cancelButtonAction, null, "Cancel");

        return "<div class='commentForm'>
                    $profileButton
                    <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
                    $cancelButton
                    $actionButton
                </div>";
    }

    private function createLikeButton() {
        $commentId      = $this->comment->getId();
        $videoId        = $this->comment->getVideoId();
        $action         = "commentInteraction($commentId,this,$videoId,1)";
        $class          = "likeButton";

        $imgsrc         = "assets/images/icons/thumb-up.png";

        if($this->comment->wasLikedBy()) {
            $imgsrc     = "assets/images/icons/thumb-up-active.png";
        }

        return ButtonProvider::create($class,$action,$imgsrc,"");
    }

    private function createDislikeButton() {
        $videoId        = $this->comment->getVideoId();
        $commentId      = $this->comment->getId();
        $action         = "commentInteraction($commentId,this,$videoId,0)";
        $class          = "dislikeButton";

        $imgsrc         = "assets/images/icons/thumb-down.png";

        if($this->comment->wasdislikedBy()) {
            $imgsrc     = "assets/images/icons/thumb-down-active.png";
        }

        return ButtonProvider::create($class,$action,$imgsrc,"");
    }
}
?>