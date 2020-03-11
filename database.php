<?php
class database
{

    protected $db;

    function __construct()
    {
        $this->db = new mysqli("35.246.28.71:3306", "root", "krutoheslo", "koronamap");

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
}
