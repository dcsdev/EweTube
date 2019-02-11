<?php
require_once("includes/header.php");
require_once("includes/DataAccess.php");


$arr = array(
    ":id" => "val1",
    ":name" => "val2"
);

DataAccess::Insert(null,"tbl", $arr);

?>

