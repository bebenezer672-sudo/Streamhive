<?php
session_start();
include 'includes/db.php';

class User {

    private $conn;
     // zet de conn in de de clas zodat ik er overal bij kan komen
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
   
    public function register($username, $email, $password) {
        // als de velden leeg zijn geef dan deze melding 
        if ($username === '' || $email === '' || $password === '') {
            return 'Vul alle velden in.';
        }

        // Check if username already exists
        $checkStmt = $this->conn->prepare('SELECT id FROM users WHERE username = ?');
        $checkStmt->bind_param('s', $username);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            $checkStmt->close();
            return 'Gebruikersnaam bestaat al.';
        }
        $checkStmt->close();

        // Check if email already exists
        $checkStmt = $this->conn->prepare('SELECT id FROM users WHERE email = ?');
        $checkStmt->bind_param('s', $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            $checkStmt->close();
            return 'E-mail adres is al geregistreerd.';
        }
        $checkStmt->close();
            
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        // hier voegt hij de gebruiker
        $stmt = $this->conn->prepare(
            'INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)'
        );
        // hier zet hij de waarden in de variabelen
        $stmt->bind_param('sss', $username, $email, $passwordHash);

        if ($stmt->execute()) {
            $stmt->close();
            return 'success';
        } else {
            $stmt->close();
            return 'Registratie mislukt. Probeer opnieuw.';
        }
    }
}

$message = '';
$messageType = '';
// als er een iets
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
     // als er een post is voert hij de class uit 
    $userHandler = new User($conn);
    $result = $userHandler->register(trim($username), trim($email), $password);
    
    if ($result === 'success') {
        $messageType = 'success';
        $message = 'Account aangemaakt! Je wordt doorgestuurd naar login...';
        header('refresh:2;url=login.php');
    } else {
        $messageType = 'error';
        $message = $result;
    }
}
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

        <?php if ($message): ?>
          <div class="message <?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
          </div>
        <?php endif; ?>

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

        <p class="note">Al een account? <a href="login.php">Inloggen</a></p>
    </form>
</div>
</body>
</html>