# UniHub — Student Course Hub
A full PHP/MySQL web application for marketing university programmes to prospective students.

---

## Tech Stack
- **Backend:** PHP 8+ (plain PHP, no framework)
- **Database:** MySQL 8+
- **Frontend:** Vanilla HTML/CSS/JS (no build tools required)

---

## Project Structure
```
student_course_hub/
├── index.php                  # Homepage
├── programmes.php             # Programme listing with search & filter
├── programme.php              # Individual programme detail + interest form
├── database.sql               # Full database schema + seed data
├── setup.php                  # Run once to create admin account (then delete)
│
├── includes/
│   ├── db.php                 # PDO database connection
│   ├── helpers.php            # h(), redirect(), flash messages, etc.
│   ├── header.php             # Public site header/nav
│   └── footer.php             # Public site footer
│
├── admin/
│   ├── login.php              # Admin login
│   ├── logout.php             # Admin logout
│   ├── dashboard.php          # Overview stats + recent activity
│   ├── programmes.php         # CRUD programmes + publish/unpublish + module management
│   ├── modules.php            # CRUD modules
│   ├── staff.php              # CRUD staff
│   ├── interests.php          # View/delete mailing lists + CSV export
│   └── includes/
│       ├── admin_header.php   # Sidebar + topbar template
│       └── admin_footer.php   # Closing tags + JS
│
└── assets/
    ├── css/
    │   ├── public.css         # Student-facing styles
    │   └── admin.css          # Admin dashboard styles
    └── js/
        └── public.js          # Nav toggle, year tabs, search
```

---

## Setup Instructions

### 1. Import the Database
Open your MySQL client (phpMyAdmin, MySQL Workbench, or CLI) and run:
```sql
SOURCE /path/to/student_course_hub/database.sql;
```
Or via CLI:
```bash
mysql -u root -p < database.sql
```

### 2. Configure Database Connection
Edit `includes/db.php` and update the constants:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // your MySQL username
define('DB_PASS', '');           // your MySQL password
define('DB_NAME', 'student_course_hub');
```

### 3. Place Files on Your Server
Copy the entire `student_course_hub/` folder to your web server root, e.g.:
- **XAMPP:** `C:/xampp/htdocs/student_course_hub/`
- **WAMP:** `C:/wamp64/www/student_course_hub/`
- **Linux:** `/var/www/html/student_course_hub/`

### 4. Create the Admin Account
Visit: `http://localhost/student_course_hub/setup.php`

This creates the default admin: **admin / admin123**

> ⚠️ **Delete `setup.php` immediately after running it.**

### 5. Start Using the App
| URL | Description |
|-----|-------------|
| `http://localhost/student_course_hub/` | Student-facing homepage |
| `http://localhost/student_course_hub/programmes.php` | All programmes |
| `http://localhost/student_course_hub/admin/login.php` | Admin login |
| `http://localhost/student_course_hub/admin/dashboard.php` | Admin dashboard |

---

## Features

### Student-Facing
- Browse all published programmes with level filter (UG/PG)
- Keyword search across programme names and descriptions
- Programme detail page with modules organised by year
- Visual tabs for Year 1 / Year 2 / Year 3
- "Shared module" badge when a module appears in multiple programmes
- Staff information on each module (leader name + initials avatar)
- Register interest form with duplicate-email prevention
- Fully accessible: skip link, ARIA roles, keyboard navigation, WCAG2 compliant

### Admin Panel
- Secure login with session-based authentication
- Dashboard with live stats and recent interest registrations
- **Programmes:** Create, Edit, Delete, Publish/Unpublish, Manage module assignments
- **Modules:** Create, Edit, Delete, assign module leaders
- **Staff:** Create, Edit, Delete with title/bio/department fields
- **Mailing Lists:** View all registrations, filter by programme, delete invalid entries, export to CSV

### Database Improvements (from schema brief)
| Feature | Implementation |
|---------|---------------|
| Publish/unpublish | `Published` column on `Programmes` |
| Duplicate sign-ups | `UNIQUE KEY (ProgrammeID, Email)` on `InterestedStudents` |
| Student opt-out | `Active` column on `InterestedStudents` |
| Richer staff profiles | `Title`, `Bio`, `Department` on `Staff` |
| Image accessibility | `ImageAlt` columns on `Programmes` and `Modules` |
| Admin authentication | `Admins` table with `PasswordHash` |
| Role-based access | `Role ENUM('super_admin','editor','viewer')` on `Admins` |
| Search performance | Indexes on level, published, programme-module joins, email |

---

## Security
- All user input sanitised with `htmlspecialchars()` before output (XSS prevention)
- All database queries use PDO prepared statements (SQL injection prevention)
- Admin passwords stored as bcrypt hashes (`password_hash` / `password_verify`)
- Session regeneration on login (`session_regenerate_id`)
- Admin area fully protected — all pages call `requireAdmin()` before rendering

---

## Changing Admin Password
1. Run `setup.php` again (it will update the existing admin password to `admin123`)
2. Or go to Admin → and edit the account, or connect to MySQL directly:
```sql
UPDATE Admins SET PasswordHash = '<new_bcrypt_hash>' WHERE Username = 'admin';
```
Generate a hash with PHP: `echo password_hash('newpassword', PASSWORD_BCRYPT);`
