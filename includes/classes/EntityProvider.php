<?php
class EntityProvider {
    public static function getEntitiesByProducersFavorites($con, $categoryId, $producersId){

        $sql = "SELECT * FROM entities ";

        if($categoryId != null) {
            $sql .= "WHERE categoryId=:categoryId ";
        }

        $tProducersId = array();
        foreach($producersId as $producerId){
            array_push($tProducersId, $producerId["producerId"]);
        }
        $sql .= "ORDER BY producerId IN (";
        $sql .= implode(", ",$tProducersId) . ") DESC";

        $query = $con->prepare($sql);

        if($categoryId != null) {
            $query->bindValue(":categoryId", $categoryId);
        }

        $query->execute();

        $result = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row);
        }

        return $result;
    }
    
    public static function getEntitiesByActorsFavorites($con, $categoryId, $entitiesIdFromActorsId){

        $sql = "SELECT * FROM entities ";

        if($categoryId != null) {
            $sql .= "WHERE categoryId=:categoryId ";
        }

        $tEntitiesIdFromActorsId = array();
        foreach($entitiesIdFromActorsId as $entityIdFromActorId){
            array_push($tEntitiesIdFromActorsId, $entityIdFromActorId["entityId"]);
        }
        $sql .= "ORDER BY id IN (";
        $sql .= implode(", ",$tEntitiesIdFromActorsId) . ") DESC";

        $query = $con->prepare($sql);

        if($categoryId != null) {
            $query->bindValue(":categoryId", $categoryId);
        }

        $query->execute();

        $result = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row);
        }

        return $result;
    }

    public static function getEntitiesMostViews($con, $categoryId, $limit) {

        $sql = "SELECT entities.`id`,`name`,`thumbnail`,`preview`,`categoryId`,`producerId` FROM `entities` 
        INNER JOIN videos ON entities.id = videos.entityId ";

        if($categoryId != null) {
            $sql .= "WHERE categoryId=:categoryId ";
        }

        $sql .= "GROUP BY entities.id, videos.views ";

        $sql .= "ORDER BY videos.views DESC LIMIT :limit";

        $query = $con->prepare($sql);

        if($categoryId != null) {
            $query->bindValue(":categoryId", $categoryId);
        }

        $query->bindValue(":limit", $limit, PDO::PARAM_INT);
        $query->execute();

        $result = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row);
        }

        return $result;
    }
    
    public static function getEntitiesByProducersAndActorsFavorites($con, $categoryId, $producersId, $entitiesIdFromActorsId){

        $sql = "SELECT * FROM entities ";
        
        $tProducersId = array();
            foreach($producersId as $producerId){
                array_push($tProducersId, $producerId["producerId"]);
            }

        if($categoryId != null) {
            $sql .= "WHERE categoryId=:categoryId ";
            $sql .= "AND producerId IN (";
            $sql .= implode(", ",$tProducersId) . ") ";
        } else {

            $sql .= "WHERE producerId IN (";
            $sql .= implode(", ",$tProducersId) . ") ";
        }

        $tEntitiesIdFromActorsId = array();
        foreach($entitiesIdFromActorsId as $entityIdFromActorId){
            array_push($tEntitiesIdFromActorsId, $entityIdFromActorId["entityId"]);
        }
        $sql .= "AND id IN (";
        $sql .= implode(", ",$tEntitiesIdFromActorsId) . ")";

        $query = $con->prepare($sql);

        if($categoryId != null) {
            $query->bindValue(":categoryId", $categoryId);
        }
        
        $query->execute();

        $result = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row);
        }

        return $result;
    }

    public static function getEntities($con, $categoryId, $limit) {

        $sql = "SELECT * FROM entities ";

        if($categoryId != null) {
            $sql .= "WHERE categoryId=:categoryId ";
        }

        $sql .= "ORDER BY RAND() LIMIT :limit";

        $query = $con->prepare($sql);

        if($categoryId != null) {
            $query->bindValue(":categoryId", $categoryId);
        }

        $query->bindValue(":limit", $limit, PDO::PARAM_INT);
        $query->execute();

        $result = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row);
        }

        return $result;
    }

    public static function getTVShowEntities($con, $categoryId, $limit) {

        $sql = "SELECT DISTINCT(entities.id) FROM `entities` 
                INNER JOIN videos ON entities.id = videos.entityId 
                WHERE videos.isMovie = 0 ";

        if($categoryId != null) {
            $sql .= "AND categoryId=:categoryId ";
        }

        $sql .= "ORDER BY RAND() LIMIT :limit";

        $query = $con->prepare($sql);

        if($categoryId != null) {
            $query->bindValue(":categoryId", $categoryId);
        }

        $query->bindValue(":limit", $limit, PDO::PARAM_INT);
        $query->execute();

        $result = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row["id"]);
        }

        return $result;
    }

    public static function getMoviesEntities($con, $categoryId, $limit) {

        $sql = "SELECT DISTINCT(entities.id) FROM `entities` 
                INNER JOIN videos ON entities.id = videos.entityId 
                WHERE videos.isMovie = 1 ";

        if($categoryId != null) {
            $sql .= "AND categoryId=:categoryId ";
        }

        $sql .= "ORDER BY RAND() LIMIT :limit";

        $query = $con->prepare($sql);

        if($categoryId != null) {
            $query->bindValue(":categoryId", $categoryId);
        }

        $query->bindValue(":limit", $limit, PDO::PARAM_INT);
        $query->execute();

        $result = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row["id"]);
        }

        return $result;
    }

    public static function getSearchEntities($con, $term, $searchMode) {
        $sql ="";

        if($searchMode == "actor"){
            $sql = "SELECT * FROM entities, roles, actors
            WHERE actors.fullName LIKE CONCAT('%', :term, '%') 
            AND roles.entityId = entities.id LIMIT 30";

        } else if($searchMode == "producer"){
            $sql = "SELECT * FROM entities, producers 
            WHERE producers.fullName LIKE CONCAT('%', :term, '%')
            AND entities.producerId = producers.id LIMIT 30";

        } else {
            $sql = "SELECT * FROM entities WHERE name LIKE CONCAT('%', :term, '%') LIMIT 30";
        }
        
        $query = $con->prepare($sql);

        $query->bindValue(":term", $term);
        $query->execute();

        $result = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity($con, $row);
        }

        return $result;
    }

}
?>