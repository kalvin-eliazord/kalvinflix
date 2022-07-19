<?php
class ProducerFavorite {

    private $con, $userId;

    public function __construct($con, $userId) {
        $this->con = $con;
        $this->userId = $userId;
    }

    public function deleteProducerFavorite($producerId){
        if(!$this->checkIfExist($producerId)){
            return false;

        } else {
            $query = $this->con->prepare("DELETE FROM producersFavorites 
                                          WHERE producerId=:producerId AND userId=:userId"); 
            $query->bindValue(":producerId", $producerId);
            $query->bindValue(":userId", $this->userId);
            $query->execute();
    
            return $query;
        }
    }

    public function getProducersFavoritesId(){
        $userId = $this->userId;
        $query = $this->con->prepare("SELECT * FROM producersFavorites WHERE userId=:userId");
        $query->bindValue(":userId", $userId);
        $query->execute();

        if($query->rowCount() !== 0) {
            return $query;
        } else {
            return false;
        }    
    }

    private function checkIfExist($producerId){
        $query = $this->con->prepare("SELECT * FROM producersFavorites
                                     WHERE producerId=:producerId AND userId=:userId");
        $query->bindValue(":producerId", $producerId);
        $query->bindValue(":userId", $this->userId);
        $query->execute();

        if($query->rowCount() == 1) {
            return true;
        }
    }

    public function insertProducerFavorite($producerId){
        if($this->checkIfExist($producerId)){
            return false;
        } else {
            $query = $this->con->prepare("INSERT INTO producersFavorites (producerId, userId)
            VALUES (:producerId, :userId)");
            $query->bindValue(":producerId", $producerId);
            $query->bindValue(":userId", $this->userId);
            $query->execute();

            return $query;
        }
    }  

}
?>