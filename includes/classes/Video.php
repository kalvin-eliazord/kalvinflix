<?php
class Video {
    private $con, $sqlData, $entity;

    public function __construct($con, $input, $admin) {
        $this->con = $con;
        
        if($admin!=null){
            $query = $this->con->prepare("SELECT * FROM videos WHERE entityId=:id");
            $query->bindValue(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        } else {
            if(is_array($input)) {
                $this->sqlData = $input;
            }
            else {
                $query = $this->con->prepare("SELECT * FROM videos WHERE id=:id");
                $query->bindValue(":id", $input);
                $query->execute();

                $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
            }
    }
        if(isset($this->sqlData["entityId"])){
            $this->entity = new Entity($con, $this->sqlData["entityId"]);
        }
    }

    public function updateVideo($title, $description, $filePath, $episode, $season){
        $query = $this->con->prepare("UPDATE videos SET title=:title, description=:description, filePath=:filePath,
        isMovie=:isMovie, episode=:episode, season=:season WHERE videos.id=:id"); 
        $query->bindValue(":title", $title);
        $query->bindValue(":description", $description);
        $query->bindValue(":filePath",  $filePath);
        $query->bindValue(":isMovie", $this->getIsMovie());
        $query->bindValue(":episode", $episode);
        $query->bindValue(":season", $season);
        $query->bindValue(":id", $this->getId());
        $query->execute();

        return $query;
    }

    public function deleteVideo(){
        $query = $this->con->prepare("DELETE FROM videos WHERE id=:id"); 
        $query->bindValue(":id", $this->getId());
        $query->execute();

        return $query;
    }

    public function insertVideo($title, $description, $filePath, $isMovie, $season, $episode, $entityId){
        $query = $this->con->prepare("INSERT INTO videos (title, description, filePath, isMovie, uploadDate, views, season, episode, entityId)
                                      VALUES (:title, :description, :filePath, :isMovie, now(), 0,  :season, :episode, :entityId)");
        $query->bindValue(":title", $title);
        $query->bindValue(":description", $description);
        $query->bindValue(":filePath", $filePath);
        $query->bindValue(":isMovie", $isMovie);
        $query->bindValue(":episode", $episode);
        $query->bindValue(":season", $season);
        $query->bindValue(":entityId", $entityId);
        $query->execute();

        return $query;
    }

    public function getId() {
        return $this->sqlData["id"];
    }

    public function getTitle() {
        return $this->sqlData["title"];
    }

    public function getDescription() {
        return $this->sqlData["description"];
    }

    public function getFilePath() {
        return $this->sqlData["filePath"];
    }

    public function getThumbnail() {
        return $this->entity->getThumbnail();
    }

    public function getEpisodeNumber() {
        return $this->sqlData["episode"];
    }

    public function getSeasonNumber() {
        return $this->sqlData["season"];
    }

    public function getEntityId() {
        if(isset($this->sqlData["entityId"])){
            return $this->sqlData["entityId"];
        } else {
            return false;
        }
    }

    public function getIsMovie() {
        if(isset($this->sqlData["isMovie"])){
            return $this->sqlData["isMovie"];
        } else {
            return false;
        }
       
    }

    public function incrementViews() {
        $query = $this->con->prepare("UPDATE videos SET views=views+1 WHERE id=:id");
        $query->bindValue(":id", $this->getId());
        $query->execute();
    }

    public function getSeasonAndEpisode() {
        if($this->isMovie()) {
            return;
        }

        $season = $this->getSeasonNumber();
        $episode = $this->getEpisodeNumber();

        return "Season $season, Episode $episode";
    }

    public function isMovie() {
        return $this->sqlData["isMovie"] == 1;
    }

    public function isInProgress($username) {
        $query = $this->con->prepare("SELECT * FROM videoProgress
                                    WHERE videoId=:videoId AND username=:username");

        $query->bindValue(":videoId", $this->getId());
        $query->bindValue(":username", $username);
        $query->execute();

        return $query->rowCount() != 0;
    }

    public function hasSeen($username) {
        $query = $this->con->prepare("SELECT * FROM videoProgress
                                    WHERE videoId=:videoId AND username=:username
                                    AND finished=1");

        $query->bindValue(":videoId", $this->getId());
        $query->bindValue(":username", $username);
        $query->execute();

        return $query->rowCount() != 0;
    }
}
?>