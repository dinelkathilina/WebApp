# Todo-App PHP Application

A simple Todo application built with PHP and MySQL that includes user registration and login functionality.

## Features

- User registration with username, email, and password
- Secure password hashing using `password_hash()`
- User login with credential validation using `password_verify()`
- Session-based authentication
- Todo management (add, view, mark complete/pending, delete)
- Responsive design with basic CSS styling
- Error handling for invalid inputs and duplicate data

## Prerequisites

- XAMPP (or any web server with PHP and MySQL support)
- Web browser

## Setup Instructions

### 1. Install XAMPP

If you haven't already, download and install XAMPP from the official website: https://www.apachefriends.org/

### 2. Start XAMPP Services

1. Open the XAMPP Control Panel
2. Start the Apache web server
3. Start the MySQL database server

### 3. Set Up the Database

1. Open your web browser and go to `http://localhost/phpmyadmin/`
2. Click on "New" in the left sidebar to create a new database
3. Enter `todo_app` as the database name and click "Create"
4. Click on the `todo_app` database in the left sidebar
5. Click on the "Import" tab at the top
6. Click "Choose File" and select the `database/schema.sql` file from your project
7. Click "Go" to import the database schema

Alternatively, you can run the SQL commands manually:
- Open phpMyAdmin
- Select the `todo_app` database
- Go to the "SQL" tab
- Copy and paste the contents of `database/schema.sql`
- Click "Go"

### 4. Deploy the Application

1. Copy the entire `WebApp` folder to your XAMPP htdocs directory:
   - On Windows: `C:\xampp\htdocs\WebApp`
   - On macOS: `/Applications/XAMPP/htdocs/WebApp`
   - On Linux: `/opt/lampp/htdocs/WebApp`

2. Make sure the folder structure looks like this:
   ```
   htdocs/WebApp/
   ├── assets/
   │   ├── css/
   │   │   └── style.css
   │   └── js/
   ├── database/
   │   └── schema.sql
   ├── includes/
   │   ├── config.php
   │   └── functions.php
   ├── pages/
   │   ├── dashboard.php
   │   ├── login.php
   │   ├── logout.php
   │   └── register.php
   └── index.php (we'll create this)
   ```

### 5. Configure Database Connection (Optional)

If your MySQL credentials are different from the defaults, edit `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username'); // Default is 'root'
define('DB_PASS', 'your_password'); // Default is empty ''
define('DB_NAME', 'todo_app');
```

### 6. Access the Application

1. Open your web browser
2. Go to `http://localhost/WebApp/pages/register.php` to register a new user
3. Or go to `http://localhost/WebApp/pages/login.php` to login
4. After logging in, you'll be redirected to the dashboard where you can manage your todos

## File Structure

```
WebApp/
├── assets/           # Static assets
│   ├── css/
│   │   └── style.css # Main stylesheet
│   └── js/           # JavaScript files (if needed)
├── database/
│   └── schema.sql    # Database schema and initial data
├── includes/         # PHP include files
│   ├── config.php    # Database configuration
│   └── functions.php # Utility functions
├── pages/            # Main application pages
│   ├── dashboard.php # User dashboard with todo management
│   ├── login.php     # User login page
│   ├── logout.php    # Logout script
│   └── register.php  # User registration page
└── README.md         # This file
```

## Usage

### Registration
1. Navigate to the registration page
2. Fill in username, email, and password
3. Click "Register"
4. You'll be redirected to login after successful registration

### Login
1. Navigate to the login page
2. Enter your email and password
3. Click "Login"
4. You'll be redirected to your dashboard

### Managing Todos
1. On the dashboard, use the form to add new todos
2. View your todos in the list below
3. Mark todos as complete or pending
4. Delete todos using the delete button

## Security Features

- Passwords are hashed using PHP's `password_hash()` function
- Prepared statements are used to prevent SQL injection
- Input sanitization and validation
- Session-based authentication
- CSRF protection through proper form handling

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Make sure MySQL is running in XAMPP
   - Check your database credentials in `config.php`
   - Ensure the `todo_app` database exists

2. **Page Not Found Errors**
   - Make sure files are in the correct directory structure
   - Check that Apache is running
   - Verify the URL is correct (case-sensitive)

3. **Permission Errors**
   - Make sure XAMPP has proper permissions to access the files
   - On Windows, run XAMPP as administrator if needed

### Getting Help

If you encounter issues:
1. Check the XAMPP control panel for error messages
2. Look at the Apache error logs in XAMPP
3. Verify your PHP version is 7.0 or higher
4. Ensure MySQL is properly configured

## Development

To modify the application:
- Edit PHP files in the `pages/` and `includes/` directories
- Modify styles in `assets/css/style.css`
- Update database schema in `database/schema.sql`

## License

This project is for educational purposes. Feel free to modify and use as needed.
