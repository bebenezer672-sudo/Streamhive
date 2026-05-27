<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'video_platform';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die('Databaseverbinding mislukt: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
?>