<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';
require_once 'modules/chatbot_module/chatbot.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}



if (isset($_GET['show'])) {
    require_once 'modules/chatbot_module/chatbot.php';
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND status =
'pending' ORDER BY due_date ASC LIMIT 3");
$stmt->execute([$_SESSION['user_id']]); $upcomingTasks = $stmt->fetchAll();
$recentDestinations = $pdo->query("SELECT to_name, COUNT(*) as visits FROM
distances GROUP BY to_name ORDER BY visits DESC LIMIT 3")->fetchAll(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tripify Dashboard</title>
    <link rel="stylesheet" href="dashboard.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
  </head>
  <body>
    <?php require_once 'includes/header.php'; ?>

    <div class="dashboard-container">
      <section class="dashboard-hero">
        <div class="hero-content">
          <h1>
            Welcome back,
            <?= htmlspecialchars($_SESSION['username']) ?>!
            <span class="emoji">🌍</span>
          </h1>
          <p class="subheading">
            Ready for your next adventure? Let's make it unforgettable.
          </p>
          <div class="hero-stats">
            <div class="stat-card">
              <i class="fas fa-route"></i>
              <div>
                <span class="stat-number">12</span>
                <span class="stat-label">Trips Planned</span>
              </div>
            </div>
            <div class="stat-card">
              <i class="fas fa-map-marker-alt"></i>
              <div>
                <span class="stat-number">7</span>
                <span class="stat-label">Destinations</span>
              </div>
            </div>
            <div class="stat-card">
              <i class="fas fa-tasks"></i>
              <div>
                <span class="stat-number"><?= count($upcomingTasks) ?></span>
                <span class="stat-label">Upcoming Tasks</span>
              </div>
            </div>
          </div>
        </div>
        <div class="hero-image">
          <img
            src="https://images.unsplash.com/photo-1503220317375-aaad61436b1b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
            alt="Travel Adventure"
          />
        </div>
      </section>

      <!-- Quick Actions -->
      <section class="quick-actions">
        <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
        <div class="action-grid">
          <a href="modules/distance_module/distance.php" class="action-card">
            <i class="fas fa-calculator"></i>
            <span>Calculate Distance</span>
          </a>
          <a href="modules/video_module/view.php" class="action-card">
            <i class="fas fa-video"></i>
            <span>Travel Shorts</span>
          </a>
          <a href="modules/todo_module/todo.php" class="action-card">
            <i class="fas fa-plus-circle"></i>
            <span>Add Task</span>
          </a>
          <a href="modules/bugdet_Module/budget_home.php" class="action-card">
            <i class="fas fa-wallet"></i>
            <span>Add Expense</span>
          </a>
        </div>
      </section>

      <!-- Main Content -->
      <div class="dashboard-content">
        <!-- Modules Section -->
        <section class="module-section">
          <h2><i class="fas fa-cube"></i> Travel Modules</h2>
          <div class="modules-grid">
            <a href="modules/distance_module/distance.php" class="module-card">
              <div
                class="card-icon"
                style="background: linear-gradient(135deg, #4361ee, #3a0ca3)"
              >
                <i class="fas fa-route"></i>
              </div>
              <h3>GeoRoute Mapper</h3>
              <p>Plan optimized paths between places</p>
              <div class="card-footer">
                <span>Explore <i class="fas fa-arrow-right"></i></span>
              </div>
            </a>

             <a href="modules/video_module/view.php" class="module-card">
              <div
                class="card-icon"
                style="background: linear-gradient(135deg, #ff4b2b, #ff416c)"
              >
                <i class="fas fa-video"></i>
              </div>
              <h3>Travel Shorts</h3>
              <p>Watch and share your travel moments</p>
              <div class="card-footer">
                <span>Watch <i class="fas fa-arrow-right"></i></span>
              </div>
            </a>

            <a href="modules/bugdet_Module/budget_home.php" class="module-card">
              <div
                class="card-icon"
                style="background: linear-gradient(135deg, #4cc9f0, #4895ef)"
              >
                <i class="fas fa-wallet"></i>
              </div>
              <h3>TripBudget AI</h3>
              <p>Smart expense tracking for your trips</p>
              <div class="card-footer">
                <span>Track <i class="fas fa-arrow-right"></i></span>
              </div>
            </a>

            <a href="modules/todo_module/todo.php" class="module-card">
              <div
                class="card-icon"
                style="background: linear-gradient(135deg, #f72585, #b5179e)"
              >
                <i class="fas fa-tasks"></i>
              </div>
              <h3>PlanSync Hub</h3>
              <p>Organize and sync your travel tasks</p>
              <div class="card-footer">
                <span>Plan <i class="fas fa-arrow-right"></i></span>
              </div>
            </a>

           
          </div>
        </section>

        <!-- Dashboard Widgets -->
        <div class="dashboard-widgets">
          <!-- Upcoming Tasks -->
          <section class="widget-card">
            <h3><i class="fas fa-tasks"></i> Upcoming Tasks</h3>
            <div class="task-list">
              <?php if (count($upcomingTasks) >
              0): ?>
              <?php foreach ($upcomingTasks as $task): ?>
              <div class="task-item">
                <div class="task-info">
                  <h4><?= htmlspecialchars($task['task']) ?></h4>
                  <?php if ($task['due_date']): ?>
                  <span class="task-date"
                    ><i class="far fa-calendar"></i>
                    <?= date('M d, Y', strtotime($task['due_date'])) ?></span
                  >
                  <?php endif; ?>
                </div>
                <a href="modules/todo.php" class="task-action">View</a>
              </div>
              <?php endforeach; ?>
              <?php else: ?>
              <p class="empty-state">
                No upcoming tasks. Add one to get started!
              </p>
              <?php endif; ?>
            </div>
            <a href="modules/todo.php" class="widget-footer">
              View all tasks <i class="fas fa-arrow-right"></i>
            </a>
          </section>

          <!-- Popular Destinations -->
          <section class="widget-card">
            <h3><i class="fas fa-map-marked-alt"></i> Popular Destinations</h3>
            <div class="destinations-list">
              <?php if (count($recentDestinations) >
              0): ?>
              <?php foreach ($recentDestinations as $destination): ?>
              <div class="destination-item">
                <i class="fas fa-map-marker-alt"></i>
                <div class="destination-info">
                  <h4><?= $destination['to_name'] ?></h4>
                  <span
                    ><?= $destination['visits'] ?>
                    visits</span
                  >
                </div>
              </div>
              <?php endforeach; ?>
              <?php else: ?>
              <p class="empty-state">No destinations yet. Start planning!</p>
              <?php endif; ?>
            </div>
            <a
              href="modules/distance_module/distance.php"
              class="widget-footer"
            >
              Explore more <i class="fas fa-arrow-right"></i>
            </a>
          </section>
        </div>
      </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
  </body>
</html>
