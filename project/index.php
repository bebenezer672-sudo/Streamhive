<?php
include __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/auth.php';

$videos = [];
$result = $conn->query("SELECT id, title, thumbnail, views, created_at FROM videos ORDER BY created_at DESC LIMIT 8");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreamHive — Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="index.php">StreamHive</a>
      <nav class="nav">
        <?php if ($authenticated): ?>
          <span>Welkom, <?php echo htmlspecialchars($_SESSION['username'] ?? 'gebruiker'); ?></span>
          <a href="admin/upload.php" class="btn">Upload</a>
          <a href="logout.php">Uitloggen</a>
        <?php else: ?>
          <a href="login.php">Inloggen</a>
          <a href="register.php">Registreren</a>
          <a href="admin/upload.php" class="btn">Upload</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="container hero-inner">
        <h1>Ontdek, Deel en Bekijk video's</h1>
        <p>Blader door trending clips of upload je eigen video.</p>
        <form class="search" action="videos/watch.php" method="get">
          <input type="search" name="q" placeholder="Zoek video's, kanalen of tags...">
          <button type="submit">Zoek</button>
        </form>
      </div>
    </section>

    <section class="features container">
      <div class="feature">
        <h3>Trending</h3>
        <p>De populairste video's nu.</p>
      </div>
      <div class="feature">
        <h3>Categorieën</h3>
        <p>Vind video's per onderwerp of interesse.</p>
      </div>
      <div class="feature">
        <h3>Upload</h3>
        <p>Deel je content met de wereld (account vereist).</p>
      </div>
    </section>

    <section class="videos container">
      <h2>Recent toegevoegd</h2>
      <div class="grid">
        <?php if (!empty($videos)): ?>
          <?php foreach ($videos as $v): ?>
            <article class="card">
              <?php $thumb = !empty($v['thumbnail']) ? $v['thumbnail'] : 'uploads/videos/default.jpg'; ?>
              <a href="videos/watch.php?id=<?php echo htmlspecialchars($v['id']); ?>"><img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($v['title']); ?>"></a>
              <h4><a href="videos/watch.php?id=<?php echo htmlspecialchars($v['id']); ?>"><?php echo htmlspecialchars($v['title']); ?></a></h4>
              <p class="meta"><?php echo (int)($v['views'] ?? 0); ?> weergaven • <?php echo htmlspecialchars($v['created_at'] ?? ''); ?></p>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Er zijn nog geen video's beschikbaar. <a href="admin/upload.php">Upload de eerste video</a>.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container">
      <p>&copy; <?php echo date('Y'); ?> StreamHive — Alle rechten voorbehouden</p>
      <nav class="footer-nav">
        <a href="#">Privacy</a>
        <a href="#">Voorwaarden</a>
        <a href="#">Contact</a>
      </nav>
    </div>
  </footer>
</body>
</html>
