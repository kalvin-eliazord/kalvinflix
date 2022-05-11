<?php
$hideNav = "hideNav";
require_once("includes/header.php");

if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}
$entityId = $_GET["id"];
$entity = new Entity($con, $entityId);

$seasonProvider = new SeasonProvider($con, $userLoggedIn);
echo $seasonProvider->createAdmin($entity);
?>