<?php

require_once("../includes/config.php"); 
require_once("../includes/classes/DataAccess.php");
require_once("../includes/classes/User.php");

if (!isset($_POST["subscribingFrom"]) || !isset($_POST["subscribingTo"]) )  {
    return;
}

$userTo     = $_POST["subscribingTo"];
$userFrom   = $_POST["subscribingFrom"];

$params = array(
        "userTo"    => $userTo,
        "userFrom"  => $userFrom
);

$queryRowCount = DataAccess::SelectRowCount($con, "SELECT * FROM subscribers", $params);

if ($queryRowCount > 0 ) {
    
    $params = array(
        "userTo"    => $userTo,
        "userFrom"  => $userFrom
);
    DataAccess::Delete($con, "DELETE from subscribers", $params);
} else {    
    $values = array(
        "userTo" => $userTo,
        "userFrom" => $userFrom
    );

    DataAccess::Insert($this->con, "subscribers", $values);
    
}

    $userToObj = new User($con, $userTo);
    echo $userToObj->getSubscriberCount();


?>