<?php

$host = "localhost";
$dbname = "unity_care_v2";
$user = "root";
$password = "";

try {
    $db = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $password
    );

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo"connexion réussite"; // Removed to prevent header errors
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
