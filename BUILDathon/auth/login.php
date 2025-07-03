<?php
session_start();
require_once '../includes/db.php';

$loginError = '';
$signupError = '';

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ../dashboard.php');
        exit;
    } else {
        $loginError = "Invalid username or password";
    }
}

// Handle Signup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        header('Location: login.php?signup=success');
        exit;
    } catch (PDOException $e) {
        $signupError = "Registration failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tripify Login & Sign Up</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #667eea, #764ba2);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      padding: 20px;
    }

    .container {
      background: #fff;
      width: 100%;
      max-width: 400px;
      border-radius: 16px;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .tabs {
      display: flex;
      justify-content: space-around;
      background: #f5f5f5;
      border-bottom: 1px solid #ccc;
    }

    .tab {
      flex: 1;
      text-align: center;
      padding: 14px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s;
    }

    .tab:hover,
    .tab.active {
      background: #e0e0e0;
    }

    .form-container {
      padding: 30px;
    }

    form {
      display: none;
      flex-direction: column;
      animation: fadeSlide 0.4s ease;
    }

    @keyframes fadeSlide {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    form.active {
      display: flex;
    }

    .input-group {
      position: relative;
    }

    .input-group i {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #888;
    }

    input {
      width: 100%;
      padding: 12px 12px 12px 36px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      outline: none;
      transition: border 0.3s;
    }

    input:focus {
      border-color: #764ba2;
    }

    .error {
      color: red;
      font-size: 13px;
      margin-top: -6px;
      margin-bottom: 8px;
    }

    button {
      margin-top: 15px;
      padding: 12px;
      background: #667eea;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: #5a67d8;
    }

    .link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .link a {
      color: #667eea;
      text-decoration: none;
    }

    .link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="tabs">
      <div class="tab active" onclick="switchTab('login')">Login</div>
      <div class="tab" onclick="switchTab('signup')">Sign Up</div>
    </div>
    <div class="form-container">
      <!-- Login Form -->
      <form id="login" class="active" method="POST">
        <h2 style="text-align:center;">Login to Tripify</h2>
        <?php if (!empty($loginError)): ?>
          <div class="error"><?= $loginError ?></div>
        <?php endif; ?>
        <div class="input-group">
          <i class="fa fa-user"></i>
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
          <i class="fa fa-lock"></i>
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" name="login">Login</button>
        <div class="link">Don't have an account? <a href="#" onclick="switchTab('signup')">Sign up</a></div>
      </form>

      <!-- Signup Form -->
      <form id="signup" method="POST">
        <h2 style="text-align:center;">Create Tripify Account</h2>
        <?php if (!empty($signupError)): ?>
          <div class="error"><?= $signupError ?></div>
        <?php endif; ?>
        <div class="input-group">
          <i class="fa fa-user"></i>
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
          <i class="fa fa-envelope"></i>
          <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
          <i class="fa fa-lock"></i>
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" name="signup">Sign Up</button>
        <div class="link">Already have an account? <a href="#" onclick="switchTab('login')">Login</a></div>
      </form>
    </div>
  </div>

  <script>
    function switchTab(tabId) {
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('form').forEach(f => f.classList.remove('active'));
      document.getElementById(tabId).classList.add('active');
      document.querySelectorAll('.tab').forEach(tab => {
        if (tab.textContent.toLowerCase().includes(tabId)) {
          tab.classList.add('active');
        }
      });
    }

    // If user just signed up, switch to login
    <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
      switchTab('login');
    <?php endif; ?>
  </script>
</body>
</html>
