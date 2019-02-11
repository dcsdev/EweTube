# EweTube
In order to use this project, you will need to provide you own secrets.php file, or fill in your connection details.
```php
<?php
ob_start();
session_start();

require_once("secrets.php");

date_default_timezone_set("America/New_York");

try {

    $os_ver    = PHP_OS;
    $os_ver    = strtolower($os_ver);

    if ($os_ver == "linux") {
        $con = new PDO(Secrets::$CONNECTION_STRING_REMOTE_SERVER, 
                        Secrets::$CONNECTION_STRING_REMOTE_USERNAME, 
                        Secrets::$CONNECTION_STRING_REMOTE_PASSWORD);
    } else  {
        $con = new PDO(Secrets::$CONNECTION_STRING_LOCAL_SERVER, 
                        Secrets::$CONNECTION_STRING_LOCAL_USERNAME, 
                        Secrets::$CONNECTION_STRING_LOCAL_PASSWORD);
    }

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(PDOException $e) {
    echo "Connection failed" . $e->getMessage();
}
?>
