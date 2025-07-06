<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once __DIR__ . '/../../includes/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle AJAX actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $amount = floatval($_POST['amount']);
            $desc = trim($_POST['description']);
            $stmt = $pdo->prepare("INSERT INTO expenses (user_id, amount, description) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $amount, $desc]);
            exit(json_encode(['status' => 'success']));
        }
        if ($_POST['action'] === 'delete') {
            $exp_id = intval($_POST['id']);
            $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
            $stmt->execute([$exp_id, $user_id]);
            exit(json_encode(['status' => 'success']));
        }
        if ($_POST['action'] === 'update') {
            $exp_id = intval($_POST['id']);
            $amount = floatval($_POST['amount']);
            $desc = trim($_POST['description']);
            $stmt = $pdo->prepare("UPDATE expenses SET amount = ?, description = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$amount, $desc, $exp_id, $user_id]);
            exit(json_encode(['status' => 'success']));
        }
    }
    exit(json_encode(['status' => 'error']));
}

// Fetch all user's expenses
$stmt = $pdo->prepare("SELECT * FROM expenses WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$total_paid = 0;
foreach ($expenses as $exp) $total_paid += $exp['amount'];

// Page title for header
$page_title = "Budget Tracker - Tripify";
?>

<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<link rel="stylesheet" href="budget.css">
<style>
        :root {
        --primary: #1a73e8;
        --secondary: #ff7043;
        --success: #34a853;
        --danger: #ea4335;
        --light: #f8f9fa;
        --dark: #202124;
        --radius: 16px;
        --shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
        }

        .budget-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 1rem;
        }

        .budget-header {
        background: linear-gradient(135deg, var(--primary) 0%, #0d5fcc 100%);
        color: white;
        padding: 2.5rem;
        border-radius: var(--radius);
        text-align: center;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
        }

        .budget-header::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        pointer-events: none;
        }

        .budget-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        }

        .welcome-text {
        font-size: 1.2rem;
        opacity: 0.9;
        position: relative;
        }

        .main-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
        }

        .expense-form-card {
        background: white;
        padding: 2rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        height: fit-content;
        }

        .summary-card {
        background: linear-gradient(135deg, rgba(240, 245, 255, 0.8) 0%, rgba(255, 240, 236, 0.6) 100%);
        padding: 2rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid rgba(26, 115, 232, 0.1);
        }

        .form-title {
        color: var(--dark);
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        }

        .form-title::before {
        content: "💰";
        font-size: 1.2rem;
        }

        .expense-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        }

        .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        }

        .form-label {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
        }

        .form-input {
        padding: 0.8rem;
        border: 2px solid #e0e7ff;
        border-radius: 12px;
        font-size: 1rem;
        transition: var(--transition);
        background: white;
        }

        .form-input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        }

        .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #0d5fcc 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
        }

        .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(26, 115, 232, 0.4);
        }

        .summary-stats {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        }

        .stat-item {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        display: block;
        }

        .stat-label {
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
        }

        .history-section {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        }

        .section-header {
        background: linear-gradient(90deg, var(--primary) 0%, #0d5fcc 100%);
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        }

        .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
        }

        .btn-danger {
        background: linear-gradient(135deg, var(--danger) 0%, #c62828 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9rem;
        }

        .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(234, 67, 53, 0.3);
        }

        .history-table {
        width: 100%;
        border-collapse: collapse;
        }

        .history-table th,
        .history-table td {
        padding: 1rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
        }

        .history-table th {
        background: rgba(240, 245, 255, 0.5);
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
        }

        .history-table tbody tr:hover {
        background: rgba(26, 115, 232, 0.02);
        }

        .action-buttons {
        display: flex;
        gap: 0.5rem;
        }

        .btn-edit,
        .btn-delete {
        padding: 0.4rem 0.8rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8rem;
        font-weight: 500;
        transition: var(--transition);
        }

        .btn-edit {
        background: rgba(26, 115, 232, 0.1);
        color: var(--primary);
        }

        .btn-edit:hover {
        background: rgba(26, 115, 232, 0.2);
        }

        .btn-delete {
        background: rgba(234, 67, 53, 0.1);
        color: var(--danger);
        }

        .btn-delete:hover {
        background: rgba(234, 67, 53, 0.2);
        }

        .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #666;
        }

        .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
        }

        @media (max-width: 768px) {
        .main-content {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .expense-form {
            gap: 0.8rem;
        }
        
        .history-table {
            font-size: 0.9rem;
        }
        
        .history-table th,
        .history-table td {
            padding: 0.8rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 0.3rem;
        }
        }
</style>

<div class="budget-container">
    <!-- Header Section -->
    <div class="budget-header">
        <h1 class="budget-title">💰 Trip Budget Tracker</h1>
        <p class="welcome-text">Welcome back, <strong><?= htmlspecialchars($user['username']) ?></strong>!</p>
    </div>

    <!-- Main Content Grid -->
    <div class="main-content">
        <!-- Add Expense Form -->
        <div class="expense-form-card">
            <h2 class="form-title">Add New Expense</h2>
            <form class="expense-form" onsubmit="addPayment(event)">
                <div class="form-group">
                    <label class="form-label" for="amount">Amount ($)</label>
                    <input type="number" id="amount" class="form-input" placeholder="0.00" min="0.01" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <input type="text" id="description" class="form-input" placeholder="What did you spend on?">
                </div>
                <button type="submit" class="btn-primary">Add Expense</button>
            </form>
        </div>

        <!-- Summary Card -->
        <div class="summary-card">
            <h2 class="form-title">📊 Summary</h2>
            <div class="summary-stats">
                <div class="stat-item">
                    <span class="stat-value">$<?= number_format($total_paid, 2) ?></span>
                    <span class="stat-label">Total Expenses</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History Section -->
    <div class="history-section">
        <div class="section-header">
            <h3 class="section-title">📋 Payment History</h3>
            <button class="btn-danger" onclick="resetAll()">Reset All</button>
        </div>
        
        <?php if (empty($expenses)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📝</div>
                <p><strong>No expenses yet!</strong></p>
                <p>Start by adding your first expense above.</p>
            </div>
        <?php else: ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($expenses as $exp): ?>
                        <tr data-id="<?= $exp['id'] ?>">
                            <td><strong>$<?= number_format($exp['amount'], 2) ?></strong></td>
                            <td><?= htmlspecialchars($exp['description'] ?: 'No description') ?></td>
                            <td><?= date('M j, Y g:i A', strtotime($exp['created_at'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editPayment(<?= $exp['id'] ?>, <?= $exp['amount'] ?>, '<?= htmlspecialchars(addslashes($exp['description'])) ?>')">Edit</button>
                                    <button class="btn-delete" onclick="deletePayment(<?= $exp['id'] ?>)">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
function addPayment(event) {
    event.preventDefault();
    let amount = document.getElementById('amount').value;
    let description = document.getElementById('description').value;
    
    if (!amount || amount <= 0) {
        alert('Please enter a valid amount');
        return;
    }
    
    fetch('', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=add&amount=' + encodeURIComponent(amount) + '&description=' + encodeURIComponent(description)
    })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') {
            location.reload();
        } else {
            alert('Error adding expense. Please try again.');
        }
    });
}

function deletePayment(id) {
    if (!confirm('Are you sure you want to delete this expense?')) return;
    
    fetch('', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=delete&id=' + id
    })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') location.reload();
    });
}

function editPayment(id, amount, description) {
    let newAmount = prompt('Edit amount:', amount);
    if (newAmount === null || newAmount <= 0) return;
    
    let newDesc = prompt('Edit description:', description);
    if (newDesc === null) return;
    
    fetch('', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=update&id=' + id + '&amount=' + encodeURIComponent(newAmount) + '&description=' + encodeURIComponent(newDesc)
    })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') location.reload();
    });
}

function resetAll() {
    if (!confirm('⚠️ This will permanently delete ALL your expenses!\n\nThis action cannot be undone. Continue?')) return;
    
    // Delete all expenses one by one
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => btn.click());
}
</script>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
