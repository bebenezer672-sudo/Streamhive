<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user =($_POST['username'] ?? '');
    $email = ($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($user === '' || $email === '' || $password === '') {
        echo "oei pak het op het is leeg ";
    }else {
        
        $stmt = $conn->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bind_param('sss', $user, $email, $passwordHash);
    
        if($stmt->execute()){
            echo "gelukt";
        }else 
        {
            echo "oei fout ";
        }


        
    }}



?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - Streamhive</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="robots" content="noindex">
</head>
<body>
<div class="container auth-page">
    <form class="auth-form" action="register.php" method="post">
        <h1>Registreren</h1>

       

        <label for="username">Gebruikersnaam</label>
        <input id="username" name="username" type="text" required>

        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" required>

        <label for="password">Wachtwoord</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Registreren</button>

        <p class="note">Al een account? <a href="login.php">Inloggen</a></p>
    </form>
</div>
</body>
</html>