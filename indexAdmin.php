<?php
require_once("includes/header.php");

$containers = new CategoryContainers($con, $userLoggedIn);
echo $containers->showAllCategoriesForAdmin();
?>


