<?php
require_once('./database.php');
$db = new database();
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo 'Get request is not supported.';
    die();
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_SERVER['HTTP_ORIGIN'])) {
        $http = 'http';
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != null) {
            $http = 'https';
        }
        $address = $http . '://' . $_SERVER['SERVER_NAME'];
        if(strpos($address, $_SERVER['HTTP_ORIGIN']) !== 0) {
            echo 'CSRF protection in POST request: detected invalid Origin header: ' . $_SERVER['HTTP_ORIGIN'];
            die();
        }
    } else {
        echo 'HTTP_ORIGIN in header is not specified.';
        die();
    }
    if(isset($_POST['type'])){
        $type = $_POST['type'];
    } else {
        $type = null;
    }
    if(isset($_POST['name'])){
        $name = $_POST['name'];
    } else {
        $name = null;
    }
    if(isset($_POST['searchName'])){
        $searchName = $_POST['searchName'];
    } else {
        $searchName = null;
    }
    if(isset($_POST['location'])){
        $location = $_POST['location'];
    } else {
        $location = null;
    }
    header("Content-Type: application/json;charset=utf-8");
    switch ($type) {
        case 'insertPlace':
            if($name == null || $searchName == null || $location == null){
                $array = array(-1, "Somethings missing.");
                $json = json_encode($array);
                echo $json;
                break;
            }
            echo $db->insertPlace($name, $searchName, $location);
            break;
        case 'findPlace':
            if($searchName == null){
                $array = array(-1, "Somethings missing.");
                $json = json_encode($array);
                echo $json;
                break;
            }
            echo $db->findPlace($searchName);
            break;
    }

}