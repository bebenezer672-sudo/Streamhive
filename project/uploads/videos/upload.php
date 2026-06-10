<?php
include __DIR__ . '/../../includes/auth.php';


class upload
 {
    private $conn;
     public function __construct($dbConnection) {
        $this->conn = $dbConnection;  
 }

          function uploadvideo($title, $description, $video, $thumbnail, $visibility) {
             if ($title === '' || $video === ''){
                echo "vull alle velden in bro";
             }
          }
 }

?>
<!DOCTYPE html>
<html lang="nl">
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>Upload video — StreamHive</title>
		<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
	<header class="site-header">
		<div class="container header-inner">
			<a class="brand" href="../../index.php">StreamHive</a>
			<nav class="nav">
				<a href="../../login.php">Inloggen</a>
				<a href="../../register.php">Registreren</a>
				<a href="../../admin/dashboard.php" class="btn">Dashboard</a>
			</nav>
		</div>
	</header>

	<main class="container">
		<section class="upload-panel">
			<h1>Upload een video</h1>
			<p class="lead">Vul de velden in en voeg optioneel een thumbnail toe.</p>

			<form class="upload-form" action="../../admin/upload.php" method="post" enctype="multipart/form-data">
				<label for="title">Titel</label>
				<input id="title" name="title" type="text" required maxlength="255">

				<label for="description">Beschrijving</label>
				<textarea id="description" name="description" rows="5"></textarea>

				<label for="video_file">Videobestand</label>
				<input id="video_file" name="video_file" type="file" accept="video/*" required>

				<label for="thumbnail">Thumbnail (optioneel)</label>
				<input id="thumbnail" name="thumbnail" type="file" accept="image/*">

				<div class="thumb-preview" id="thumbPreview">
					<img src="../../uploads/videos/default.jpg" alt="Thumbnail preview">
				</div>

				<label for="visibility">Zichtbaarheid</label>
				<select id="visibility" name="visibility">
					<option value="public" selected>Publiek</option>
					<option value="private">Privé</option>
				</select>

				<div class="actions">
					<button type="submit" class="btn primary">Uploaden</button>
					<a href="../../index.php" class="btn">Annuleren</a>
				</div>
			</form>
		</section>
	</main>

	<footer class="site-footer">
		<div class="container">
			<p>&copy; <?php echo date('Y'); ?> StreamHive</p>
		</div>
	</footer>

	<script>
		(function(){
			const input = document.getElementById('thumbnail');
			const preview = document.getElementById('thumbPreview').querySelector('img');
			input.addEventListener('change', function(e){
				const file = e.target.files && e.target.files[0];
				if (!file) { preview.src = '../../uploads/videos/default.jpg'; return; }
				const reader = new FileReader();
				reader.onload = function(ev){ preview.src = ev.target.result; };
				reader.readAsDataURL(file);
			});
		})();
	</script>
</body>
</html>