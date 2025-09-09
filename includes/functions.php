<?php
require_once 'config.php';

// Sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Check if user exists by email
function userExists($email) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}

// Check if username exists
function usernameExists($username) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}

// Validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Get user by email
function getUserByEmail($email) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id, username, email, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $user;
}

// Get user by ID
function getUserById($id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $user;
}

// Get todos for user
function getTodos($user_id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $todos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $todos;
}

// Add todo
function addTodo($user_id, $title, $description) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO todos (user_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $description);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

// Update todo status
function updateTodoStatus($todo_id, $user_id, $status) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("UPDATE todos SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $todo_id, $user_id);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}

// Delete todo
function deleteTodo($todo_id, $user_id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $todo_id, $user_id);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $success;
}
?>
