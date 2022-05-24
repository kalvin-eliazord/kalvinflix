<?php
class ActorFavorite {

    private $con, $sqlData, $userId;

    public function __construct($con,$userId) {
        $this->con = $con;
        $this->userId = $userId;

        $query = $this->con->prepare("SELECT * FROM actorsFavorites WHERE userId=:userId");
        $query->bindValue(":userId", $userId);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteActorFavorite($actorId){
        if(!$this->checkIfExist($actorId)){
            return false;

        } else {
            $query = $this->con->prepare("DELETE FROM actorsFavorites WHERE userId=:userId AND actorId=:actorId"); 
            $query->bindValue(":actorId", $this->actorId);
            $query->bindValue(":userId", $this->userId);
            $query->execute();

            return $query;
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

    public function getId() {
        return $this->sqlData["id"];
    }

}
?>