<?php
session_start();
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);
$todos = getTodos($user_id);

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_todo'])) {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    
    if (empty($title)) {
        $errors[] = "Title is required.";
    } else {
        if (addTodo($user_id, $title, $description)) {
            $success = "Todo added successfully!";
            $todos = getTodos($user_id); // Refresh list
        } else {
            $errors[] = "Failed to add todo.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_status'])) {
    $todo_id = (int)$_POST['todo_id'];
    $new_status = $_POST['new_status'];
    if (updateTodoStatus($todo_id, $user_id, $new_status)) {
        $todos = getTodos($user_id);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_todo'])) {
    $todo_id = (int)$_POST['todo_id'];
    if (deleteTodo($todo_id, $user_id)) {
        $todos = getTodos($user_id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Todo App</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- Modern Header with Profile -->
        <header class="dashboard-header">
            <div class="header-content">
                <h1 class="app-title">✓ TodoApp</h1>
                <div class="user-profile">
                    <div class="user-info">
                        <p class="user-name"><?php echo htmlspecialchars($user['username']); ?></p>
                        <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div class="user-avatar" onclick="toggleProfileDropdown()">
                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    <div class="profile-dropdown" id="profileDropdown">


                        <form method="POST" action="logout.php" style="margin: 0;">
                            <button type="submit" class="dropdown-item logout">
                                <span>🚪</span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Add Todo Section -->
            <div class="content-card">
                <h2 class="section-title">
                    <span>➕</span>
                    Add New Todo
                </h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required placeholder="Enter todo title...">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" placeholder="Enter todo description..."></textarea>
                    </div>
                    
                    <button type="submit" name="add_todo" class="btn">
                        <span>➕</span>
                        Add Todo
                    </button>
                </form>
                
                <!-- Stats Section -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <p class="stat-number"><?php echo count($todos); ?></p>
                        <p class="stat-label">Total Todos</p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-number"><?php echo count(array_filter($todos, function($t) { return $t['status'] == 'completed'; })); ?></p>
                        <p class="stat-label">Completed</p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-number"><?php echo count(array_filter($todos, function($t) { return $t['status'] == 'pending'; })); ?></p>
                        <p class="stat-label">Pending</p>
                    </div>
                </div>
            </div>

            <!-- Todos List Section -->
            <div class="content-card">
                <h2 class="section-title">
                    <span>📝</span>
                    Your Todos
                </h2>
                
                <?php if (empty($todos)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📝</div>
                        <p>No todos yet. Create your first todo to get started!</p>
                    </div>
                <?php else: ?>
                    <ul class="todo-list">
                        <?php foreach ($todos as $todo): ?>
                            <li class="todo-item <?php echo $todo['status']; ?>">
                                <div class="todo-content">
                                    <div class="flex items-center gap-2">
                                        <h3><?php echo htmlspecialchars($todo['title']); ?></h3>
                                        <span class="status-badge <?php echo $todo['status']; ?>">
                                            <?php echo $todo['status'] == 'pending' ? '⏳ Pending' : '✅ Completed'; ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($todo['description'])): ?>
                                        <p><?php echo htmlspecialchars($todo['description']); ?></p>
                                    <?php endif; ?>
                                    <small>📅 Created: <?php echo date('M j, Y g:i A', strtotime($todo['created_at'])); ?></small>
                                </div>
                                <div class="todo-actions">
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="todo_id" value="<?php echo $todo['id']; ?>">
                                        <input type="hidden" name="new_status" value="<?php echo $todo['status'] == 'pending' ? 'completed' : 'pending'; ?>">
                                        <button type="submit" name="toggle_status" class="btn-small <?php echo $todo['status'] == 'pending' ? 'btn-success' : 'btn-secondary'; ?>">
                                            <?php echo $todo['status'] == 'pending' ? '✓ Complete' : '↻ Reopen'; ?>
                                        </button>
                                    </form>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="todo_id" value="<?php echo $todo['id']; ?>">
                                        <button type="submit" name="delete_todo" class="btn-small btn-danger" onclick="return confirm('Are you sure you want to delete this todo?')">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profile = document.querySelector('.user-profile');
            const dropdown = document.getElementById('profileDropdown');
            
            if (!profile.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Auto-hide success/error messages
        setTimeout(function() {
            const messages = document.querySelectorAll('.success, .error');
            messages.forEach(function(message) {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    message.style.display = 'none';
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>
