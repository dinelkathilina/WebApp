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
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <a href="logout.php">Logout</a>
        
        <h2>Add New Todo</h2>
        
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
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>
            
            <button type="submit" name="add_todo">Add Todo</button>
        </form>
        
        <h2>Your Todos</h2>
        
        <?php if (empty($todos)): ?>
            <p>No todos yet. Add one above!</p>
        <?php else: ?>
            <ul class="todo-list">
                <?php foreach ($todos as $todo): ?>
                    <li class="todo-item <?php echo $todo['status']; ?>">
                        <div class="todo-content">
                            <h3><?php echo htmlspecialchars($todo['title']); ?></h3>
                            <p><?php echo htmlspecialchars($todo['description']); ?></p>
                            <small>Created: <?php echo $todo['created_at']; ?></small>
                        </div>
                        <div class="todo-actions">
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="todo_id" value="<?php echo $todo['id']; ?>">
                                <input type="hidden" name="new_status" value="<?php echo $todo['status'] == 'pending' ? 'completed' : 'pending'; ?>">
                                <button type="submit" name="toggle_status">
                                    <?php echo $todo['status'] == 'pending' ? 'Mark Complete' : 'Mark Pending'; ?>
                                </button>
                            </form>
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="todo_id" value="<?php echo $todo['id']; ?>">
                                <button type="submit" name="delete_todo" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
