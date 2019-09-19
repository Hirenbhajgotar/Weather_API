<?php

$hostname = 'localhost';
$dbname   = 'weather_api';
$username = 'root';
$password = '';

try {
    $con = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo 'connected successfully';

} catch (PDOException $e) {
    echo 'Connected faild'. $e->getMessage();
}

?>