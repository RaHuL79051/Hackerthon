<?php
require_once '../includes/db.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: ?page=expenses");
    exit;
}

// Handle add expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO expenses (user_id, amount, description) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $amount, $description]);
    header("Location: ?page=expenses");
    exit;
}

// Handle update expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_expense'])) {
    $id = $_POST['expense_id'];
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE expenses SET user_id = ?, amount = ?, description = ? WHERE id = ?");
    $stmt->execute([$user_id, $amount, $description, $id]);
    header("Location: ?page=expenses");
    exit;
}

// Fetch users for dropdown
$users = $pdo->query("SELECT id, username FROM users")->fetchAll(PDO::FETCH_ASSOC);

// Fetch expenses
$expenses = $pdo->query("SELECT expenses.*, users.username FROM expenses JOIN users ON expenses.user_id = users.id ORDER BY expenses.id DESC")
                ->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>All Expenses</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">Add Expense</button>
</div>

<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>User</th>
      <th>Amount (₹)</th>
      <th>Description</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($expenses as $exp): ?>
      <tr>
        <td><?= $exp['id'] ?></td>
        <td><?= htmlspecialchars($exp['username']) ?></td>
        <td><?= number_format($exp['amount'], 2) ?></td>
        <td><?= htmlspecialchars($exp['description']) ?></td>
        <td>
          <a href="?page=expenses&delete=<?= $exp['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this expense?')">Delete</a>
          <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editExpenseModal<?= $exp['id'] ?>">Edit</button>

          <!-- Edit Expense Modal -->
          <div class="modal fade" id="editExpenseModal<?= $exp['id'] ?>" tabindex="-1" aria-labelledby="editExpenseModalLabel<?= $exp['id'] ?>" aria-hidden="true">
            <div class="modal-dialog">
              <form method="POST" class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editExpenseModalLabel<?= $exp['id'] ?>">Edit Expense</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="expense_id" value="<?= $exp['id'] ?>">
                  <div class="mb-3">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select">
                      <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= $u['id'] == $exp['user_id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['username']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Amount (₹)</label>
                    <input name="amount" required type="number" step="0.01" value="<?= $exp['amount'] ?>" class="form-control">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" required><?= htmlspecialchars($exp['description']) ?></textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" name="edit_expense" class="btn btn-warning">Update</button>
                </div>
              </form>
            </div>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addExpenseModalLabel">Add New Expense</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">User</label>
          <select name="user_id" required class="form-select">
            <?php foreach ($users as $u): ?>
              <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Amount (₹)</label>
          <input name="amount" required type="number" step="0.01" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" required class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_expense" class="btn btn-success">Add Expense</button>
      </div>
    </form>
  </div>
</div>