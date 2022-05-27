<?php 
include_once("includes/header.php");

if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}

$entityId = $_GET["id"];

$entity = new Entity($con, $entityId);
$detailsMessage = "";

if(isset($_POST["insertButton"])) {
    if(!$_POST["name"] == ""){
        $thumbnail = $_POST["thumbnail"];
        $preview = $_POST["preview"];
        $category = $_POST["category"];
        $producerId = $_POST["producerId"];
        $name = $_POST["name"];
        $entity->insertEntity($name, $thumbnail, $preview, $category, $producerId);
        $detailsMessage = "<div class='alertSuccess'>
                                    Details inserted successfully!
                                </div>";
    } else {
        $detailsMessage = "<div class='alertError'>
                                Insert error, please check the fields.
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
        <title>Insert entity</title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css" />
    </head>
    <body>
        
        <div class="settingsContainer column">
                <div class="header">
                <h1> Insert a entity </h1>
                </div>
                <h3>Name </h3>
                <form method="POST">
                    <input type="text" name="name" placeholder="Name" value="<?php getInputValue("name"); ?>">
                    <h3>Thumbnail </h3>
                    <select name='thumbnail'>
                                <?php
                                $dirName = 'entities/thumbnails-real-images';
                                $thumbnailsDir = opendir($dirName);

                                while($file = readdir($thumbnailsDir)) {
                                    if($file != '.' && $file != '..' && !is_dir($dirName.$file))
                                    {
                                        echo "<option value='$dirName/$file'> '$file' </option>";
                                    }
                                }

                                closedir($thumbnailsDir);
                                ?>
                                </select>
                                <h3>Preview</h3>
                                <select name='preview'>
                                    <?php
                                    $dirName = 'entities/previews';
                                    $previewsDir = opendir($dirName);

                                    while($file = readdir($previewsDir)) {
                                        if($file != '.' && $file != '..' && !is_dir($dirName.$file))
                                        {
                                            echo "<option value='$dirName/$file'> '$file' </option>";
                                        }
                                    }

                                    closedir($previewsDir);
                                    ?>
                                </select>
                                <h3>Category </h3>
                            <select name='category'>
                                <?php 
                                    $query = $con->prepare("SELECT * FROM categories");
                                    $query->execute();
                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){       
                                        echo "<option value='$row[id]'> $row[name] </option>";
                                    } 
                                ?>
                            </select>
                            <h3>Producer </h3>
                            <select name='producerId'>
                                <?php 
                                    $query = $con->prepare("SELECT * FROM producers");
                                    $query->execute();
                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){       
                                        echo "<option value='$row[id]'> $row[fullName] </option>";
                                    } 
                                ?>
                            </select>
                    <div class="message">
                        <?php echo $detailsMessage; ?>
                    </div>
                        <div class="settingsContainer">
                            <input type="submit" name="insertButton" value="INSERT">
                                </form>
                <a href="indexAdmin.php" class="signInMessage">Return</a>
                </div>
            </div>

    </body>
</html>
