<?php

    class User {
        private $con;
        private $sqlData;

        public function __construct($con, $username) {
            $this->con = $con;            
            
            $whereParams = array(
                "username" => $username
            );
            
            $this->sqlData = DataAccess::getDataObject($con, "SELECT * FROM  users", $whereParams, ""); 
        }

        public function getUsername() {
            return $this->sqlData["username"];
        }

        public function getDisplayName() {
            return $this->sqlData["firstname"] . " " . $this->sqlData["lastname"] ;
        }

        public function getFirstName() {
            return $this->sqlData["firstname"];
        }

        public function getLastName() {
            return $this->sqlData["lastname"];
        }

        
        public function getEmail() {
            return $this->sqlData["email"];
        }

        
        public function getProfilePhotoURL() {
            return $this->sqlData["profilephotourl"];
        }

        
        public function getSignUpDate() {
            return $this->sqlData["createdate"];
        }

        public static function isLoggedIn() {
            return isset($_SESSION["userLoggedIn"]);
        }

        public function isSubscribedTo($userTo) {
            $userFrom = $this->getUsername();
            
            $whereParams = array(
                "userto"    => $userTo,
                "userFrom"  => $userFrom
            );

            return DataAccess::SelectRowCount($this->con, "SELECT * FROM  subscribers", $whereParams) > 0;
        }

        public function getSubscriberCount() {
            $userFrom = $this->getUsername();

            $whereParams = array(
                "userto"    => $userFrom,
            );

            return DataAccess::SelectRowCount($this->con, "SELECT * FROM subscribers", $whereParams);
        }

        public function getSubscriptions() {
            $userFrom = $this->getUsername();

            $whereParams = array(
                "userTo"    => $userFrom,
            );

            $data = DataAccess::getDataArray($this->con, "SELECT userTo FROM  subscribers", $whereParams, "");

            $subscriptions = array();

            foreach ($data as $row) {
                $user = new User($this->con, $row["userTo"]);
                array_push($subscriptions, $user);
            }

            return $subscriptions;
        }
    }
?>