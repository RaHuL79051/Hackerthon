<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include_once "../../includes/db.php";

$user_id = $_SESSION['user_id'];

// Fetch all group expenses from DB
$stmt = $pdo->prepare("SELECT * FROM group_expenses WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Budget Tracker - TravelMate Pro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php require_once '../../includes/header.php'; ?>
    <link rel="stylesheet" href="budget_group.css" />
  </head>
  <body>
    <div class="container" style="margin-top: 25px;">
      <h1>💰 Trip Budget Tracker</h1>
      <button class="reset-btn" onclick="resetAll()">Reset All</button>
      <div style="clear: both"></div>
      <div class="people-setup">
        <label>Number of Travelers:</label>
        <input
          type="number"
          id="peopleCount"
          class="people-input"
          min="1"
          value="1"
        />
        <div id="nameFields"></div>
      </div>
      <div class="payment-form">
        <select id="payer"></select>
        <input type="number" id="amount" placeholder="Amount" />
        <input
          type="text"
          id="description"
          placeholder="Description (optional)"
        />
        <button class="add-payment-btn" onclick="addPayment()">Add</button>
      </div>
      <h3>Payment History</h3>
      <div id="paymentList"></div>
      <h3>Settlement Summary</h3>
      <table class="results-table">
        <thead>
          <tr>
            <th>Person</th>
            <th>Total Paid</th>
            <th>Share</th>
            <th>Net Amount</th>
          </tr>
        </thead>
        <tbody id="resultsBody"></tbody>
      </table>
      <div class="who-owes" id="whoOwes"></div>
    </div>
    <script src="budget_group.js"></script>
  </body>
    <?php require_once '../../includes/footer.php'; ?>
</html>
