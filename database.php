<?php
require_once('databaseLogin.php');
class database
{

    protected $db;
    protected $statusArr;

    function __construct()
    {
        $databaseLogin = getDatabaseLogin();
        $this->db = new mysqli($databaseLogin[0], $databaseLogin[1], $databaseLogin[2], $databaseLogin[3]);

        if ($this->db->connect_error) {
            $this->statusArr = array(0, "Connection failed: " . $this->db->connect_error);
        } else {
            $this->statusArr = array(1, "Connected successfully.");
        }
    }

    function getStatusArr(){
        return json_encode($this->statusArr, true);
    }

    function insertPlace($name, $searchName, $location){
        $query = 'insert into places (name, searchName, location) values ("'.$name.'","'.$searchName.'","'.$location.'");';
        $result = $this->db->query($query);
        if($result){
            $array = array($this->db->insert_id, null);
        } else {
            $array = array(0, $this->db->error);
        }
        return json_encode($array, true);
    }

    function findPlace($searchName){
        $query = 'select name, location from places where searchName = "'.$searchName.'";';
        $result = $this->db->query($query);
        if($result){
            $array = array(1, $result->fetch_assoc());
        } else {
            $array = array(0, $this->db->error);
        }
        return json_encode($array);
    }

    function getCacheLastRevision($state, $name){
        $query = 'select revid from cacheTest where state="'.$state.'" and name = "'.$name.'";';
        $result = $this->db->query($query);
        if($result){
            $array = array(1, $result->fetch_assoc());
        } else {
            $array = array(0, $this->db->error);
        }
        return json_encode($array);
    }

    function setCache($state, $revid, $data, $name){
        $query = 'update cacheTest set revid="'.$revid.'", data="'.$this->db->real_escape_string($data).'" where state="'.$state.'" and name="'.$name.'";';
        $result = $this->db->query($query);
        if($result){
            $array = array(1, $result);
        } else {
            $array = array(0, $this->db->error);
        }
        return json_encode($array);
    }

    function getCache($state, $name){
        $query = 'select data from cacheTest where state="'.$state.'" and name = "'.$name.'";';
        $result = $this->db->query($query);
        if($result){
            $array = array(1, $result->fetch_assoc());
        } else {
            $array = array(0, $this->db->error);
        }
        return json_encode($array);
    }

    function setStateInfected($state, $infectedCount){
        $query = 'update states set infectedCount="'.$infectedCount.'" where state="'.$state.'";';
        $result = $this->db->query($query);
        if($result){
            $array = array(1, $result);
        } else {
            $array = array(0, $this->db->error);
        }
        return json_encode($array);
    }

    function getStateInfected($state){
        $query = 'select infectedCount from states where state = "'.$state.'";';
        $result = $this->db->query($query);
        if($result){
            $array = array(1, $result->fetch_assoc());
        } else {
            $array = array(0, $this->db->error);
        }
        return json_encode($array);
    }

    function getStates(){
        $query = 'select state, infectedCount, revid, url from states;';
        $result = $this->db->query($query);
        if($result){
            $resArr = [];
            while($resultFetch = $result->fetch_assoc()){
                array_push($resArr, $resultFetch);
            }
            $array = array(1, $resArr);
        } else {
            $array = array(0, $this->db->error);
        }
        return json_encode($array);
    }

    function __destruct()
    {
        $this->db->close();
    }
}
