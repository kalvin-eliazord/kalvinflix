<?php
require_once("includes/header.php");

?>
<div class="topBar">

    <div class="logoContainer">
        <a href="index.php">
            <img src="assets/images/logo.png" alt="Logo">
        </a>
    </div>

    <ul class="navLinks">
        <li><a href="index.php">Home</a></li>
        <li><a href="shows.php">TV Shows</a></li>
        <li><a href="movies.php">Movies</a></li>
        <li><a href="entitiesFavorites.php">Your favorites</a></li>
        <li><a href="mostViewsEntities.php">Most Views</a></li>

        <?php 
        $userAdmin = new User($con, $userLoggedIn);
        if($userAdmin->getIsAdmin() == 1){
            echo " <li><a href='indexAdmin.php'>Index Admin</a></li>";
        }
        ?> 
    </ul>
    
   <div class="rightItems">
        <a href="search.php">
            <i class="fas fa-search"></i>
        </a>

        <a href="profile.php">
            <i class="fas fa-user"></i>
        </a>

        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>

</div>