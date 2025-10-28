<?php
require_once __DIR__ . '/includes/config.php';

// Simple server-side validation and secure insert
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

if (!csrf_check($_POST['csrf_token'] ?? '')) {
    exit('CSRF check failed');
}

$fullname = trim($_POST['fullname'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
$organisation = trim($_POST['organisation'] ?? '');

$errors = [];
if (strlen($fullname) < 2) $errors[] = 'Full name is too short';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters';

if ($errors) {
    // In production, redirect back with flash messages. For demo print them.
    foreach ($errors as $e) echo '<p>'.htmlspecialchars($e).'</p>';
    echo '<p><a href="register.php">Back</a></p>';
    exit;
}

// check if email exists
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    exit('Email already registered. <a href="register.php">Back</a>');
}

// hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// insert
$stmt = $pdo->prepare('INSERT INTO users (fullname, email, password_hash, organisation, created_at) VALUES (?, ?, ?, ?, datetime("now"))');
$stmt->execute([$fullname, $email, $hash, $organisation]);

echo '<p>Registration successful. <a href="index.php">Home</a></p>';
