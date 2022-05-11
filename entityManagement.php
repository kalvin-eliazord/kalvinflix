<?php
$hideNav = "hideNav";
require_once("includes/header.php");
if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}

$entityId = $_GET["id"];

$entity = new Entity($con, $entityId);

if(isset($_POST["createButton"])) { // a coder
        
    $name = $_POST["name"];
    $thumbnail = $_POST["thumbnail"]; // on voudra une liste deroulante ou on peut choisir les thumbnail presents en local et pour preview
    $preview = $_POST["preview"];
    $category = $_POST["category"];

    $success = $entity->updateEntity($name, $thumbnail, $preview, $category, $entityId);

    if($success) {
        header("Location: indexAdmin.php");
    } else {
        echo "error!";
    }
}

if(isset($_POST["updateButton"])) {
        
        $name = $_POST["name"];
        $thumbnail = $_POST["thumbnail"]; // on voudra une liste deroulante ou on peut choisir les thumbnail presents en local et pour preview
        $preview = $_POST["preview"];
        $category = $_POST["category"];

        $success = $entity->updateEntity($name, $thumbnail, $preview, $category, $entityId);

        if($success) {
            header("Location: indexAdmin.php");
        } else {
            echo "error!";
        }
    }

 if(isset($_POST["deleteButton"])) {
    $success = $entity->deleteEntity($entityId);

    if($success) {
        header("Location: indexAdmin.php");
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
                            <?php echo $entity->getName(); ?>
                        </td>
                        <td class ="table"> 
                            <input type="text" name="name" placeholder="Name" value="<?php getInputValue("name"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class ="table">
                            <?php echo $entity->getThumbnail(); ?>
                        </td>
                        <td>
                            <input type="list" name="thumbnail" placeholder="Thumbnail" value="<?php getInputValue("thumbnail"); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class ="table">
                            <?php echo $entity->getPreview(); ?>
                      </td>
                        <td>
                         <input type="text" name="preview" placeholder="Preview" value="<?php getInputValue("preview"); ?>" required>
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
                                    echo $row["id"]." - ".$row["name"];
                                }
                                
                             ?>
                        </td>
                        <td>
                        <select name='category'>
                            <?php 
                                $query = $con->prepare("SELECT * FROM categories");
                                $query->execute();
                                while($row = $query->fetch(PDO::FETCH_ASSOC)){       
                                    echo "<option value='$row[id]'> $row[id] - $row[name] </option>";
                                } 
                            ?>
                        </select>
                        </td>
                    </tr>
                 </table> 
                    <input type="submit" name="createButton" value="CREATE">
                    <input type="submit" name="submitButton" value="UPDATE">
                    <input type="submit" name="deleteButton" value="DELETE">
                </form>
                <a href="indexAdmin.php" class="signInMessage">Retour</a>
            </div>

        </div>

    </body>
</html>