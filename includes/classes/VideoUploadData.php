<?php  
class VideoUploadData {

    public $fileData;
    public $title;
    public $description;
    public $category;
    public $privacy;
    public $uploadedByUser;

    public function __construct($fileData, $title, $description, $category, $privacy, $uploadedByUser) {

        $this->fileData = $fileData;
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->privacy = $privacy;
        $this->uploadedByUser = $uploadedByUser;

    }

    public function updateDetails($con,$videoId) {
        $updateColumns = array(
            "title"         => $this->title,
            "description"   => $this->description,
            "privacy"       => $this->privacy,
            "category"      => $this->category
        );

        $whereParams = array(
            "id" => $videoId
        );

        $result = DataAccess::Update($this->con, "videos", $updateColumns, $whereParams);

        if (!$result) {
           // echo "query failed";
            exit();
        } else {
            //echo "query succeeded";
        }

        return $result;
    }
}
?>
