<?php
if (session_status() == PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logged Out</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      min-height: 100vh;
      margin: 0;
      background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
    }
    .logout-card {
      background: #fff;
      padding: 3rem 2.5rem 2.5rem 2.5rem;
      border-radius: 20px;
      box-shadow: 0 8px 32px rgba(79,70,229,0.15);
      max-width: 400px;
      width: 100%;
      text-align: center;
      animation: fadeIn 0.7s cubic-bezier(.4,0,.2,1);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px);}
      to { opacity: 1; transform: translateY(0);}
    }
    .logout-card .icon {
      font-size: 3.5rem;
      color: #4f46e5;
      margin-bottom: 1.2rem;
      animation: bounce 1.2s infinite alternate;
    }
    @keyframes bounce {
      to { transform: translateY(-8px);}
    }
    .logout-card h1 {
      font-size: 2rem;
      font-weight: 700;
      color: #22223b;
      margin-bottom: 0.7rem;
    }
    .logout-card p {
      color: #555;
      font-size: 1.08rem;
      margin-bottom: 2.2rem;
      line-height: 1.6;
    }
    .logout-card .btn {
      display: inline-block;
      padding: 0.9rem 2.1rem;
      font-size: 1.05rem;
      font-weight: 600;
      border-radius: 50px;
      border: none;
      background: linear-gradient(90deg, #4f46e5, #6366f1);
      color: #fff;
      text-decoration: none;
      box-shadow: 0 4px 14px rgba(79,70,229,0.12);
      transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
      margin: 0 0.5rem;
    }
    .logout-card .btn:hover {
      background: linear-gradient(90deg, #6366f1, #4f46e5);
      transform: translateY(-2px) scale(1.04);
      box-shadow: 0 8px 24px rgba(79,70,229,0.18);
    }
    .logout-card .home-link {
      background: #fff;
      color: #4f46e5;
      border: 2px solid #4f46e5;
      margin-left: 0.5rem;
    }
    .logout-card .home-link:hover {
      background: #f3f4f6;
      color: #22223b;
      border-color: #6366f1;
    }
    @media (max-width: 500px) {
      .logout-card {
        padding: 2rem 1rem;
      }
      .logout-card h1 {
        font-size: 1.3rem;
      }
    }
  </style>
</head>
<body>
  <div class="logout-card">
    <div class="icon">
      <i class="fas fa-sign-out-alt"></i>
    </div>
    <h1>You’re Logged Out</h1>
    <p>
      Thank you for using <strong>Tripify</strong>.<br>
      We hope your journeys are amazing!<br>
      Want to continue planning your next adventure?
    </p>
    <a href="./login.php" class="btn">
      <i class="fas fa-sign-in-alt"></i> Login Again
    </a>
    <a href="../index.html" class="btn home-link">
      <i class="fas fa-home"></i> Home
    </a>
  </div>
</body>
</html>
