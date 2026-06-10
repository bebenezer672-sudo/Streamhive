<?php
session_start();
include __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    
    if (empty($title)) {
        $error = 'Titel is verplicht';
    } elseif (!isset($_FILES['video']) || $_FILES['video']['error'] !== 0) {
        $error = 'Video upload mislukt';
    } else {
        $filename = time() . "_" . $_FILES['video']['name'];
        $videoPath = "uploads/" . $filename;
        
        if (move_uploaded_file($_FILES['video']['tmp_name'], __DIR__ . "/../" . $videoPath)) {
            $thumbnail = "";
            $stmt = $conn->prepare("INSERT INTO videos (user_id, title, video_path, thumbnail) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $_SESSION['user_id'], $title, $videoPath, $thumbnail);
            
            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = 'Database error';
            }
            $stmt->close();
        } else {
            $error = 'Kon video niet opslaan';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Uploaden</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <a href="index.php" class="brand">StreamHive</a>
                <div class="nav">
                    <a href="index.php">Home</a>
                    <a href="upload.php" class="btn primary">Uploaden</a>
                    <a href="../logout.php">Uitloggen</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="upload-panel">
            <h1>Video uploaden</h1>
            <p class="lead">Deel jouw video met de wereld</p>

            <?php if ($success): ?>
                <div class="message success">
                    ✅ Video succesvol geüpload! <a href="index.php" style="color: var(--accent);">Terug naar home</a>
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="message error">
                        ❌ <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" class="upload-form">
                    <label>Titel *</label>
                    <input type="text" name="title" placeholder="Bijv: Mijn geweldige video" required>

                    <label>Video bestand *</label>
                    <input type="file" name="video" accept="video/mp4,video/mpeg,video/quicktime" required>
                    <small style="color: var(--muted);">Ondersteunde formaten: MP4, MOV, AVI (max 500MB)</small>

                    <div class="actions">
                        <button type="submit" class="btn primary">📤 Uploaden</button>
                        <a href="index.php" class="btn secondary">Annuleren</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2026 StreamHive. Deel en ontdek.</p>
            <div class="footer-nav">
                <a href="#">Over ons</a>
                <a href="#">Privacy</a>
                <a href="#">Contact</a>
            </div>
        </div>
    </footer>
</body>
</html>