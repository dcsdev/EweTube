<?php
require_once("ButtonProvider.php");
require_once("CommentControls.php");

class Comment {

    private $con, $sqlData, $userLoggedInObj, $videoId;


    public function __construct($con, $input, $userLoggedInObj, $videoId) {

        if (!is_array($input)) {

            $whereParams = array(
                "id" => $input
            );

            $input = DataAccess::getDataObject($con, "SELECT * FROM comments", $whereParams, "");
        } 

        $this->sqlData = $input;
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
        $this->videoId = $videoId;
    }

    public function getId() {
        return $this->sqlData["Id"];
    }

    public function getVideoId() {
        return $this->videoId;
    }

    public function wasLikedBy() {
        $id = $this->getId();
        $username = $this->userLoggedInObj->getUsername();

        $whereParams = array(
            "commentId" => $id,
            "username"  => $username
        );

        return  DataAccess::SelectRowCount($this->con, "SELECT * FROM likes", $whereParams) > 0;
    }

    public function wasDislikedBy() {
        $id = $this->getId();
        $username = $this->userLoggedInObj->getUsername();

        $whereParams = array(
            "commentId" => $id,
            "username"  => $username
        );

        return  DataAccess::SelectRowCount($this->con, "SELECT * FROM dislikes", $whereParams) > 0;
    }

    public function getLikes() {
    
        $commentId = $this->getId();

        $whereParams = array(
            "commentID" => $commentId
        );

        $rowcount = DataAccess::SelectRowCount($this->con, "SELECT * FROM likes", $whereParams);

        $numLikes = $rowcount;

        $rowcount = DataAccess::SelectRowCount($this->con, "SELECT * FROM likes", $whereParams);

        $numDislikes = $rowcount;

        return $numLikes - $numDislikes;

    }

    public function getNumberOfReplies() {
        $id = $this->sqlData["Id"];
        
        $whereParams = array(
            "responseTo" => $id,
        );

        return DataAccess::SelectRowCount($this->con, "SELECT * FROM comments", $whereParams);
    }

    public function create() {
        $body               = $this->sqlData["body"];
        $postedBy           = $this->sqlData["postedBy"];
        $profileButton      = ButtonProvider::createProfileButton($this->con, $postedBy);
        $timespan           = $this->time_elapsed_string($this->sqlData["datePosted"]);

        $id                 = $this->sqlData["Id"];
        $videoId            = $this->getVideoId();


        $commentControlsObj = new CommentControls($this->con, $this, $this->userLoggedInObj);
        $commentControls    = $commentControlsObj->create();


        $numResponses       = $this->getNumberOfReplies();
        $viewRepliesText    = "";
        
        if ($numResponses >0) {
            $viewRepliesText = "<span class='repliesSection viewReplies' onclick='getReplies($id, this, $videoId)'>
                                View all $numResponses replies
                                </span>";
        } else {
            $viewRepliesText = "<div class='repliesSection'></div>";
        }

        return "<div class='itemContainer'>
                    <div class='comment'>
                        $profileButton
                        <div class='mainContainer'>
                            <div class='commentHeader'>
                                <a href='profile.php?username=$postedBy'>
                                    <span class='username'>$postedBy</span>
                                </a>
                                <span class='timestamp'>$timespan</span>
                            </div>
                                <div class='body'>
                                    $body
                                </div>
                        </div>
                    </div>
                    $commentControls
                    $viewRepliesText
                </div>";
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function like() {
        $id = $this->getId();
    
        $username = $this->userLoggedInObj->getUsername();

        $whereParams = array(
            "username"  => $username,
            "commentId" => $id
        );

        if (DataAccess::SelectRowCount($this->con,"SELECT * FROM likes", $whereParams) > 0) {
            DataAccess::Delete($this->con,"DELETE FROM likes", $whereParams);
            return -1;
        } else {
            $count = DataAccess::Delete($this->con,"DELETE FROM dislikes", $whereParams);
            return 1 + $count;
        }
}


public function dislike() {
    $id         = $this->getId();
    $username   = $this->userLoggedInObj->getUsername();

    $whereParams = array(
        "username"  => $username,
        "commentId" => $id
    );

    if (DataAccess::SelectRowCount($this->con,"SELECT * FROM dislikes",$whereParams) > 0) {
        //Already Been disliked
        DataAccess::Delete($this->con,"DELETE FROM dislikes", $whereParams);
        return 1;
    } else {
        //User has not been liked
        $count = DataAccess::Delete($this->con,"DELETE FROM dislikes", $whereParams);

        $values = array(
            "username" => $username,
            "commentId" => $id
        );

        DataAccess::Insert($this->con, "dislikes", $values);

        return -1 - $count;
    }
}

public function getReplies() {
    $id = $this->getId();
            
    $whereParams = array(
        "commentId" => $id
    );

    $data = DataAccess::getDataObject($this->con,"SELECT * from comments", $whereParams, "ORDER BY datePosted ASC");

    $comments       = array();
    $videoId        = $this->getVideoId();

    while($row = $data) {
        $comment    = new Comment($this->con, $row, $this->userLoggedInInstance, $videoId);
        $comments   .= $comment->create();
    }

    return $comments;
}

}

?>