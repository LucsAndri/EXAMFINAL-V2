<?php
function connectserv(){
    $conn = new mysqli("localhost", "ETU004394", "E0CDEmsk", "db_s2_ETU004394");
    if ($conn->connect_error) {
        die("Erreur : " . $conn->connect_error);
    }
    return $conn;
}
function connectlocal(){
    $conn = new mysqli("localhost", "root", "", "examfinalS2");
    if ($conn->connect_error) {
        die("Erreur : " . $conn->connect_error);
    }
    return $conn;
}
?>