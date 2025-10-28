# Global Goffer Fund for Nature — Ready-to-run PHP Project

## What you get
- PHP site with pages for:
  - Institutional Framework, Accreditation, Coordination, Private Placement Offers, Itinerary Shares, Registration, Uploads
- Secure Registration (CSRF, prepared statements, password hashing using `password_hash`)
- Secure file uploads (extension + MIME check, size limit, randomized filenames)
- SQLite database (`data/database.sqlite`) or easily adapted to MySQL by changing `includes/config.php`
- Basic Content Security Policy and security headers in `<head>`
- `.htaccess` to prevent PHP execution in the `uploads/` folder

## How to run (local)
1. Ensure PHP (>=7.4) is installed with PDO SQLite extension (or adjust config for MySQL).
2. Place the project in your webroot (e.g., `/var/www/html/global_goffer_fund_for_nature`) or run PHP built-in server:
   ```
   php -S localhost:8000 -t /mnt/data/global_goffer_fund_for_nature
   ```
3. Run `php scripts/init_db.php` once to create the SQLite database and tables.
4. Visit `http://localhost:8000/index.php`

## Files of note
- `includes/config.php` — DB connection and site-wide config
- `includes/header.php`, `includes/footer.php` — common layout and metadata
- `register.php` — registration form
- `process_register.php` — secure form handler
- `uploads/` — user uploads (non-executable)
- `scripts/init_db.php` — create SQLite DB and tables

## Security highlights
- CSRF tokens on forms
- Prepared statements for DB
- `password_hash` + `password_verify` for passwords
- File upload validation (size, extension, mime)
- Basic CSP and security headers
- `.htaccess` in `uploads/` to prevent PHP execution

