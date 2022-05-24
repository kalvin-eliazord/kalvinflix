<?php
require_once("includes/header.php");

$containersFavorites = new CategoryContainers($con, $userLoggedIn);
echo $containersFavorites->showAllCategoriesFavorites();
?>
