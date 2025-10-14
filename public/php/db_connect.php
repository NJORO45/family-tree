<?php

require __DIR__ .'/../../vendor/autoload.php';
//point it to where .env exists
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
$host     = $_ENV['DB_HOST'] ;
$user     = $_ENV['DB_USER'] ;
$password = $_ENV['DB_PASSWORD'] ;
$database = $_ENV['DB_DATABASE'] ;
$con = new mysqli($host,$user,$password,$database);
if($con->connect_error){
    die("Connection Failed:" .$con->connect_error);
}
?>