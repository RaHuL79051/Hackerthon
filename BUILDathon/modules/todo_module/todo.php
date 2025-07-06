<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Initialize tasks as empty array
$tasks = [];

// Add new task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = trim($_POST['task']);
    $dueDate = $_POST['due_date'] ?: null;
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task, due_date) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $task, $dueDate]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Update task status
if (isset($_GET['toggle'])) {
    $taskId = $_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE tasks SET status = IF(status='pending', 'completed', 'pending') WHERE id = ? AND user_id = ?");
    $stmt->execute([$taskId, $_SESSION['user_id']]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Delete task
if (isset($_GET['delete'])) {
    $taskId = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$taskId, $_SESSION['user_id']]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch user's tasks
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC, created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll();
?>
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>
<style>
  :root {
    --primary: #4f8cff;
    --primary-dark: #2b5ca8;
    --accent: #ffb347;
    --accent-light: #ffdda9;
    --background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    --card-bg: rgba(255, 255, 255, 0.92);
    --text: #2d3748;
    --text-light: #718096;
    --success: #48bb78;
    --danger: #e53e3e;
    --pending: #f6e05e;
    --completed: #68d391;
    --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
      0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
    background: var(--background);
    min-height: 100vh;
    color: var(--text);
    padding: 2rem;
    display: flex;
    justify-content: center;
    align-items: center;
    line-height: 1.6;
  }

  .trip-planner-container {
    max-width: 700px;
    width: 100%;
    margin: auto;
    background: var(--card-bg);
    border-radius: 24px;
    padding: 2.5rem;
    box-shadow: var(--shadow);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  /* Decorative elements */
  .trip-planner-container::before {
    content: "";
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    background: linear-gradient(45deg, var(--primary), var(--accent-light));
    border-radius: 50%;
    z-index: -1;
    opacity: 0.15;
  }

  .trip-planner-container::after {
    content: "";
    position: absolute;
    bottom: -80px;
    left: -80px;
    width: 250px;
    height: 250px;
    background: linear-gradient(45deg, var(--accent), var(--primary));
    border-radius: 50%;
    z-index: -1;
    opacity: 0.1;
  }

  /* Header section */
  .trip-header {
    text-align: center;
    margin-bottom: 2rem;
    position: relative;
  }

  .trip-title {
    font-size: 2.5rem;
    color: var(--primary-dark);
    margin-bottom: 0.5rem;
    background: linear-gradient(45deg, var(--primary-dark), var(--accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    position: relative;
    display: inline-block;
  }

  .trip-title::after {
    content: "✈️";
    position: absolute;
    right: -40px;
    top: -5px;
    font-size: 1.8rem;
    animation: float 3s ease-in-out infinite;
  }

  .trip-subtitle {
    font-size: 1.1rem;
    color: var(--text-light);
    max-width: 80%;
    margin: 0 auto;
    line-height: 1.7;
  }

  /* Task form */
  .trip-form {
    display: flex;
    gap: 1rem;
    margin-bottom: 2.5rem;
    position: relative;
    z-index: 2;
  }

  .trip-form input[type="text"] {
    flex: 1;
    padding: 1rem 1.2rem;
    border: none;
    border-radius: 14px;
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.8);
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
    transition: var(--transition);
    border: 1px solid rgba(0, 0, 0, 0.05);
  }

  .trip-form input[type="text"]:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(79, 140, 255, 0.2);
    background: white;
  }

  .trip-form input[type="date"] {
    flex: 0.7;
    padding: 1rem;
    border: none;
    border-radius: 14px;
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.8);
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
    transition: var(--transition);
    border: 1px solid rgba(0, 0, 0, 0.05);
  }

  .trip-form button {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 6px rgba(79, 140, 255, 0.2);
    transition: var(--transition);
  }

  .trip-form button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(79, 140, 255, 0.3);
  }

  .trip-form button:active {
    transform: translateY(1px);
  }

  /* Task summary */
  .task-summary {
    background: rgba(79, 140, 255, 0.1);
    padding: 1rem 1.5rem;
    border-radius: 14px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 12px;
    border-left: 4px solid var(--primary);
    animation: fadeIn 0.6s ease-out;
  }

  .task-summary h3 {
    font-size: 1.2rem;
    color: var(--primary-dark);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .task-count {
    background: var(--primary);
    color: white;
    padding: 0.2rem 0.8rem;
    border-radius: 20px;
    font-size: 0.9rem;
  }

  /* Task list */
  .trip-task-list {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
  }

  /* Empty state */
  .empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 16px;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.03);
    animation: fadeIn 0.8s ease-out;
  }

  .empty-state p {
    font-size: 1.1rem;
    color: var(--text-light);
    margin-bottom: 1.5rem;
  }

  .empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--primary);
    opacity: 0.7;
    animation: pulse 2s infinite;
  }

  /* Task item */
  .trip-task-item {
    background: white;
    padding: 1.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    animation: slideIn 0.5s ease-out;
  }

  .trip-task-item::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 6px;
    background: var(--pending);
    transition: var(--transition);
  }

  .trip-task-item.completed::before {
    background: var(--completed);
  }

  .trip-task-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
  }

  .trip-task-item.completed {
    background: rgba(104, 211, 145, 0.08);
  }

  .trip-task-text {
    font-size: 1.15rem;
    flex: 1;
    font-weight: 500;
    color: var(--text);
    padding-right: 1rem;
    position: relative;
    transition: var(--transition);
  }

  .trip-task-item.completed .trip-task-text {
    color: var(--text-light);
  }

  .trip-due-date {
    font-size: 0.95rem;
    background: rgba(79, 140, 255, 0.15);
    color: var(--primary-dark);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    margin: 0.5rem 0;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
  }

  .trip-task-actions {
    display: flex;
    gap: 0.8rem;
    margin-top: 0.8rem;
    width: 100%;
    justify-content: flex-end;
  }

  .trip-toggle-btn,
  .trip-delete-btn {
    text-decoration: none;
    font-size: 0.95rem;
    padding: 0.6rem 1rem;
    border-radius: 12px;
    transition: var(--transition);
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .trip-toggle-btn {
    background: rgba(72, 187, 120, 0.15);
    color: var(--success);
  }

  .trip-toggle-btn:hover {
    background: rgba(72, 187, 120, 0.25);
  }

  .trip-delete-btn {
    background: rgba(229, 62, 62, 0.1);
    color: var(--danger);
  }

  .trip-delete-btn:hover {
    background: rgba(229, 62, 62, 0.2);
  }

  /* Animations */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateX(20px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

  @keyframes float {
    0% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-10px);
    }
    100% {
      transform: translateY(0);
    }
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.1);
    }
    100% {
      transform: scale(1);
    }
  }

  /* Responsive */
  @media (max-width: 768px) {
    body {
      padding: 1.5rem;
    }

    .trip-planner-container {
      padding: 1.8rem;
    }

    .trip-form {
      flex-direction: column;
    }

    .trip-form input[type="text"],
    .trip-form input[type="date"] {
      width: 100%;
    }

    .trip-task-item {
      flex-direction: column;
      align-items: flex-start;
    }

    .trip-task-actions {
      justify-content: flex-start;
    }
  }
  .back-dashboard-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(90deg, #4f8cff, #6a82fb);
    color: #fff;
    padding: 0.7rem 1.3rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(79, 140, 255, 0.08);
    margin-bottom: 1.5rem;
    transition: background 0.2s, transform 0.12s;
  }

  .back-dashboard-btn:hover {
    background: linear-gradient(90deg, #2b5ca8, #4f8cff);
    transform: translateY(-2px) scale(1.04);
    text-decoration: none;
    color: #fff;
  }
</style>

<a href="../../dashboard.php" class="back-dashboard-btn">
  <i class="fas fa-arrow-left"></i> Back to Dashboard
</a>
<div class="trip-planner-container">
  <div class="trip-header">
    <h2 class="trip-title">Trip Planner</h2>
    <p class="trip-subtitle">
      Organize your travel tasks and make your journey unforgettable ✨
    </p>
  </div>

  <!-- Add Task Form -->
  <form method="POST" class="trip-form">
    <input type="text" name="task" placeholder="Add a new task..." required />
    <input type="date" name="due_date" />
    <button type="submit"><i class="fas fa-plus"></i> Add Task</button>
  </form>

  <!-- Task Summary -->
  <div class="task-summary">
    <h3>
      <i class="fas fa-tasks"></i> Your Task List
      <span class="task-count"><?= count($tasks) ?></span>
    </h3>
  </div>

  <!-- Task List -->
  <div class="trip-task-list">
    <?php if (count($tasks) === 0): ?>
    <div class="empty-state">
      <div class="empty-icon">✈️</div>
      <p>Your trip planning starts here! Add your first task to begin.</p>
      <p>No tasks yet! Add something above to start planning. 🌱</p>
    </div>
    <?php else: ?>
    <?php foreach ($tasks as $task): ?>
    <div
      class="trip-task-item <?= $task['status'] === 'completed' ? 'completed' : '' ?>"
    >
      <span class="trip-task-text"><?= htmlspecialchars($task['task']) ?></span>

      <?php if ($task['due_date']): ?>
      <span class="trip-due-date"
        ><i class="far fa-calendar"></i>
        <?= date('M d, Y', strtotime($task['due_date'])) ?></span
      >
      <?php endif; ?>

      <div class="trip-task-actions">
        <a href="?toggle=<?= $task['id'] ?>" class="trip-toggle-btn">
          <i
            class="fas <?= $task['status'] === 'pending' ? 'fa-check-circle' : 'fa-undo' ?>"
          ></i>
          <?= $task['status'] === 'pending' ? 'Mark Complete' : 'Mark Pending' ?>
        </a>
        <a href="?delete=<?= $task['id'] ?>" class="trip-delete-btn">
          <i class="fas fa-trash-alt"></i> Delete
        </a>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

