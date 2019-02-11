<?php

class SelectThumbnail {
    private $con, $video;

    public function __construct($con, $video) {
        $this->con      = $con;
        $this->video    = $video;
    }

    public function create() {
        $thumbNailData = $this->getThumbnailData();

        $html = "";

        foreach ($thumbNailData as $data) {
            $html .= $this->createThumbnailItem($data);
        }

        return "<div class='thumbnailItemsContainer'>
                    $html
                </div>";
    }

    private function createThumbnailItem($data) {
        $id         = $data["id"];
        $url        = $data["filePath"];
        $videoId    = $data["videoId"];
        $selected   = $data["selected"] == 1 ? "selected": "";

        return "<div class='thumbnailItem $selected' onclick='setNewThumbnail($id,$videoId,this)'>
                    <img src='$url'>
                </div>";
    }

    private function getThumbnailData() {
        $data = array();

        $whereParams = array(
            "videoId" => $videoId
        );

        $result = DataAccess::getDataObject($this->con, "SELECT * FROM thumbnails", $whereParams, "");

        while ($row = $result) {
            $data[] = $row;
        }

        return $data;
    }
}
?>