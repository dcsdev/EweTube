<?php

    class Video {
        private $con;
        private $sqlData;
        private $userLoggedInInstance;

        public function __construct($con, $input, $userLoggedIn) {
            $this->con = $con;            
            $this->userLoggedInInstance = $userLoggedIn;
            
            if (is_array($input)) {
                $this->sqlData = $input;
            } else {
        
                $whereParams = array(
                    "id" => $input
                );

                $this->sqlData = DataAccess::getDataObject($this->con,"SELECT * FROM videos",$whereParams, "");
            }
        }

        public function getCategory() {
            return $this->sqlData["category"];
        }

        public function getUploadedBy() {
            return $this->sqlData["uploadedBy"] ;
        }

        public function getTitle() {
            return $this->sqlData["title"];
        }

        public function getDescription() {
            return $this->sqlData["description"];
        }

        
        public function getPrivacy() {
            return $this->sqlData["privacy"];
        }

        
        public function getFilePath() {
            return $this->sqlData["filePath"];
        }

        
        public function getTimeStamp() {
            $date = $this->sqlData["uploadDate"];
            return date("M jS, Y",strtotime($date));
        }

        public function getUploadDate() {
            $date = $this->sqlData["uploadDate"];
            return date("M j, Y",strtotime($date));
        }

        public function getDuration() {
            return $this->sqlData["duration"];
        }

        
        public function getViews() {
            return $this->sqlData["views"];
        }

        public function getId() {
            return $this->sqlData["id"];
        }

        public function getLikes() {
            $videoId = $this->getId();

            $whereParams = array(
                "videoid" => $videoId
            );

            return DataAccess::SelectRowCount($this->con, "SELECT * FROM likes", $whereParams);
        }

        public function getDislikes() {
            $videoId = $this->getId();

            $whereParams = array(
                "videoid" => $videoId
            );

            return DataAccess::SelectRowCount($this->con, "SELECT * FROM dislikes", $whereParams);
        }

        
        public function incrementViews() {
            $videoId = $this->getId();

            $columnParams = array(
                "views" => "views+1"
            );

            $whereParams = array(
                "id" => $videoId
            );

            DataAccess::Update($this->con, "videos", $columnParams, $whereParams);

            $this->sqlData["views"] = $this->sqlData["views"] + 1;
        }

        public function like() {
                $id = $this->getId();
                $username = $this->userLoggedInInstance->getUsername();

                $whereParams = array(
                    "videoId" => $id,
                    "username" => $username
                );

                $rowcount = DataAccess::SelectRowCount($this->con, "SELECT * FROM likes", $whereParams);

                if ($rowcount > 0) {
                    $whereParams = array(
                        "username" => $username,
                        "videoid" => $id
                    );

                    DataAccess::Delete($this->con, "DELETE FROM likes", $whereParams);

                    $result = array(
                        "likes" => -1,
                        "dislikes" => 0
                    );

                    return json_encode($result);


                } else {
                    //User has not been liked
                    $result = DataAccess::Delete($this->con, "DELETE FROM dislikes", $whereParams);

                    $count = $result->rowCount();

                    $values = array(
                        "username" => $username,
                        "videoid" => $id
                    );

                    DataAccess::Insert($this->con, "likes", $values);

                    $result = array(
                        "likes" => 1,
                        "dislikes" => 0 - $count
                    );

                    return json_encode($result);
                }
        }

        public function wasLikedBy() {
            $id = $this->getId();
            $username = $this->userLoggedInInstance->getUsername();

            $whereParams = array(
                "username"  => $username,
                "videoid"   => $id
            );

            $result = DataAccess::Delete($this->con, "DELETE FROM likes", $whereParams);

            return  $result->rowCount() > 0;
        }

        public function wasDislikedBy() {
            $id = $this->getId();
            $username = $this->userLoggedInInstance->getUsername();

            $whereParams = array(
                "videoId"   => $id,
                "username"  => $username
            );

            $result = DataAccess::Delete($this->con, "DELETE FROM dislikes", $whereParams);

            return  $result->rowCount() > 0;
        }

        public function dislike() {
            $id = $this->getId();
            $username = $this->userLoggedInInstance->getUsername();

            $whereParams = array(
                "videoId"   => $id,
                "username"  => $username
            );

            $result = DataAccess::Delete($this->con, "DELETE FROM dislikes", $whereParams);

            if ($result->rowCount() > 0) {
                $result = DataAccess::Delete($this->con, "DELETE FROM dislikes", $whereParams);

                $result = array(
                    "likes" => 0,
                    "dislikes" => -1
                );

                return json_encode($result);


            } else {
                //User has not been liked
                $result = DataAccess::Delete($this->con, "DELETE FROM likes", $whereParams);

                $count  = $result->rowCount();

                $values = array(
                    "username"  => $username,
                    "videoid"   => $id
                );

                DataAccess::Insert($this->con, "dislikes", $values); 

                $result = array(
                    "likes" => 0 - $count,
                    "dislikes" => 1
                );

                return json_encode($result);
            }
    }

        public function getNumberOfComments() {
            $id = $this->getId();

            $whereParams = array(
                "videoId" => $id,
            );

            return DataAccess::SelectRowCount($this->con, "SELECT * from comments", $whereParams);

        }

        public function getComments() {
            $id = $this->getId();

            $whereParams = array(
                "videoId"        => $id,
                "responseTo"    => "0"
            );
            
            $data = DataAccess::getDataArray($this->con, "SELECT * from comments", $whereParams, "");

            $comments = array();

            foreach ($data as $row) {
                $comment = new Comment($this->con, $row, $this->userLoggedInInstance, $id);
                array_push($comments, $comment);
            }

            return $comments;
        }

        public function getThumbnail() {
            $query = $this->con->prepare("SELECT filePath from thumbnails WHERE videoId=:Id AND selected=1");
            $query->bindParam(":Id", $id);
            $id = $this->getId();

            $whereParams = array(
                "videoId" => $id,
                "selected" => "1"
            );

            return DataAccess::getSingleColumn($this->con, "SELECT filePath from thumbnails", $whereParams);
        }
    }
?>