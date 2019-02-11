<?php

class LikedVideoProvider {

    private $con;
    private $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {

        $this->con              = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }   

    public function getVideos() {
        $videos = array();

        $whereParams = array(
            "username"  => $this->userLoggedInObj->getUsername(),
            "commentId" => "0"
        );
        
        $data = DataAccess::getDataArray($this->con, "SELECT videoId FROM likes", $whereParams, "ORDER BY id DESC");

        foreach ($data as $row) {
            $video = new Video($this->con, $row["videoId"], $this->userLoggedInObj);
            array_push($videos, $video);
        }

        return $videos;
        
    }

}
