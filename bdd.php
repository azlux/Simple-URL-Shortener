<?php
$hostname = 'localhost';
$username = '*********';
$password = '**********';

try {
    $connexion = new PDO("mysql:host=$hostname;dbname=url", $username, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}
