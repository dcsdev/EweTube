# EweTube
In order to use this project, you will need to add a config.php file in the includes directory similar to the below:
```php
<?php
ob_start();

date_default_timezone_set("America/New_York");

try {
    $con = new PDO("mysql:dbname=db;host=server", "", "");    
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(PDOException $e) {
    echo "Connection failed" . $e->getMessage();
}
?>
