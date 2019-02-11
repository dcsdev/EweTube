<?php
class VideoProcess {
    private $con;
    private $sizeLimit = 500000000;
    //This should be a db call
    private $allowedTypes = array(  "png","jpg", "jpeg","flv", "mp4", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg", 
                                    "3g2","m4v");
    private $ffmpegPath;
    private $ffmpegProbePath;


    public function __construct($con) {
        $this->con  = $con;
         $os_ver    = PHP_OS;
         $os_ver    = strtolower($os_ver);
    
         if ($os_ver == "winnt") {
             $this->ffmpegPath = realpath("ffmpeg/bin/ffmpeg.exe");
             $this->ffmpegProbePath = realpath("ffmpeg/bin/ffprobe.exe");
         } else if ($os_ver == "linux") {
            $this->ffmpegPath = "ffmpeg/bin/ffmpeg";
             $this->ffmpegProbePath = "ffmpeg/bin/ffprobe";
         } else {
             echo "OS not found";
             exit();
         }


    }

    public function upload($videoUploadData) {
        $targetDir      = "uploads/videos/";
        $videoData      = $videoUploadData->fileData;

        $tempFilePath   = $targetDir . uniqid() . basename($videoData["name"]);
        $tempFilePath   = str_replace(" ", "_", $tempFilePath);
        
        $isValidData = $this->processData($videoData, $tempFilePath);

        if (!$isValidData) {
            return;
        }
        
        if (move_uploaded_file($videoData["tmp_name"],$tempFilePath)) {
            $finalFilePath = $targetDir . uniqid() . ".mp4";

            if (!$this->insertVideoData($videoUploadData, $finalFilePath)) {
                echo "Insert Query failed";
                return false;
            }
            
            if (!$this->convertVideoToMp4($tempFilePath, $finalFilePath)) {
                echo "Upload Failed";
                return false;
            }

            if (!$this->deleteFile($tempFilePath)) {
               echo "Upload Failed";
               return false;
            }

            if (!$this->generateThumbs($finalFilePath)) {
                echo "Upload failed : could not generate thumbnails\n";
                return false;
            }

            return true;


        } else {
            echo "File Was Not Moved";
        }
    }

    private function insertVideoData($data, $finalpath) {
 
        $values = array(
            "title"         => $data->title,
            "uploadedBy"    =>  $data->uploadedByUser,
            "description"   =>  $data->description,
            "privacy"       =>  $data->privacy,
            "category"      =>  $data->category,
            "filePath"      =>  $finalpath
        );
        return DataAccess::Insert($this->con,"videos", $values);
    }

    public function convertVideoToMp4($tempFilePath, $finalFilePath) {
        $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";
        $outputLog = array();

        exec($cmd,$outputLog, $returnCode);
                                             
        if ($returnCode != 0) {
            foreach ($error as $line) {
                echo $line . "<br>";
                
        }
        return false;
        }

    return true;
    }

    private function deleteFile($filePath) {
        
        if(!unlink($filePath)) {
            echo "Could not delete file\n";
            return false;
        }

        return true;
    }

    public function generateThumbs($filePath) {
        $thumbNailSize = "210x118";
        $numThumbnails = 3;
        $pathToThumbnail = "uploads/videos/thumbnails";
        
        $duration = $this->getVideoDuration($filePath);

        $duration = (int)$duration;

        $videoId    = $this->con->lastInsertId();

        $this->updateDuration($duration, $videoId);

        for($num = 1; $num <= $numThumbnails; $num++) {
            $imageName = uniqid() . ".jpg";
            $interval = ($duration * 0.8) / $numThumbnails * $num;
            $fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";

            $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbNailSize -vframes 1 $fullThumbnailPath 2>&1";

            $outputLog = array();
            exec($cmd, $outputLog, $returnCode);
            
            if($returnCode != 0) {
                //Command failed
                foreach($outputLog as $line) {
                    echo $line . "<br>";
                }
            }

            $selected = $num == 1 ? 1 : 0;

            $values = array(
                "videoId"   => $videoId,
                "filePath"  => $fullThumbnailPath,
                "selected"  => $selected
            );

            $result = DataAccess::Insert($this->con,"thumbnails",$values);

            if(!$result) {
                echo "Error inserting thumbail\n";
                return false;
            }
        }
             
        return true;
}

    private function getVideoDuration($filePath) {
        $probeScript = "$this->ffmpegProbePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath";
        return shell_exec("$probeScript");
    }

    private function updateDuration($duration, $videoId) {
        $duration   = (int)$duration;
       
        $hours = floor($duration / 3600);
        $mins = floor(($duration - ($hours*3600)) / 60);
        $secs = floor($duration % 60);
        
        $hours = ($hours < 1) ? "" : $hours . ":";
        $mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
        $secs = ($secs < 10) ? "0" . $secs : $secs;

        $duration = $hours . $mins . $secs;

        $query = $this->con->prepare("UPDATE videos set duration=:duration Where id=:videoId");
        $query->bindParam(":duration", $duration);
        $query->bindParam(":videoId", $videoId);

        $query->execute();
    }

    private function processData($videoData, $tempFilePath) {
        $videoType = pathInfo($tempFilePath, PATHINFO_EXTENSION);

        if (!$this->isValidSize($videoData)) {
            echo "File Is Too Large";
            return false;
        } else if (!$this->isValidType($videoType)) {
            echo "Invalid File Type";
            return false;
        } else if ($videoData["error"] != 0) {
            echo "There is a general error with your video" . $videoData["error"];
            return false;
        }

        return true;
    }

    private function isValidSize($videoData) {
        return $videoData["size"] <= $this->sizeLimit;
    }

    private function isValidType($type) {
        $lowercased = strtolower($type);
        return in_array($lowercased, $this->allowedTypes);
    }
}
?>
