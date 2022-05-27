<?php 
class Review{
    private $con, $entityId, $sqlData;

    public function __construct($con, $entityId) {
        $this->con = $con;
        $this->entityId = $entityId;
        $query = $this->con->prepare("SELECT * FROM reviews WHERE entityId=:entityId");
        $query->bindValue(":entityId", $this->entityId);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function insertReview($userId, $review){
        $query = $this->con->prepare("INSERT INTO reviews (userId, entityId, review)
                                        values (:userId, :entityId, :review) ");
        $query->bindValue(":userId", $userId);
        $query->bindValue(":entityId", $this->entityId);
        $query->bindValue(":review", $review);

        return $query->execute();
    }

    public function getReview(){
        if(isset($this->sqlData["review"])){
           return true;
        }
        
        return false;
    }

    public function getUserId(){
        if(isset($this->sqlData["userId"])){
            $query = $this->con->prepare("SELECT * FROM reviews WHERE entityId=:entityId");
            $query->bindValue(":entityId", $this->entityId);
            $query->execute();
            
        return $query->fetch(PDO::FETCH_ASSOC);
    }
        return false;
    }

}
