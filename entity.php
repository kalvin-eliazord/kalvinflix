<?php
require_once("includes/header.php");

if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}
$entityId = $_GET["id"];
$entity = new Entity($con, $entityId);

$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview->createPreviewVideo($entity);
?>

<div class="season"> 
    <a href='entityReviews.php?id=<?php echo $entityId ?>'> Click here to review </a>
</div>
<div class="season"> 
    <a href='contact.php?id=<?php echo $entityId ?>'> Contact admin </a>
</div>

<?php
$seasonProvider = new SeasonProvider($con, $userLoggedIn);
echo $seasonProvider->create($entity);

$categoryContainers = new CategoryContainers($con, $userLoggedIn);
echo $categoryContainers->showCategory($entity->getCategoryId(), "You might also like");
?>

