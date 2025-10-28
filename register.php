<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/header.php';
$token = csrf_token();
?>
<main class="container py-5">
  <h1>Registration</h1>
  <p>Create an account to access restricted content and upload documents.</p>
  <div class="card p-4">
    <form action="process_register.php" method="post" novalidate>
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token); ?>">
      <div class="mb-3">
        <label class="form-label">Full name</label>
        <input required name="fullname" class="form-control" maxlength="150" />
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input required name="email" type="email" class="form-control" maxlength="255" />
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input required name="password" type="password" class="form-control" minlength="8" />
        <div class="form-text">Minimum 8 characters. Passwords are hashed and never stored in plain text.</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Organisation (optional)</label>
        <input name="organisation" class="form-control" maxlength="255" />
      </div>
      <button class="btn btn-primary" type="submit">Register</button>
    </form>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>