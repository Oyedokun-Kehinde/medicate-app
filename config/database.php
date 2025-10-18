<?php
// config/database.php
$host = 'localhost';
$dbname = 'medicate_app';
$username = 'root';      // default for XAMPP/WAMP
$password = '';          // often empty in local dev

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>