<?php
require_once("includes/header.php");
if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}

$entityId = $_GET["id"];

$video = new Video($con, $entityId, "admin");
$detailsMessage ="";

if(isset($_POST["updateButton"])) {
    if(!$_POST["title"] == "" &&
    !$_POST["description"] == "" && 
    !$_POST["season"] == "" && 
    !$_POST["episode"] == ""){     
        $title = $_POST["title"];
        $description = $_POST["description"];
        $filePath = $_POST["filePath"];
        $season = $_POST["season"];
        $episode = $_POST["episode"];
        
        $video->updateVideo($title, $description, $filePath, $episode, $season);
            $detailsMessage = "<div class='alertSuccess'>
                                Details updated successfully!
                            </div>";
        } else {
            $detailsMessage = "<div class='alertError'>
                                Update error!
                               </div>";
        }
    }

 if(isset($_POST["deleteButton"])) {
    if($video->deleteVideo()) {
        if($isMovie =="0"){
            header("Location:entityAdmin.php?id=".$entityId);
        } else {    
            header("Location: indexAdmin.php");
        }
    } else {
        $detailsMessage = "<div class='alertError'>
                                Delete error!
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
             <link rel="stylesheet" type="text/css" href="assets/style/style.css" />
    </head>
    <body>
        
        <div class="settingsContainer column">
                <div class="header">
                <?php 
            if($video->getIsMovie() == "0"){
                $name = "tv show";
            } else {
                $name = "movie";
            }
        ?>
                <h1> Manage your <?php echo $name ?> </h1>
                </div>
                <form method="POST">
                 <h2> Title </h2>     
                            <input type="text" name="title" placeholder="<?php echo $video->getTitle(); ?>" value="<?php getInputValue("title"); ?>">
                            <h2> Description </h2>
                            <h4>
                            </h4>
                            <input type="textarea"  name="description" style="height:120px" placeholder="<?php echo $video->getDescription(); ?>" value="<?php getInputValue("description"); ?>">
                            <h2> File path </h2>
                            <?php echo $video->getFilePath(); ?>
                            <select name='filePath'>
                                <?php
                                $dirName = 'entities/videos';
                                $dir = opendir($dirName);

                                while($file = readdir($dir)) {
                                    if($file != '.' && $file != '..' && !is_dir($dirName.$file))
                                    {
                                        echo "<option value='$dirName/$file'> '$file' </option>";
                                    }
                                }

                                closedir($dir);
                                ?>
                            </select>
                            <?php 
                                if($video->getIsMovie() == "0"){
                            ?>
                            <h2> Episode </h2>
                            <?php echo $video->getEpisodeNumber(); ?>
                         <input type="number" name="episode" placeholder="episode" value="1">
                         <h2> Season </h2>
                            <?php echo $video->getSeasonNumber(); ?>
                         <input type="number" name="season" placeholder="season" value="1">
                 <?php 
                                }
                                ?>
                         <div class="message">
                     <?php echo $detailsMessage; ?>
                 </div>
                        <input type="submit" name="updateButton" value="UPDATE">
                        <input type="submit" name="deleteButton" value="DELETE">
                </form>
                <?php 
                    if($video->getIsMovie() == "0"){
                        $pageRedirection = "entityAdmin.php?id=".$entityId;
                    } else {
                    $pageRedirection = "indexAdmin.php";
                }
                ?>
                <a href="<?php echo $pageRedirection ?>" class="signInMessage">Return</a>
                </div>
            </div>

    </body>
</html>