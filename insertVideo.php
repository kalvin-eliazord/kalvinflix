<?php 
require_once("includes/header.php");

if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}

$entityId = $_GET["id"];

$video = new Video($con, $entityId, "admin");
$detailsMessage ="";

if(isset($_POST["insertButton"])) {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $filePath = $_POST["filePath"];

    if($video->getEntityId()){
        $isMovie = $video->getIsMovie();
        $season = $_POST["season"];
        $episode = $_POST["episode"];
    } else {
        $isMovie = $_POST["isMovie"];
        $season = "1";
        $episode = "1";
    }
    
    if($video->insertVideo($title, $description, $filePath, $isMovie, $season, $episode, $entityId)) {
        if($isMovie =="0"){
            header("Location:entityAdmin.php?id=".$entityId);
        } else {    
            header("Location: indexAdmin.php");
    }
    } else {
        $detailsMessage = "<div class='alertError'>
                                Insert error!
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
        <title>Insert Video</title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css" />
    </head>
    <body>
        
        <div class="settingsContainer column">
                <div class="header">
                <h1> Insert a video </h1>
                </div>
                
                <h2> Title </h2>
                <form method="POST">
                            <input type="text" name="title" placeholder="Title" value="<?php getInputValue("title"); ?>" required>
                            <h2> Description </h2>
                            <input type="text"  name="description" style="height:120px"; width:200px value="<?php getInputValue("description"); ?>" required>
                            <h2> File Path </h2>
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
                                <?php 
                                    if(!$video->getId()){
                                ?>
                                    </select>
                                    <h2> Is a movie or a Tv Show </h2>
                                    <select name='isMovie'>
                                        <option value=1> It's a movie </option>
                                        <option value=0> It's a TV Show </option>
                                    </select>
                                <?php 
                                    }
                                 ?>

                           <?php 
                            if($video->getEntityId()){
                                ?>
                            <h2> Episode </h2>
                                 <input type="number" name="episode" placeholder="episode" value="<?php getInputValue("episode"); ?>" required>
                                <h2> Season </h2>
                                <input type="number" name="season" placeholder="season" value="<?php getInputValue("season"); ?>" required>
                            <?php 
                            } 
                           ?>
                        

                 <div class="message">
                     <?php echo $detailsMessage; ?>
                 </div>
                <input type="submit" name="insertButton" value="INSERT">
                </form>
                <?php 
                if($video->getIsMovie()){
                    if($video->getIsMovie() == "0"){
                        $pageRedirection = "entityAdmin.php?id=".$entityId;
                    }
                } else {
                    $pageRedirection = "indexAdmin.php";
                }
                ?>
                <a href="<?php echo $pageRedirection ?>" class="signInMessage">Return</a>
                </div>
            </div>

    </body>
</html>
