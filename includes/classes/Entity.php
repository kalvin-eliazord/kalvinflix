<?php
class Entity {

    private $con, $sqlData;

    public function __construct($con, $input) {
        $this->con = $con;

        if(is_array($input)) {
            $this->sqlData = $input;
        }
        else {
            $query = $this->con->prepare("SELECT * FROM entities WHERE id=:id");
            $query->bindValue(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function updateEntity($name, $thumbnail, $preview, $id){
        $query = $this->con->prepare("UPDATE entities SET name=:name, thumbnail=:thumbnail, preview=:preview,
        categoryId:categoryId WHERE entities.id=:id"); 
        $query->bindValue(":name", $this->getName());
        $query->bindValue(":thumbnail", $this->getThumbnail()());
        $query->bindValue(":preview", $this->getPreview());
        $query->bindValue(":categoryId", $this->getCategoryId());
        $query->bindValue(":id", $this->getId());
        $query->execute();

        return $query;
    }

    public function deleteEntity($id){
        $query = $this->con->prepare("DELETE * FROM entities WHERE id=:id"); 
        $query->bindValue(":id", $this->getId());
        $query->execute();

        return $query;
    }

    public function createEntity($name, $thumbnail, $preview){
        $query = $this->con->prepare("UPDATE entities SET name=:name, thumbnail=:thumbnail, preview=:preview,
        categoryId:categoryId WHERE entities.id=:id"); 
        $query->bindValue(":name", $this->getName());
        $query->bindValue(":thumbnail", $this->getThumbnail()());
        $query->bindValue(":preview", $this->getPreview());
        $query->bindValue(":categoryId", $this->getCategoryId());
        $query->bindValue(":id", $this->getId());
        $query->execute();

        return $query;
    }

    public function getId() {
        return $this->sqlData["id"];
    }

    public function getName() {
        return $this->sqlData["name"];
    }

    public function getThumbnail() {
        return $this->sqlData["thumbnail"];
    }

    public function getPreview() {
        return $this->sqlData["preview"];
    }

    public function getCategoryId() {
        return $this->sqlData["categoryId"];
    }

    public function getSeasons() {
        $query = $this->con->prepare("SELECT * FROM videos WHERE entityId=:id
                                    AND isMovie=0 ORDER BY season, episode ASC");
        $query->bindValue(":id", $this->getId());
        $query->execute();

        $seasons = array();
        $videos = array();
        $currentSeason = null;
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            
            if($currentSeason != null && $currentSeason != $row["season"]) {
                $seasons[] = new Season($currentSeason, $videos);
                $videos = array();
            }

            $currentSeason = $row["season"];
            $videos[] = new Video($this->con, $row, null);

        }

        if(sizeof($videos) != 0) {
            $seasons[] = new Season($currentSeason, $videos);
        }

        return $seasons;
    }

}
?>