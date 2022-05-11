<?php
$hideNav = "hideNav";
require_once("includes/header.php");
if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}

$entityId = $_GET["id"];

$video = new Video($con, $entityId, "admin");

if(isset($_POST["createButton"])) { 
        
    $title = $_POST["title"];
    $description = $_POST["description"]; // mettre une plus grande area text
    $filePath = $_POST["filePath"];
    $season = $_POST["season"];
    $episode = $_POST["episode"];

    $success = $video->createVideo($title, $description, $filePath, $season, $episode);

    if($success) {
        header("Location: videoAdmin.php?id=".$video->getEntityId()."");
    } else {
        echo "error!";
    }
}

if(isset($_POST["updateButton"])) {
        
        $title = $_POST["title"];
        $description = $_POST["description"]; 
        $filePath = $_POST["filePath"];
        $season = $_POST["season"];
        $episode = $_POST["episode"];

        $success = $video->updateVideo($title, $description, $filePath, $season, $episode);

        if($success) {
            echo "Updating done!"; 
            header("Location: videoAdmin.php?id=".$video->getEntityId()."");
        } else {
            echo "error!";
        }
    }

 if(isset($_POST["deleteButton"])) {
    $success = $video->deleteVideo();

    if($success) {
        header("Location: videoAdmin.php?id=".$video->getEntityId()."");
    } else {
        echo "error!";
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
        <title>Manage entity</title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css" />
    </head>
    <body>
        
        <div class="signInContainer">

            <div class="column">

                <div class="header">
                    <img src="assets/images/logo.png" title="Logo" alt="Site logo" />
                </div>

                <form method="POST">
                 <table>
                     <tr>
                        <td class ="table">
                            Actual Data:
                        </td>
                        <td class ="table"> 
                            New Data:
                        </td>
                    </tr>
                    <tr>
                        <td class ="table">
                            <?php echo $video->getTitle(); ?>
                        </td>
                        <td class ="table"> 
                            <input type="text" name="title" placeholder="Title" value="<?php getInputValue("title"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class ="table">
                            <?php echo $video->getDescription(); ?>
                        </td>
                        <td>
                            <input type="textarea"  name="description" placeholder="Description" value="<?php getInputValue("description"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class ="table">
                            <?php echo $video->getFilePath(); ?>
                      </td>
                        <td>
                         <input type="text" name="filePath" placeholder="FilePath" value="<?php getInputValue("filePath"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class ="table">
                            <?php echo $video->getSeasonNumber(); ?>
                      </td>
                        <td>
                         <input type="text" name="season" placeholder="season" value="<?php getInputValue("season"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class ="table">
                            <?php echo $video->getEpisodeNumber(); ?>
                      </td>
                        <td>
                         <input type="number" name="episode" placeholder="episode" value="<?php getInputValue("episode"); ?>" required>
                        </td>
                    </tr>
                 </table> 
                    <input type="submit" name="createButton" value="CREATE">
                    <input type="submit" name="updateButton" value="UPDATE">
                    <input type="submit" name="deleteButton" value="DELETE">
                </form>
                <a href="entityAdmin.php?id=<?php echo $video->getEntityId()?>" class="signInMessage">Retour</a>
            </div>
        </div>

    </body>
</html>