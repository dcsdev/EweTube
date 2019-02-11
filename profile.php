<?php

require_once("includes/header.php");
require_once("includes/classes/ProfileGenerator.php");

if (isset($_GET["username"])) {
    $profileUsername = $_GET["username"];
} else {
    echo "No Username Specified";
    exit();
}

$profileGenerator = new ProfileGenerator($con, $userLoggedInInstance, $profileUsername);

echo $profileGenerator->create();

?>