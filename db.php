<?php
$host = 'localhost';
$dbname = 'dbldksmutejozt';
$username = 'uaozeqcbxyhyg';
$password = 'f4kld3wzz1v3';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Connection failed. Please try again later.");
}
?>
