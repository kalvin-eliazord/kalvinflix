<?php
class ActorFavorite {

    private $con, $userId;

    public function __construct($con,$userId) {
        $this->con = $con;
        $this->userId = $userId;
    }

    public function deleteActorFavorite($actorId){
        if(!$this->checkIfExist($actorId)){
            return false;

        } else {
            $query = $this->con->prepare("DELETE FROM actorsFavorites WHERE userId=:userId AND actorId=:actorId"); 
            $query->bindValue(":actorId", $actorId);
            $query->bindValue(":userId", $this->userId);
            $query->execute();

            return $query;
        }
    }

    public function getActorsFavoritesId(){
        $query = $this->con->prepare("SELECT actorId FROM actorsFavorites WHERE userId=:userId");
        $query->bindValue(":userId", $this->userId);
        $query->execute();

        if($query->rowCount() !== 0) {
            return $query;
        } else {
            return false;
        } 
    }

    private function checkIfExist($actorId){
        $query = $this->con->prepare("SELECT * FROM actorsFavorites
                                     WHERE actorId=:actorId AND userId=:userId");
        $query->bindValue(":actorId", $actorId);
        $query->bindValue(":userId", $this->userId);
        $query->execute();

        if($query->rowCount() == 1) {
            return true;
        }
    }

    public function insertActorFavorite($actorId){
        if($this->checkIfExist($actorId)){
            return false;
        } else {
            $query = $this->con->prepare("INSERT INTO actorsFavorites (actorId, userId)
                                        VALUES (:actorId, :userId)");
            $query->bindValue(":actorId", $actorId);
            $query->bindValue(":userId", $this->userId);
            $query->execute();

            return $query;
        }
    }

}
?>