<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/header.php';
$token = csrf_token();
// fetch uploaded files metadata
$files = $pdo->query('SELECT id, original_name, stored_name, uploaded_at FROM uploads ORDER BY uploaded_at DESC')->fetchAll();
?>
<main class="container py-5">
  <h1>Uploads</h1>
  <p>Upload documents related to offers, itineraries or accreditation. Max 5MB per file.</p>
  <div class="card p-3 mb-3">
    <form action="process_upload.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token); ?>">
      <div class="mb-3">
        <label class="form-label">Choose file</label>
        <input type="file" name="file" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Description (optional)</label>
        <input name="description" class="form-control" maxlength="500" />
      </div>
      <button class="btn btn-primary">Upload</button>
    </form>
  </div>

  <h3 class="mt-4">Recent uploads</h3>
  <div class="list-group">
  <?php foreach ($files as $f): ?>
    <a class="list-group-item list-group-item-action" href="/uploads/<?php echo htmlspecialchars($f['stored_name']); ?>" target="_blank">
      <?php echo htmlspecialchars($f['original_name']); ?> — <small><?php echo htmlspecialchars($f['uploaded_at']); ?></small>
    </a>
  <?php endforeach; ?>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>