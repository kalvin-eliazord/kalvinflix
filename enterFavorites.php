<?php
require_once("includes/header.php");

$user = new User($con, $userLoggedIn);
$userId = $user->getId();
$categoryFavorite = new CategoryFavorite($con, $userId);
$categoryMessage = "";

if(isset($_POST["deleteCategoryButton"])) {
    $categoryId = $_POST["category"];
    if($categoryFavorite->deleteCategoryFavorite($categoryId)){
        $categoryMessage = "<div class='alertSuccess'>
                                Category deleted successfully!
                            </div>";
    } else {
        $categoryMessage = "<div class='alertError'>
                             You can't delete what you don't have!
                        </div>";
    }
}

if(isset($_POST["addCategoryButton"])) {
    $categoryId = $_POST["category"];
    if($categoryFavorite->insertCategoryFavorite($categoryId)){
        $categoryMessage = "<div class='alertSuccess'>
                                Category added successfully!
                            </div>";
    } else {
        $categoryMessage = "<div class='alertError'>
                             You can't add the same category!
                        </div>";
    }
}

$actorFavorite = new ActorFavorite($con, $userId);
$actorMessage = "";

if(isset($_POST["deleteActorButton"])) {
    $actorId = $_POST["actor"];
    if($actorFavorite->deleteActorFavorite($actorId)){
        $actorMessage = "<div class='alertSuccess'>
                                Actor deleted successfully!
                            </div>";
    } else {
        $actorMessage = "<div class='alertError'>
                             You can't delete what you don't have!
                        </div>";
    }
}

if(isset($_POST["addActorButton"])) {
    $actorId = $_POST["actor"];
    if($actorFavorite->insertActorFavorite($actorId)){
        $actorMessage = "<div class='alertSuccess'>
                                Actor added successfully!
                            </div>";
    } else {
        $actorMessage = "<div class='alertError'>
                             You can't add the same actor!
                        </div>";
    }
}

$producerFavorite = new ProducerFavorite($con, $userId);
$producerMessage = "";

if(isset($_POST["deleteProducerButton"])) {
    $producerId = $_POST["producer"];
    if($producerFavorite->deleteProducerFavorite($producerId)){
        $producerMessage = "<div class='alertSuccess'>
                                Producer deleted successfully!
                            </div>";
    } else {
        $producerMessage = "<div class='alertError'>
                             You can't delete what you don't have!
                        </div>";
    }
}

if(isset($_POST["addProducerButton"])) {
    $producerId = $_POST["producer"];
    if($producerFavorite->insertProducerFavorite($producerId)){
        $producerMessage = "<div class='alertSuccess'>
                                Producer added successfully!
                            </div>";
    } else {
        $producerMessage = "<div class='alertError'>
                             You can't add the same producer!
                        </div>";
    }
}

?>
<div class="settingsContainer column">
     <div class="formSection">
        <form method="POST">
            <h2> Enter your favorites categories </h2>
            <select name='category'>
                            <?php 
                                $query = $con->prepare("SELECT * FROM categories");
                                $query->execute();
                                while($row = $query->fetch(PDO::FETCH_ASSOC)){       
                                    echo "<option value='$row[id]'> $row[name] </option>";
                                } 
                            ?>
                        </select>
                    <div class='enterFavorites'>
                        <?php 
                               $query = $con->prepare("SELECT * FROM categoriesFavorites WHERE userId=:userId");
                               $query->bindValue(":userId", $userId);
                               $query->execute();
                               $html = "Your favorites categories : ";
                               while($categoriesFavorites = $query->fetch(PDO::FETCH_ASSOC)){       
                                    $queryCategory = $con->prepare("SELECT * FROM categories WHERE id=:categoriesFavorites");
                                    $queryCategory->bindValue(":categoriesFavorites", $categoriesFavorites["categoryId"]);
                                    $queryCategory->execute();
    
                                    while($row = $queryCategory->fetch(PDO::FETCH_ASSOC)){
                                        echo $row["name"] . "/ ";
                                    } 
                                } 
                        ?>
                        <div class="message">
                            <?php
                                echo $categoryMessage;
                            ?>
                        </div>
                    </div>
                    <div class="enterFavorites">
                        <input type="submit" name="addCategoryButton" value="Add Category">
                        <input type="submit" name="deleteCategoryButton" value="Delete Category">
                    </div>
        </form>
    </div>
    
    <div class="formSection">

        <form method="POST">

        <h2> Enter your favorites actors </h2>

        <select name='actor'>
                            <?php 
                                $query = $con->prepare("SELECT * FROM actors");
                                $query->execute();
                                while($row = $query->fetch(PDO::FETCH_ASSOC)){       
                                    echo "<option value='$row[id]'> $row[fullName] </option>";
                                } 
                            ?>
                        </select>
                        <div class='enterFavorites'>
                        <?php 
                               $queryActorFavorite = $con->prepare("SELECT * FROM actorsFavorites WHERE userId=:userId");
                               $queryActorFavorite->bindValue(":userId", $userId);
                               $queryActorFavorite->execute();
                               $textFavorites = "Your favorites actors : ";
                               while($actorsFavorites = $queryActorFavorite->fetch(PDO::FETCH_ASSOC)){       
                                    $queryActor = $con->prepare("SELECT * FROM actors WHERE id=:actorsFavorites");
                                    $queryActor->bindValue(":actorsFavorites", $actorsFavorites["actorId"]);
                                    $queryActor->execute();
    
                                    while($row = $queryActor->fetch(PDO::FETCH_ASSOC)){
                                        echo $row["fullName"] . "/ ";
                                    } 
                                } 
                        ?>
                        <div class="message">
                            <?php
                                echo $actorMessage;
                            ?>
                        </div>
                    </div>
                    <div class="enterFavorites">
                       <input type="submit" name="addActorButton" value="Add Actor">
                        <input type="submit" name="deleteActorButton" value="Delete Actor">
                    </div>
        </form>

    </div>

    <div class="formSection">

        <form method="POST">

        <h2> Enter your favorites producers </h2>

        <select name='producer'>
                            <?php 
                                $query = $con->prepare("SELECT * FROM producers");
                                $query->execute();
                                while($row = $query->fetch(PDO::FETCH_ASSOC)){       
                                    echo "<option value='$row[id]'> $row[fullName] </option>";
                                } 
                            ?>
                        </select>
                        <div class='enterFavorites'>
                        <?php 
                               $queryFavoriteProducer = $con->prepare("SELECT * FROM producersFavorites WHERE userId=:userId");
                               $queryFavoriteProducer->bindValue(":userId", $userId);
                               $queryFavoriteProducer->execute();
                               $textFavorite = "Your favorites producers : ";
                               
                               while($producersFavorites = $queryFavoriteProducer->fetch(PDO::FETCH_ASSOC)){       
                                    $queryProducer = $con->prepare("SELECT * FROM producers WHERE id=:producersFavorites");
                                    $queryProducer->bindValue(":producersFavorites", $producersFavorites["producerId"]);
                                    $queryProducer->execute();
                                
                                    while($row = $queryProducer->fetch(PDO::FETCH_ASSOC)){
                                        echo $row["fullName"] . "/ ";
                                    } 
                                } 
                        ?>
                        <div class="message">
                            <?php
                                echo $producerMessage;
                            ?>
                        </div>
                    </div>
                    <div class="enterFavorites">
                        <input type="submit" name="addProducerButton" value="Add producer">
                        <input type="submit" name="deleteProducerButton" value="Delete Producer">
                    </div>

        </form>

    </div>
    </div>