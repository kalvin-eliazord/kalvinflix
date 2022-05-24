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

    public function updateEntity($name, $thumbnail, $preview, $categoryId, $producerId){
        $query = $this->con->prepare("UPDATE entities SET name=:name, thumbnail=:thumbnail, preview=:preview,
        categoryId=:categoryId, producerId=:producerId WHERE id=:id"); 
        $query->bindValue(":name", $name);
        $query->bindValue(":thumbnail", $thumbnail);
        $query->bindValue(":preview", $preview);
        $query->bindValue(":categoryId", $categoryId);
        $query->bindValue(":producerId", $producerId);
        $query->bindValue(":id", $this->getId());
        $query->execute();

        return $query;
    }

    public function deleteEntity(){
        $query = $this->con->prepare("DELETE FROM entities WHERE id=:id"); 
        $query->bindValue(":id", $this->getId());
        $query->execute();

        return $query;
    }

    public function insertEntity($name, $thumbnail, $preview, $categoryId, $producerId){
        $query = $this->con->prepare("INSERT INTO entities (name, thumbnail, preview, categoryId, producerId)
                                      VALUES (:name, :thumbnail, :preview, :categoryId, :producerId)");
        $query->bindValue(":name", $name);
        $query->bindValue(":thumbnail", $thumbnail);
        $query->bindValue(":preview", $preview);
        $query->bindValue(":categoryId", $categoryId);
        $query->bindValue(":producerId", $producerId);
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

    public function getDescription() {
        return $this->sqlData["description"];
    }

    public function getProducerId() {
        return $this->sqlData["producerId"];
    }

    public function getVideos() {
        $query = $this->con->prepare("SELECT * FROM videos WHERE entityId=:id");
        $query->bindValue(":id", $this->getId());
        $query->execute();
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