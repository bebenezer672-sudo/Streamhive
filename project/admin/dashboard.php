<?php
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/auth.php';

if (!$authenticated) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user stats
$userStats = $conn->query(
    "SELECT COUNT(*) as total_videos, SUM(views) as total_views FROM videos WHERE user_id = $user_id"
)->fetch_assoc();

// Get recent videos
$recentVideos = [];
$result = $conn->query(
    "SELECT id, title, views, created_at, thumbnail FROM videos WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 6"
);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentVideos[] = $row;
    }
}

// Get total likes
$totalLikes = $conn->query(
    "SELECT COUNT(*) as total FROM likes WHERE user_id = $user_id"
)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — StreamHive</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="robots" content="noindex">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="../index.php">StreamHive</a>
      <nav class="nav">
        <span>Welkom, <?php echo htmlspecialchars($_SESSION['username'] ?? 'gebruiker'); ?></span>
        <a href="upload.php" class="nav-link">Upload Video</a>
        <a href="../logout.php" class="nav-link">Uitloggen</a>
      </nav>
    </div>
  </header>

  <main>
    <section class="container">
      <h1 style="margin-top: 2rem; margin-bottom: 2rem;">Dashboard</h1>

      <div class="stats-grid">
        <div class="stat-card">
          <h3>Videos</h3>
          <p class="stat-number"><?php echo (int)($userStats['total_videos'] ?? 0); ?></p>
          <span class="stat-label">Geüploade video's</span>
        </div>
        <div class="stat-card">
          <h3>Views</h3>
          <p class="stat-number"><?php echo number_format((int)($userStats['total_views'] ?? 0)); ?></p>
          <span class="stat-label">Totaal weergaven</span>
        </div>
        <div class="stat-card">
          <h3>Likes</h3>
          <p class="stat-number"><?php echo (int)($totalLikes['total'] ?? 0); ?></p>
          <span class="stat-label">Ontvangen likes</span>
        </div>
      </div>

      <div style="margin-top: 3rem;">
        <h2>Jouw Video's</h2>
        
        <?php if (!empty($recentVideos)): ?>
          <div class="grid">
            <?php foreach ($recentVideos as $video): ?>
              <article class="card">
                <?php $thumb = !empty($video['thumbnail']) ? $video['thumbnail'] : '../uploads/videos/default.jpg'; ?>
                <a href="../videos/watch.php?id=<?php echo htmlspecialchars($video['id']); ?>">
                  <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>">
                </a>
                <h4>
                  <a href="../videos/watch.php?id=<?php echo htmlspecialchars($video['id']); ?>">
                    <?php echo htmlspecialchars($video['title']); ?>
                  </a>
                </h4>
                <p class="meta">
                  <?php echo (int)($video['views'] ?? 0); ?> weergaven • 
                  <?php echo date('d M Y', strtotime($video['created_at'] ?? '')); ?>
                </p>
                <div class="card-actions">
                  <a href="upload.php?edit=<?php echo htmlspecialchars($video['id']); ?>" class="btn-small">Bewerken</a>
                  <a href="#" class="btn-small btn-danger" onclick="deleteVideo(<?php echo htmlspecialchars($video['id']); ?>); return false;">Verwijderen</a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p style="color: var(--muted); margin-top: 1rem;">Je hebt nog geen video's geüpload. 
            <a href="upload.php" style="color: var(--accent);">Upload je eerste video nu</a>
          </p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container">
      <p>&copy; <?php echo date('Y'); ?> StreamHive — Alle rechten voorbehouden</p>
    </div>
  </footer>

  <style>
    .nav-link {
      color: var(--muted);
      margin-left: 0.85rem;
      transition: color 0.2s ease;
    }

    .nav-link:hover {
      color: var(--text);
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 24px;
      padding: 1.75rem;
      text-align: center;
    }

    .stat-card h3 {
      margin: 0 0 0.5rem;
      font-size: 1rem;
      color: var(--muted);
      font-weight: 600;
    }

    .stat-number {
      margin: 0.5rem 0;
      font-size: 2rem;
      font-weight: 700;
      color: var(--accent);
    }

    .stat-label {
      display: block;
      font-size: 0.9rem;
      color: var(--muted);
    }

    .card-actions {
      display: flex;
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .btn-small {
      display: inline-block;
      padding: 0.6rem 0.9rem;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.12);
      color: var(--text);
      font-size: 0.85rem;
      transition: background 0.2s ease, border-color 0.2s ease;
      cursor: pointer;
      text-decoration: none;
    }

    .btn-small:hover {
      background: rgba(255, 255, 255, 0.12);
      border-color: rgba(255, 255, 255, 0.18);
    }

    .btn-danger:hover {
      background: rgba(229, 62, 62, 0.12);
      border-color: rgba(229, 62, 62, 0.28);
      color: #ffb3b3;
    }

    @media (max-width: 640px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }

      .stat-number {
        font-size: 1.6rem;
      }
    }
  </style>

  <script>
    function deleteVideo(videoId) {
      if (confirm('Weet je zeker dat je deze video wilt verwijderen?')) {
        // Implement delete functionality here
        console.log('Delete video:', videoId);
      }
    }
  </script>
</body>
</html>
