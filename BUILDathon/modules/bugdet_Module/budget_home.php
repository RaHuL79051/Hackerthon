<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = "Choose Budget Mode - Tripify";
include_once "../../includes/header.php";
?>

<style>
  body {
    background: #f2f9ff;
    font-family: "Segoe UI", sans-serif;
    margin: 0;
    padding: 0;
  }

  .budget-choose-container {
    max-width: 1100px;
    margin: 4rem auto;
    padding: 2rem;
    display: flex;
    justify-content: space-between;
    gap: 2rem;
    flex-wrap: wrap;
  }

  .budget-card-link {
    flex: 1 1 45%;
    text-decoration: none;
  }

  .budget-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
  }

  .budget-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
  }

  .budget-card i {
    font-size: 3rem;
    color: #0077cc;
    margin-bottom: 1rem;
    animation: bounce 2s infinite;
  }

  .budget-card h2 {
    color: #2c3e50;
    margin-bottom: 0.5rem;
  }

  .budget-card p {
    color: #555;
    font-size: 1rem;
    margin-bottom: 1.5rem;
  }

  .budget-card .btn {
    display: inline-block;
    background: #0077cc;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: background 0.3s ease;
    pointer-events: none; /* Prevent duplicate click event from button inside card */
  }

  .budget-card-link:hover .btn {
    background: #005fa3;
  }

  @keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
  }

  @media (max-width: 768px) {
    .budget-card-link {
      flex: 1 1 100%;
    }

    .budget-choose-container {
      padding: 1rem;
    }
  }
</style>

<div class="budget-choose-container">
  <!-- Solo Budget Mode -->
  <a href="budget_solo.php" class="budget-card-link">
    <div class="budget-card">
      <i class="fas fa-user"></i>
      <h2>Solo Budget Tracker</h2>
      <p>Manage your personal travel expenses and keep track of your spending.</p>
      <div class="btn">Use Solo Mode</div>
    </div>
  </a>

  <!-- Group Budget Mode -->
  <a href="budget_group.php" class="budget-card-link">
    <div class="budget-card">
      <i class="fas fa-users"></i>
      <h2>Group Budget Tracker</h2>
      <p>Split costs among friends and automatically settle up together.</p>
      <div class="btn">Use Group Mode</div>
    </div>
  </a>
</div>

<?php include_once "../../includes/footer.php"; ?>
