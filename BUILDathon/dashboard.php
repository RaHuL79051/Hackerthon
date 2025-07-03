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
    <style>
      /* ===== DASHBOARD STYLES ===== */
      .dashboard-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
      }

      .dashboard-hero {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);
        border-radius: 20px;
        padding: 3rem;
        color: white;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      }

      .hero-content h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        line-height: 1.2;
      }

      .emoji {
        font-size: 1.5em;
        vertical-align: middle;
      }

      .subheading {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        max-width: 80%;
      }

      .hero-stats {
        display: flex;
        gap: 1.5rem;
        margin-top: 2rem;
      }

      .stat-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        width: 180px;
        transition: transform 0.3s ease;
      }

      .stat-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.25);
      }

      .stat-card i {
        font-size: 1.8rem;
        opacity: 0.8;
      }

      .stat-number {
        display: block;
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
      }

      .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
      }

      .hero-image {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        height: 300px;
      }

      .hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      /* Quick Actions */
      .quick-actions {
        margin-bottom: 2.5rem;
      }

      .quick-actions h2 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.4rem;
        margin-bottom: 1.5rem;
        color: #2b2d42;
      }

      .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.2rem;
      }

      .action-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        text-decoration: none;
        color: #3a0ca3;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 2px solid transparent;
      }

      .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #4361ee;
      }

      .action-card i {
        font-size: 2rem;
        color: #4361ee;
      }

      /* Dashboard Content */
      .dashboard-content {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 2rem;
      }

      /* Module Section */
      .module-section h2 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.4rem;
        margin-bottom: 1.5rem;
        color: #2b2d42;
      }

      .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.8rem;
      }

      .module-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 7px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        text-decoration: none;
        color: #2b2d42;
        display: flex;
        flex-direction: column;
      }

      .module-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
      }

      .card-icon {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .card-icon i {
        font-size: 3rem;
        color: white;
      }

      .module-card h3 {
        padding: 1.2rem 1.5rem 0.5rem;
        font-size: 1.3rem;
      }

      .module-card p {
        padding: 0 1.5rem;
        color: #6c757d;
        flex-grow: 1;
      }

      .card-footer {
        padding: 1.2rem 1.5rem;
        background: #f8f9fa;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        font-weight: 600;
        color: #4361ee;
      }

      /* Widgets */
      .dashboard-widgets {
        display: flex;
        flex-direction: column;
        gap: 1.8rem;
      }

      .widget-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 7px 20px rgba(0, 0, 0, 0.08);
      }

      .widget-card h3 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2rem;
        margin-bottom: 1.2rem;
        color: #2b2d42;
      }

      .task-list,
      .destinations-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }

      .task-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem;
        border-radius: 10px;
        background: #f8f9fa;
        transition: all 0.2s ease;
      }

      .task-item:hover {
        background: #edf2ff;
      }

      .task-info h4 {
        font-size: 1rem;
        margin-bottom: 0.3rem;
      }

      .task-date {
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 5px;
      }

      .task-action {
        background: #4361ee;
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.9rem;
        text-decoration: none;
        transition: background 0.2s;
      }

      .task-action:hover {
        background: #3a0ca3;
      }

      .destination-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.8rem;
        border-radius: 10px;
        background: #f8f9fa;
      }

      .destination-item i {
        font-size: 1.2rem;
        color: #4361ee;
      }

      .destination-info h4 {
        font-size: 1rem;
        margin-bottom: 0.2rem;
      }

      .destination-info span {
        font-size: 0.85rem;
        color: #6c757d;
      }

      .empty-state {
        text-align: center;
        padding: 1.5rem;
        color: #6c757d;
        font-style: italic;
      }

      .widget-footer {
        display: block;
        margin-top: 1.2rem;
        text-align: center;
        color: #4361ee;
        font-weight: 600;
        text-decoration: none;
        padding-top: 1rem;
        border-top: 1px solid #eee;
      }
    </style>
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
          <a href="modules/todo_module/todo.php" class="action-card">
            <i class="fas fa-plus-circle"></i>
            <span>Add Task</span>
          </a>
          <a href="modules/bugdet_Module/budget.php" class="action-card">
            <i class="fas fa-wallet"></i>
            <span>Add Expense</span>
          </a>
          <a href="#" class="action-card">
            <i class="fas fa-robot"></i>
            <span>Ask Assistant</span>
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

            <a href="modules/bugdet_Module/budget.php" class="module-card">
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