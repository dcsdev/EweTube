<?php

class ProfileData {

    private $con, $profileUserObj;

    public function __construct($con, $profileUserObj) {
        $this->con = $con;
        $this->profileUserObj = new User($con, $profileUserObj);
    }

    public function getProfileUserName() {
        return $this->profileUserObj->getUsername();
    }

    public function userExists() {
        $username = $this->getProfileUserName();

        $whereParams = array(
            "username" => $username
        );

        return DataAccess::SelectRowCount($this->con, "SELECT * FROM users ", $whereParams) != 0;
    }

    public function getCoverPhoto() {
        return "assets/images/coverPhotos/default-cover-photo.jpg";
    }

    public function getProfileUserFullName() {
        return $this->profileUserObj->getDisplayName();
    }

    public function getProfilePic() {
        return $this->profileUserObj->getProfilePhotoURL();
    }

    public function getSubscriberCount() {
        return $this->profileUserObj->getSubscriberCount();
    }

    public function getProfileUserObj() {
        return $this->profileUserObj;
    }

    public function getUsersVideos() {
        $username = $this->getProfileUserName();

        $whereParams = array(
            "uploadedBy" => $username
        );

        $data = DataAccess::getDataObject($this->con,"SELECT * FROM videos", $whereParams, "ORDER BY uploadDate DESC");

        $videos = array();

        foreach ($data as $row) {
            $videos[] = new Video($this->con,$row,$this->profileUserObj->getUsername());
        }
        
        return $videos;
    }

    public function getAllUserDetails() {
        return array(
            "Name"          => $this->getProfileUserFullName(),
            "Username"      => $this->getProfileUserName(),
            "Subscribers"   => $this->getSubscriberCount(),
            "Total Views"   => $this->getTotalViews(),
            "Sign up date"  => $this->getSignUpDate()
        );
    }

    private function getTotalViews() {
        $username = $this->getProfileUserName();

        $whereParams = array(
            "uploadedBy" => $username
        );

        return DataAccess::getSingleColumn($this->con, "SELECT SUM(views) as 'TotalViews' from videos", $whereParams);

    }

    private function getSignUpDate() {
        $date = $this->profileUserObj->getSignUpDate();

        return date("F jS, Y", strtotime($date));
    }
}


?>