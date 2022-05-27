<?php include_once("includes/header.php");

if(!isset($_GET["id"])){
    ErrorMessage::show("No ID passed into page");
}

$entityId = $_GET["id"];
$entity = new Entity($con, $entityId);
$review = new Review($con, $entityId);
$user = new User($con, $userLoggedIn);
$userId = $user->getId();
$detailsMessage ="";

if(isset($_POST["insertButton"])) {
    $userReview = $_POST["review"];
    if($review->insertReview($userId, $userReview)) {
            header("Location:entityReviews.php?id=".$entityId);
    } else {
        $detailsMessage = "<div class='alertError'>
                                Insert review error!
                            </div>";
    }
}

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}  
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Insert Reviews</title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css" />
    </head>
    <body>
        
        <div class="settingsContainer column">
                <div class="header">
                <h1> Insert a review for <?php echo $entity->getName(); ?></h1>
                </div>
                <form method="POST">
                            <textarea name="review" value="<?php getInputValue("review"); ?>" required> </textarea>
                 <div class="message">
                     <?php echo $detailsMessage; ?>
                 </div>
                 <div class="settingsContainerAdmin">                    
                    <input type="submit" name="insertButton" value="INSERT">
                    <?php echo "<a href='entity.php?id=$entityId'"?> <a class="signInMessage">Return</a>
                    <?php
                    if($review->getReview()){
                        $query = $con->prepare("SELECT * FROM reviews WHERE entityId=:entityId");
                        $query->bindValue(":entityId", $entityId);
                        $query->execute();
                    
                        while($row = $query->fetch(PDO::FETCH_ASSOC)){
                            $html = "<h2> ";
                            $username = $user->getUsernameById($row["userId"]);
                            echo $html .= $username.
                                    "</h2>
                                    <div class='comment'>
                                        ".$row["review"]."      
                                    </div>";                       
                        }       
                    } else {
                        echo "Be the first comment!";
                    }
                     
                    ?>
                    </div>
                </div>
                </form>
                
                </div>
            </div>

    </body>
</html>