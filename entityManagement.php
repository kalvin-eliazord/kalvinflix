<?php
$hideNav = "hideNav";
require_once("includes/header.php");
if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}

$entityId = $_GET["id"];

$entity = new Entity($con, $entityId);
$detailsMessage = "";
if(isset($_POST["updateButton"])) {
    if(!$_POST["name"] == ""){
        $thumbnail = $_POST["thumbnail"];
        $preview = $_POST["preview"];
        $category = $_POST["category"];
        $producerId = $_POST["producerId"];
        $name = $_POST["name"];
        $entity->updateEntity($name, $thumbnail, $preview, $category, $producerId);
        header("Location: entityManagement.php?id=".$entityId);
        $detailsMessage = "<div class='alertSuccess'>
                                Details updated successfully!
                            </div>";
        } else {
            $detailsMessage = "<div class='alertError'>
                                Update error, please check the fields.
                            </div>";
        }
    }

 if(isset($_POST["deleteButton"])) {

    if($entity->deleteEntity()) {
        header("Location: indexAdmin.php");
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
                            <?php echo $entity->getName(); ?>
                        </td>
                        <td class ="table"> 
                            <input type="text" name="name" placeholder="Name" value="<?php getInputValue("name"); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class ="table">
                            <?php echo $entity->getThumbnail(); ?>
                        </td>
                        <td>
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
                        </td>
                    </tr>
                    <tr>
                        <td class ="table">
                            <?php echo $entity->getPreview(); ?>
                      </td>
                        <td>
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
                        </td>
                    </tr>
                    <tr>
                        <td class ="table">
                            <?php
                             $categoryId = $entity->getCategoryId();
                             $query = $con->prepare("SELECT * FROM categories WHERE categories.id=:categoryId");
                                $query->bindValue(":categoryId",$categoryId);
                                $query->execute();
                                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                                    echo $row["name"];
                                }
                                
                             ?>
                        </td>
                        <td>
                        <select name='category'>
                            <?php 
                                $query = $con->prepare("SELECT * FROM categories");
                                $query->execute();
                                while($row = $query->fetch(PDO::FETCH_ASSOC)){       
                                    echo "<option value='$row[id]'> $row[name] </option>";
                                } 
                            ?>
                        </select>
                        </td>
                        <tr>
                        <td class ="table">
                            <?php
                            $producerId = $entity->getProducerId();
                             $query = $con->prepare("SELECT * FROM producers WHERE id=:producerId");
                                $query->bindValue(":producerId",$producerId);
                                $query->execute();
                                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                                    echo $row["fullName"];
                                }
                                
                             ?>
                        </td>
                        <td>
                        <select name='producerId'>
                            <?php 
                                $query = $con->prepare("SELECT * FROM producers");
                                $query->execute();
                                while($row = $query->fetch(PDO::FETCH_ASSOC)){       
                                    echo "<option value='$row[id]'> $row[fullName] </option>";
                                } 
                            ?>
                        </select>
                        </td>
                    </tr>
                 </table>
                 <div class="message">
                     <?php echo $detailsMessage; ?>
                 </div>
                    <div class="settingsContainer">
                        <input type="submit" name="updateButton" value="UPDATE">
                        <input type="submit" name="deleteButton" value="DELETE">
                    </div>
                </form>
                <a href="indexAdmin.php" class="signInMessage">Return</a>
            </div>
        </div>
    </body>
</html>