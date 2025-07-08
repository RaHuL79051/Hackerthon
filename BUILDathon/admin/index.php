<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tripify Admin Panel</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f2f5;
    }
    .sidebar {
      height: 100vh;
      background-color: #212529;
      color: white;
    }
    .sidebar a {
      color: white;
      display: block;
      padding: 15px;
      text-decoration: none;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background-color: #343a40;
    }
    .content {
      padding: 30px;
    }
    .card {
      margin-bottom: 20px;
    }
    .navbar-brand {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <nav class="col-md-2 sidebar d-flex flex-column p-3">
        <h4 class="text-white mb-4">Tripify Admin</h4>
        <a href="?page=users">Users</a>
        <a href="?page=expenses">Expenses</a>
        <a href="?page=tasks">Tasks</a>
        <a href="?page=locations">Locations</a>
        <a href="?page=places">Places</a>
        <a href="?page=distances">Distances</a>
        <a href="?page=chatbot">Chatbot Nodes</a>
      </nav>

      <main class="col-md-10 content">
        <h2 class="mb-4">Admin Dashboard</h2>
        <?php
          $page = $_GET['page'] ?? 'dashboard';
          $pagePath = __DIR__ . "/{$page}.php";
          if (file_exists($pagePath)) {
            include $pagePath;
          } else {
            echo "<p>Welcome to the Tripify admin panel. Select a module from the sidebar to begin managing your application.</p>";
          }
        ?>
      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
