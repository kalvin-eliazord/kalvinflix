<?php
class CategoryContainers {

    private $con, $username;

    public function __construct($con, $username) {
        $this->con = $con;
        $this->username = $username;
    }

    public function showAllCategoriesMostViews() {
        if($this->getCategoriesFavoritesId()){
            $queryText = "SELECT * FROM categories ORDER BY id in (";

            $tCategoriesId = array();
            foreach($this->getCategoriesFavoritesId() as $categoryId){
                array_push($tCategoriesId, $categoryId["categoryId"]);
            }

            $queryText .= implode(", ",$tCategoriesId) . ") DESC";
            $query = $this->con->prepare($queryText);
        } else {
            $query = $this->con->prepare("SELECT * FROM categories");
        }
        
        $query->execute();
        $html = "<div class='previewCategories'>";
    
        while($categoriesId = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryMostViewsHtml($categoriesId, null, true, true);
        }

        return $html . "</div>";
    }

    public function showAllCategoriesForAdmin() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();
        
        $html = "<div class='previewCategoriesAdmin'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtmlAdmin($row, null, true, true);
        }

        return $html . "</div>";
    }

    private function getUserId(){
        $queryUser = $this->con->prepare("SELECT * FROM users WHERE username=:username");
        $queryUser->bindValue(":username", $this->username);
        $queryUser->execute();
        $userId = "";
        while($row = $queryUser->fetch(PDO::FETCH_ASSOC)){
            $userId = $row["id"];
        }

        return $userId;
    }

    private function getActorsFavoritesId(){
        $userId = $this->getUserId();
        $query = $this->con->prepare("SELECT actorId FROM actorsFavorites WHERE userId=:userId");
        $query->bindValue(":userId", $userId);
        $query->execute();

        if($query->rowCount() !== 0) {
            return $query;
        } else {
            return false;
        } 
    }

    private function getEntitiesIdFromActorId(){
        if($this->getActorsFavoritesId()){
            $tActorsId = array();
            foreach($this->getActorsFavoritesId() as $actorId){
                array_push($tActorsId, $actorId["actorId"]);
            }

            $sqlQuery = "SELECT * FROM roles WHERE actorId IN (";
            $sqlQuery .= implode(", ",$tActorsId) . ") ";
            $query = $this->con->prepare($sqlQuery);
            $query->execute();

            return $query;
        } else {
            return false;
        }
       
    }

    private function getProducersFavoritesId(){
        $userId = $this->getUserId();
        $query = $this->con->prepare("SELECT * FROM producersFavorites WHERE userId=:userId");
        $query->bindValue(":userId", $userId);
        $query->execute();

        if($query->rowCount() !== 0) {
            return $query;
        } else {
            return false;
        }    
    }

    private function getCategoriesFavoritesId(){
        $userId = $this->getUserId();
        $query = $this->con->prepare("SELECT * FROM categoriesFavorites WHERE userId=:userId");
        $query->bindValue(":userId", $userId);
        $query->execute();

        if($query->rowCount() !== 0) {
            return $query;
        } else {
            return false;
        }
    }

   public function showAllCategoriesFavorites(){
    if($this->getCategoriesFavoritesId()){
        $queryText = "SELECT * FROM categories ORDER BY id in (";
        
        $tCategoriesId = array();
        foreach($this->getCategoriesFavoritesId() as $categoryId){
            array_push($tCategoriesId, $categoryId["categoryId"]);
        }
        
        $queryText .= implode(", ",$tCategoriesId) . ")";
        $query = $this->con->prepare($queryText);
        $query->execute();
        $html = "<div class='previewCategories'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryFavoriteHtml($row, null, true, true);
        }

        return $html . "</div>";

        } 
   }

    public function showAllCategories() {
        if($this->getCategoriesFavoritesId()){
            $queryText = "SELECT * FROM categories ORDER BY id in (";

            $tCategoriesId = array();
            foreach($this->getCategoriesFavoritesId() as $categoryId){
                array_push($tCategoriesId, $categoryId["categoryId"]);
            }

            $queryText .= implode(", ",$tCategoriesId) . ") DESC";
            $query = $this->con->prepare($queryText);
        } else {
            $query = $this->con->prepare("SELECT * FROM categories");
        }
        
        $query->execute();
        $html = "<div class='previewCategories'>";
    
        while($categoriesId = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($categoriesId, null, true, true);
        }

        return $html . "</div>";
    }

    public function showTVShowCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>
                    <h1>TV Shows</h1>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, true, false);
        }

        return $html . "</div>";
    }

    public function showMovieCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>
                    <h1>Movies</h1>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, false, true);
        }

        return $html . "</div>";
    }
    
    public function showCategory($categoryId, $title = null) {
        $query = $this->con->prepare("SELECT * FROM categories WHERE id=:id");
        $query->bindValue(":id", $categoryId);
        $query->execute();

        $html = "<div class='previewCategories noScroll'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, $title, true, true);
        }

        return $html . "</div>";
    }

    private function getCategoryMostViewsHtml($sqlData, $title, $tvShows, $movies) {
        $categoryId = $sqlData["id"];
        $title = $title == null ? $sqlData["name"] : $title;

        if($tvShows && $movies) {
            $entities = EntityProvider::getEntitiesMostViews($this->con, $categoryId, 30);
            
        }
        else if($tvShows) {
            $entities = EntityProvider::getTVShowEntities($this->con, $categoryId, 30);
            
        }
        else {
            $entities = EntityProvider::getMoviesEntities($this->con, $categoryId, 30);
            
        }
    
         if(sizeof($entities) == 0) {
         return;
        }
    
        $entitiesHtml = "";
        $previewProvider = new PreviewProvider($this->con, $this->username);
        foreach($entities as $entity) {
            $entitiesHtml .= $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>
                    <a href='category.php?id=$categoryId'>
                        <h3>$title</h3>
                    </a>

                    <div class='entities'>
                        $entitiesHtml
                    </div>
                </div>";
    }

    private function getCategoryHtml($sqlData, $title, $tvShows, $movies) {
        $categoryId = $sqlData["id"];
        $title = $title == null ? $sqlData["name"] : $title;

        if($tvShows && $movies) {
            $entities = EntityProvider::getEntities($this->con, $categoryId, 30);
            
        }
        else if($tvShows) {
            $entities = EntityProvider::getTVShowEntities($this->con, $categoryId, 30);
            
        }
        else {
            $entities = EntityProvider::getMoviesEntities($this->con, $categoryId, 30);
            
        }
    
         if(sizeof($entities) == 0) {
         return;
        }
    
        $entitiesHtml = "";
        $previewProvider = new PreviewProvider($this->con, $this->username);
        foreach($entities as $entity) {
            $entitiesHtml .= $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>
                    <a href='category.php?id=$categoryId'>
                        <h3>$title</h3>
                    </a>

                    <div class='entities'>
                        $entitiesHtml
                    </div>
                </div>";
    }

    private function getCategoryFavoriteHtml($sqlData, $title, $tvShows, $movies){
        $categoryId = $sqlData["id"];
        $title = $title == null ? $sqlData["name"] : $title;

        if($this->getProducersFavoritesId() && $this->getEntitiesIdFromActorId()){
            $entities = EntityProvider::getEntitiesByProducersAndActorsFavorites($this->con, $categoryId, 
                $this->getProducersFavoritesId(),  $this->getEntitiesIdFromActorId());

        } else if ($this->getProducersFavoritesId() && !$this->getEntitiesIdFromActorId()){
            $entities = EntityProvider::getEntitiesByProducersFavorites($this->con, $categoryId, 
            $this->getProducersFavoritesId());

        } else if($this->getEntitiesIdFromActorId() && !$this->getProducersFavoritesId()) {
            $entities = EntityProvider::getEntitiesByActorsFavorites($this->con, $categoryId,
            $this->getEntitiesIdFromActorId());

        } else {
            
            echo "<div class='category'>
                    You don't have any favorites!
                    <a href='enterFavorites.php'> Click here to enter your favorites!</a>
                 </div>";
        }

        if(sizeof($entities) == 0) {
            return;
           }
       
           $entitiesHtml = "";
           $previewProvider = new PreviewProvider($this->con, $this->username);
           foreach($entities as $entity) {
               $entitiesHtml .= $previewProvider->createEntityPreviewSquare($entity);
           }
   
           return "<div class='category'>
                       <a href='category.php?id=$categoryId'>
                           <h3>$title</h3>
                       </a>
   
                       <div class='entities'>
                           $entitiesHtml
                       </div>
                   </div>";
        
    }

    private function getCategoryHtmlAdmin($sqlData, $title, $tvShows, $movies) {
        $categoryId = $sqlData["id"];
        $title = $title == null ? $sqlData["name"] : $title;

        if($tvShows && $movies) {
            $entities = EntityProvider::getEntities($this->con, $categoryId, 200);
        }
        else if($tvShows) {
            $entities = EntityProvider::getTVShowEntities($this->con, $categoryId, 200);
        }
        else {
            $entities = EntityProvider::getMoviesEntities($this->con, $categoryId, 200);
        }

        if(sizeof($entities) == 0) {
            return;
        }

        $entitiesHtml = "";
        $previewProvider = new PreviewProvider($this->con, $this->username);
        foreach($entities as $entity) {
            $entitiesHtml .= $previewProvider->createEntityPreviewSquareAdmin($entity);
        }

        return "<div class='categoryAdmin'>
                    <a href='category.php?id=$categoryId'>
                        <h3>$title</h3>
                    </a>

                    <div class='entities'>
                        $entitiesHtml
                    </div>
                </div>";
    }

}
?>