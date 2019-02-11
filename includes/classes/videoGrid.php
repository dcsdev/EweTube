<?php

class VideoGrid {

    private $con;
    private $userLoggedInObj;
    private $largeMode = false;
    private $gridClass = "videoGrid";

    public function __construct($con, $userLoggedInObj) {

        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;

    }

    public function create($videos, $title, $showFilter) {

        if ($videos == null) {
            $gridItems = $this->generateItems();
        } else {
            $gridItems = $this->generateItemsFromVideos($videos);
        }

        $header = "";

        if ($title != null) {
            $header = $this->createGridHeader($title,$showFilter);
        }

        return "    $header
                    <div class='$this->gridClass'
                    $gridItems                
                </div>";
    }

    public function generateItems() {
        $elementsHtml = "";

        $data = DataAccess::getDataArray($this->con,"SELECT * FROM videos", null, "");

        foreach ($data as $row) {
            $video  = new Video($this->con, $row, $this->userLoggedInObj);
            $item   = new VideoGridItem($video, $this->largeMode);
            $elementsHtml .= $item->create();
        }

        return $elementsHtml;
    }

    public function generateItemsFromVideos($videos) {
        $elementsHTML = "";

        foreach ($videos as $video) {
            $item = new VideoGridItem($video, $this->largeMode);
            $elementsHTML .= $item->create();

        }

        return $elementsHTML;
    }

    public function createGridHeader($title, $showFilter) {
        $filter = "";

        if ($showFilter) {
            $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            $urlArray = parse_url($link);
            $query = $urlArray["query"];

            parse_str($query, $params);

            unset($params["orderBy"]);

            $newQuery = http_build_query($params);

            $newURL = basename($_SERVER["PHP_SELF"]) . "?" . $newQuery;

            $filter = "<div class='right'>
                            <span>Orderby:<span>
                            <a href='$newURL&orderBy=uploadDate'>Upload date</a>
                            <a href='$newURL&orderBy=views'>Most viewed</a>
            
                        </div>";

        }
        $header = "<div class='videoGridHeader'>
                        <div class='left'>
                            $title
                        </div>
                        $filter
                    </div>"    ;
        return $header;
    }

    public function createLarge($videos, $title, $showFilter) {
        $this->gridClass .= " large";
        $this->largeMode = true;
        return $this->create($videos, $title, $showFilter);
    }
}
?>