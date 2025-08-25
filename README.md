<<<<<<< HEAD
# Blog Management System (PHP, MySQL)

A clean, lightweight blog application with:
- User authentication (register/login/logout)
- Admin-managed posts (create, edit, delete)
- Categories and category-wise sorting
- Commenting system with moderation
- Image upload for posts
- Secure password hashing & basic validation
- Responsive UI (vanilla CSS)
- Ready to run on XAMPP/WAMP/MAMP

## 1) Quick Start (XAMPP)
1. Copy the folder `blog-management-php` into `htdocs` (e.g., `C:\xampp\htdocs\blog-management-php`).
2. Start **Apache** and **MySQL** from XAMPP Control Panel.
3. Open **phpMyAdmin** → create a database named **`blog_db`** (utf8mb4).
4. Import `sql/schema.sql` into `blog_db`.  
   This seeds an admin account: **admin@example.com / admin123**
5. If your DB user/password differ, update them in `config.php`.
6. Visit: `http://localhost/blog-management-php/public/`

## 2) Project Structure
```
blog-management-php/
├── admin/
│   ├── index.php            # Admin dashboard
│   ├── posts.php            # List & manage posts
│   ├── post-create.php      # Create post (with image upload)
│   ├── post-edit.php        # Edit post
│   ├── post-delete.php      # Delete post
│   ├── categories.php       # Manage categories
│   └── comments.php         # Moderate comments
├── includes/
│   ├── admin_header.php     # Common admin header
│   ├── public_header.php    # Common public header
│   └── footer.php           # Footer
├── public/
│   ├── index.php            # Homepage: search/filter + latest posts
│   ├── post.php             # Single post + comments
│   ├── login.php            # User login
│   ├── register.php         # User registration
│   ├── logout.php           # End session
│   └── assets/
│       ├── style.css        # Minimal responsive styling
│       └── uploads/         # Post images (make writable)
├── sql/
│   └── schema.sql           # DB schema + admin seed
└── config.php               # PDO connection + helpers
```

## 3) Features Map
- **Authentication:** `public/register.php`, `public/login.php` (hashing with `password_hash` & `password_verify`).
- **Posts CRUD:** `admin/post-create.php`, `admin/post-edit.php`, `admin/post-delete.php`, listed in `admin/posts.php`.
- **Categories:** create/delete via `admin/categories.php`; filter on homepage.
- **Comments:** add on `public/post.php` (logged-in users), moderate in `admin/comments.php`.
- **Image Upload:** stored under `public/assets/uploads/` with basic file-type validation.

## 4) Security & Notes
- All DB calls use **prepared statements**.
- Passwords are **hashed** using PHP's native `password_hash`.
- For production, add CSRF tokens and stricter validation, and serve over HTTPS.
- Ensure `public/assets/uploads` is writable by the web server user.

## 5) Default Admin
- Email: `admin@example.com`
- Password: `admin123`

## 6) License
MIT — do whatever you like, just don't hold me liable. 🙂
=======
# the-Blog-Management-System-PHP-MySQL-
>>>>>>> c73de848bc5df1eb61ed0e87f02fb1f70459c1ca
