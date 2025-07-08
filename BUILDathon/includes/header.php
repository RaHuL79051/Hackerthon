<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tripify Dashboard</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
      rel="stylesheet"
    />
    
    <style>
      /* header.css */
      body {
        margin: 0;
        font-family: "Poppins", sans-serif;
      }

      /* Site Header */
      .main-header {
        background: linear-gradient(90deg, #4f46e5, #6366f1);
        color: white;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
      }

      /* Logo */
      .main-header .logo {
        font-size: 1.8rem;
        font-weight: 700;
      }

      /* Navigation */
      .navbar ul {
        list-style: none;
        display: flex;
        align-items: center;
        padding: 0;
        margin: 0;
      }

      .navbar ul li {
        margin-left: 1.2rem;
      }

      .navbar ul li:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease-in-out;
      }

      .navbar a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease-in-out;
      }

      .navbar a:hover {
        opacity: 0.85;
        scale: 1.05;
      }

       .logout-btn {
          background: #ff3b30; /* Vibrant red */
          color: white !important; /* Force white text */
          padding: 0.6rem 1.2rem;
          border-radius: 50px; /* Pill shape */
          font-weight: 600;
          text-decoration: none;
          transition: all 0.3s ease;
          box-shadow: 0 4px 10px rgba(255, 59, 48, 0.3);
          border: none;
          cursor: pointer;
          display: inline-block;
          font-family: "Poppins", sans-serif;
        }

        .logout-btn:hover {
          background: #ff1d10; 
          transform: translateY(-2px);
          box-shadow: 0 6px 15px rgba(255, 29, 16, 0.4);
        }

        .logout-btn:active {
          transform: translateY(0);
          box-shadow: 0 2px 5px rgba(255, 29, 16, 0.3);
        }

        /* Add logout icon */
        .logout-btn i {
          margin-right: 8px;
        }
        .logo:hover {
          cursor: pointer;
          color: #e0e7ff; /* Lighter shade on hover */
          scale: 1.05; /* Slightly enlarge on hover */
          transition: all 0.3s ease-in-out;
        }
    </style>
  </head>
  <body>
    <header class="main-header">
      <div class="logo">✈️ Tripify</div>
      <nav class="navbar">
        <ul>
          <li><a href="/TRIPIFY/dashboard.php">Dashboard</a></li>
          <li><a href="/TRIPIFY/modules/distance_module/distance.php">Distance</a></li>
          <li><a href="/TRIPIFY/modules/bugdet_Module/budget_home.php">Budget</a></li>
          <li><a href="/TRIPIFY/modules/todo_module/todo.php">To-Do</a></li>
          <li><a href="/TRIPIFY/dashboard.php?show=chatbot">Chatbot</a></li>
          <li><a href="/TRIPIFY/auth/logout.php" class="logout-btn">Logout</a></li>
        </ul>
      </nav>
    </header>
    <main class="page-content">
  </body>
  <script>
    let logoNav = document.querySelector('.logo');
    logoNav.addEventListener('click', function() {
      window.location.href = '/TRIPIFY/dashboard.php';
    });
  </script>
</html>
