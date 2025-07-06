<?php
session_start();
require_once '../../includes/db.php';

$videos = $pdo->query("SELECT travel_shorts.*, users.username FROM travel_shorts JOIN users ON travel_shorts.user_id = users.id ORDER BY created_at DESC")
         ->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Travel Shorts - Tripify</title>
  <link rel="stylesheet" href="view.css">
  <style>
    body {
      margin: 0;
      background: #000;
      font-family: 'Poppins', sans-serif;
    }

    .shorts-container {
      scroll-snap-type: y mandatory;
      overflow-y: scroll;
      height: 100vh;
    }

    .shorts-card {
      scroll-snap-align: start;
      width: 100vw;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      background: #000;
    }

    .video-wrapper {
      position: relative;
      width: 50%;
      height: 90%;
      max-width: 420px;
      border-radius: 20px;
      overflow: hidden;
      background: #111;
      box-shadow: 0 0 20px rgba(0,0,0,0.5);
    }

    video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .reel-info {
      position: absolute;
      bottom: 16px;
      left: 16px;
      color: white;
      z-index: 10;
      font-size: 0.9rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.7);
    }

    .username {
      font-weight: 600;
      margin-bottom: 4px;
    }

    .follow-btn {
      background: none;
      border: 1px solid #fff;
      color: #fff;
      padding: 2px 8px;
      font-size: 0.75rem;
      border-radius: 4px;
      margin-left: 8px;
      cursor: pointer;
    }

    .caption {
      margin-top: 4px;
    }

    .city-date {
      margin-top: 4px;
      font-size: 0.75rem;
      color: #ccc;
    }

    .reel-actions {
      position: absolute;
      right: 16px;
      bottom: 80px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 18px;
      z-index: 10;
    }

    .reel-actions .icon {
      display: flex;
      flex-direction: column;
      align-items: center;
      font-size: 0.75rem;
      color: #fff;
      text-shadow: 0 2px 4px rgba(0,0,0,0.6);
    }

    .reel-actions .icon img {
      width: 28px;
      height: 28px;
      margin-bottom: 4px;
      filter: invert(1);
      cursor: pointer;
    }

    .upload-button {
      position: fixed;
      bottom: 24px;
      right: 24px;
      background: linear-gradient(135deg, #ff416c, #ff4b2b);
      color: #fff;
      width: 56px;
      height: 56px;
      border-radius: 50%;
      font-size: 32px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 24px rgba(255, 65, 108, 0.35);
      text-decoration: none;
      z-index: 1000;
      transition: transform 0.2s ease;
    }

    .upload-button:hover {
      transform: scale(1.1);
    }

    @media screen and (max-width: 768px) {
      .video-wrapper {
        width: 100%;
        height: 100%;
        border-radius: 0;
      }
    }
  </style>
</head>
<body>

<?php include '../../includes/header.php'; ?>

<div class="shorts-container">
  <?php foreach ($videos as $vid): ?>
    <div class="shorts-card">
      <div class="video-wrapper">
        <video autoplay muted loop playsinline controlls>
          <source src="<?= htmlspecialchars($vid['video_path']) ?>" type="video/mp4">
          Your browser does not support the video tag.
        </video>

        <div class="reel-info">
          <div class="username">@<?= htmlspecialchars($vid['username']) ?> <button class="follow-btn">Follow</button></div>
          <div class="caption"><?= htmlspecialchars($vid['caption']) ?></div>
          <div class="city-date"><?= htmlspecialchars($vid['city']) ?> • <?= date('M d, Y', strtotime($vid['created_at'])) ?></div>
        </div>

        <div class="reel-actions">
          <div class="icon like-icon">
  <img src="https://cdn-icons-png.flaticon.com/512/1077/1077035.png" alt="Like">
  <div>2.2K</div>
</div>

          <div class="icon">
            <img src="https://cdn-icons-png.flaticon.com/512/1380/1380338.png" alt="Comment">
            <div>134</div>
          </div>
          <div class="icon">
            <img src="https://cdn-icons-png.flaticon.com/512/786/786205.png" alt="Share">
            <div>Share</div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<a class="upload-button" href="upload.php">+</a>

<?php include '../../includes/footer.php'; ?>

<!-- ✅ SMART VIDEO CONTROL SCRIPT -->
<script>
  const videos = document.querySelectorAll('video');

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        const video = entry.target;
        if (entry.isIntersecting) {
          video.currentTime = 0;           // Always start fresh
          video.muted = false;
          video.play().catch(() => {});
        } else {
          video.pause();
          video.muted = true;
          video.currentTime = 0;           // 🔁 Reset when out of view
        }
      });
    },
    {
      threshold: 0.8, // Video must be 80% in view
    }
  );

  videos.forEach((video) => {
    observer.observe(video);
  });

    videos.forEach((video) => {
    video.addEventListener('click', () => {
      if (video.paused) {
        video.play();
      } else {
        video.pause();
      }
    });
  });
  
  document.querySelectorAll('.like-icon').forEach(icon => {
    icon.addEventListener('click', () => {
      icon.classList.toggle('liked');
    });
  });

</script>


</body>
</html>
