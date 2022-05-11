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
        $this->entity = new Entity($con, $this->sqlData["entityId"]);
    }

    public function updateVideo($title, $description, $filePath, $episode, $season){
        $query = $this->con->prepare("UPDATE videos SET title=:title, description=:description, filePath=:filePath,
        episode=:episode , season=:season WHERE videos.id=:id"); 
        $query->bindValue(":title", $title);
        $query->bindValue(":description", $description);
        $query->bindValue(":filePath",  $filePath);
        $query->bindValue(":episode", $episode);
        $query->bindValue(":season", $season);
        $query->bindValue(":id", $this->getId());
        $query->execute();

        return $query;
    }

    public function deleteVideo(){
        $query = $this->con->prepare("DELETE * FROM videos WHERE id=:id"); 
        $query->bindValue(":id", $this->getId());
        $query->execute();

        return $query;
    }

    public function createVideo($name, $thumbnail, $preview){
        $query = $this->con->prepare("CREATE videos SET name=:name, thumbnail=:thumbnail, preview=:preview,
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
        return $this->sqlData["entityId"];
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