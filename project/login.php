<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - Streamhive</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="robots" content="noindex">
</head>
<body>
<div class="container auth-page">
    <form class="auth-form" action="login.php" method="post">
        <h1>Inloggen</h1>

        <label for="username">Gebruikersnaam</label>
        <input id="username" name="username" type="text" required>

        <label for="password">Wachtwoord</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Inloggen</button>

        <p class="note">Nog geen account? <a href="register.php">Registreer</a></p>
    </form>
</div>
</body>
</html>
