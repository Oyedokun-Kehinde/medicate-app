<?php

// Connect to MyQSL DB using PHP PDO Method
$host = 'localhost';
$dbname = 'medicate_app';
$username = 'root';
$password = '';

try {
    //Try connecting to the datbase
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    //If connection fails, return an error in browser
    die("Database connection failed: " . $e->getMessage());
}
?>