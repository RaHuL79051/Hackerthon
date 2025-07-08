<?php
require_once '../includes/db.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: ?page=users");
    exit;
}

// Handle add user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password_raw = $_POST['password'];
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    $stmt->execute([$email, $username, $password]);
    header("Location: ?page=users");
    exit;
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashed_password, $user_id]);
    header("Location: ?page=users");
    exit;
}

// Fetch users
$users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>All Users</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
</div>

<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Email</th>
      <th>Username</th>
      <th>Password (Hashed)</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><code style="font-size: 0.75rem; word-break: break-all;"><?= htmlspecialchars($user['password']) ?></code></td>
        <td>
          <a href="?page=users&delete=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
          <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal<?= $user['id'] ?>">Reset Password</button>

          <!-- Reset Password Modal -->
          <div class="modal fade" id="resetPasswordModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="resetPasswordModalLabel<?= $user['id'] ?>" aria-hidden="true">
            <div class="modal-dialog">
              <form method="POST" class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="resetPasswordModalLabel<?= $user['id'] ?>">Reset Password for <?= htmlspecialchars($user['username']) ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                  <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input name="new_password" required type="text" class="form-control">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" name="reset_password" class="btn btn-warning">Update Password</button>
                </div>
              </form>
            </div>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" required type="email" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input name="username" required type="text" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input name="password" required type="text" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_user" class="btn btn-success">Add User</button>
      </div>
    </form>
  </div>
</div>
