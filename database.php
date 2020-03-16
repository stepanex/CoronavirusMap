<?php
require_once('databaseLogin.php');
class database
{

    protected $db;

    function __construct()
    {
        $databaseLogin = getDatabaseLogin();
        $this->db = new mysqli($databaseLogin[0], $databaseLogin[1], $databaseLogin[2], $databaseLogin[3]);

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
//        echo "Connected successfully";
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

    function getCacheLastRevision($name){
        $query = 'select revid from cache where name = "'.$name.'";';
        $result = $this->db->query($query);
        if($result){
            $array = array(1, $result->fetch_assoc());
        } else {
            $array = array(0, $this->db->error);
        }
        return json_encode($array);
    }

    function setCache($name, $revid, $data){
        $query = 'update cache set revid="'.$revid.'", data="'.$this->db->real_escape_string($data).'" where name="'.$name.'";';
        $result = $this->db->query($query);
        if($result){
            $array = array(1, $result);
        } else {
            $array = array(0, $this->db->error);
        }
        return json_encode($array);
    }

    function getCache($name){
        $query = 'select data from cache where name = "'.$name.'";';
        $result = $this->db->query($query);
        if($result){
            $array = array(1, $result->fetch_assoc());
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
