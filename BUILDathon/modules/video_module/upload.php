<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  die("Unauthorized. Please <a href='../../auth/login.php'>login</a> first.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $caption = $_POST['caption'];
  $city = $_POST['city'];

  if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
    $error = "Video upload failed.";
  } else {
    $video = $_FILES['video'];
    $allowed = ['mp4', 'webm', 'ogg'];
    $ext = strtolower(pathinfo($video['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
      $error = "Only MP4, WEBM, or OGG videos allowed.";
    } elseif ($video['size'] > 10 * 1024 * 1024) {
      $error = "Max file size is 10MB.";
    } else {
      $upload_dir = 'uploads/';
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      $filename = uniqid() . '.' . $ext;
      $target = $upload_dir . $filename;

      if (move_uploaded_file($video['tmp_name'], $target)) {
        $stmt = $pdo->prepare("INSERT INTO travel_shorts (user_id, video_path, caption, city) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $target, $caption, $city]);
        $success = "Video uploaded successfully!";
      } else {
        $error = "Failed to save video.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Upload Travel Short</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include '../../includes/header.php'; ?>
<div class="container">
  <h2>Upload Your Travel Short 🎥</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="video">Select Video (Max 10MB, MP4/WEBM/OGG)</label>
      <input type="file" name="video" accept="video/*" required>
    </div>
    <div class="form-group">
      <label for="caption">Caption</label>
      <input type="text" name="caption" maxlength="255" required>
    </div>
    <div class="form-group">
      <label for="city">City</label>
      <select name="city" required>
        <option value="">Select City</option>
        <option>Delhi</option>
        <option>Mumbai</option>
        <option>Chandigarh</option>
      </select>
    </div>
    <button type="submit">Upload</button>
  </form>
</div>
<?php include '../../includes/footer.php'; ?>
</body>
</html>