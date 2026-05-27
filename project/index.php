<?php
include 'includes/db.php';

$videoCount = 0;
$result = $conn->query('SELECT COUNT(*) AS total FROM videos');
if ($result) {
    $videoCount = (int) ($result->fetch_assoc()['total'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Streamhive</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container main-page">
        <div class="hero">
            <h1>Welkom bij Streamhive</h1>
            <p>Upload je video, bekijk content en beheer je uploads in één eenvoudige interface.</p>
            <div class="hero-actions">
                <a class="button" href="register.php">Registreren</a>
                <a class="button button-secondary" href="login.php">Inloggen</a>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <strong><?php echo $videoCount; ?></strong>
                <span>Video's geüpload</span>
            </div>
        </div>

        <div class="grid">
            <a class="card" href="register.php">
                <h2>Registreer</h2>
                <p>Maak een account aan en begin met uploaden.</p>
            </a>
            <a class="card" href="login.php">
                <h2>Login</h2>
                <p>Log in en beheer je content.</p>
            </a>
            <a class="card" href="admin/upload.php">
                <h2>Upload video</h2>
                <p>Upload nieuwe video's naar je platform.</p>
            </a>
        </div>
    </div>
</body>
</html>