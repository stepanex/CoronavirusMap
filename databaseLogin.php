<?php
function getDatabaseLogin(){
    $db_host = getenv('DB_HOST');
    if($db_host == false){
        $db_host = "localhost:3306";
    }

    $db_user = getenv('DB_USER');
    if($db_user == false){
        $db_user = "user";
    }

    $db_password = getenv('DB_PASSWORD');
    if($db_password == false){
        $db_password = "heslo";
    }

    $db_name = getenv('DB_NAME');
    if($db_name == false){
        $db_name = "d23117_strba";
    }
 return $databaseLogin = [$db_host, $db_user, $db_password, $db_name];
}