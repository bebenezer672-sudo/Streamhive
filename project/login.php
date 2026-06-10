<?php
include 'includes/db.php';

session_start();

class Login {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($username, $password) {
        if ($username === '' || $password === '') {
            return 'Vul alle velden in.';
        }

        $stmt = $this->conn->prepare(
            'SELECT id, password_hash FROM users WHERE username = ?'
        );


        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $stmt->close();
                return true;
            }
        }

        $stmt->close();
        return 'Ongeldige gebruikersnaam of wachtwoord.';
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = new Login($conn);
    $result = $login->login(
        trim($_POST['username'] ?? ''),
        $_POST['password'] ?? ''
    );
    
    if ($result === true) {
        header('Location: index.php');
        exit();
    } else {
        $error = $result;
    }
}
?>
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

        <?php if ($error): ?>
          <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
     
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
