<?php
class CategoryFavorite  {

    private $con, $userId, $sqlData;

    public function __construct($con, $userId) {
        $this->con = $con;
        $this->userId = $userId;

        $query = $this->con->prepare("SELECT * FROM categoriesFavorites WHERE userId=:userId");
        $query->bindValue(":userId", $this->userId);
        $query->execute();
        
        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCategoryFavorite($categoryId){
        if(!$this->checkIfExist($categoryId)){
            return false;

        } else {
            $query = $this->con->prepare("DELETE FROM categoriesFavorites 
                                          WHERE categoryId=:categoryId AND userId=:userId"); 
            $query->bindValue(":categoryId", $categoryId);
            $query->bindValue(":userId", $this->userId);
            $query->execute();
    
            return $query;
        }
    }

    private function checkIfExist($categoryId = null){
        $query = $this->con->prepare("SELECT * FROM categoriesFavorites
                                     WHERE categoryId=:categoryId AND userId=:userId");
        $query->bindValue(":categoryId", $categoryId);
        $query->bindValue(":userId", $this->userId);
        $query->execute();

        if($query->rowCount() == 1) {
            return true;
        }
    }

    public function insertCategoryFavorite($categoryId){
        if($this->checkIfExist($categoryId)){
            return false;
        } else {
            $query = $this->con->prepare("INSERT INTO categoriesFavorites (categoryId, userId)
            VALUES (:categoryId, :userId)");
            $query->bindValue(":categoryId", $categoryId);
            $query->bindValue(":userId", $this->userId);
            $query->execute();

            return $query;
        }
    }

    public function getId() {
        return $this->sqlData["id"];
    }

}
?>