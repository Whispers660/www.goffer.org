<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}
if (!csrf_check($_POST['csrf_token'] ?? '')) {
    exit('CSRF check failed');
}
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    exit('No file uploaded or upload error');
}

$file = $_FILES['file'];
if ($file['size'] > MAX_UPLOAD_BYTES) {
    exit('File too large');
}

$original = basename($file['name']);
$ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
global $ALLOWED_EXT, $ALLOWED_MIME;
if (!in_array($ext, $ALLOWED_EXT)) {
    exit('Disallowed file extension');
}

// verify MIME type
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);
if (!in_array($mime, $ALLOWED_MIME)) {
    exit('Disallowed MIME type: ' . htmlspecialchars($mime));
}

// generate safe random filename
$stored = bin2hex(random_bytes(16)) . '.' . $ext;
$dest = UPLOAD_DIR . $stored;

// ensure uploads dir exists and not web-executable (should have .htaccess)
if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

// move uploaded file
if (!move_uploaded_file($file['tmp_name'], $dest)) {
    exit('Failed to save uploaded file');
}

// record in DB
$stmt = $pdo->prepare('INSERT INTO uploads (original_name, stored_name, description, uploaded_at) VALUES (?, ?, ?, datetime("now"))');
$stmt->execute([$original, $stored, substr(trim($_POST['description'] ?? ''), 0, 500)]);

echo '<p>Upload successful. <a href="uploads.php">Back</a></p>';
