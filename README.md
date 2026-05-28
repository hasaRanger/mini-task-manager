# TMS | Task Management System

A lightweight PHP + MySQL task manager built for XAMPP. It supports creating, editing, completing, deleting, searching, and filtering tasks by priority, with a Bootstrap-based UI and CSRF-protected form submissions.

## Features

- Create new tasks with title, description, and priority
- Edit existing tasks in a Bootstrap modal
- Mark tasks as completed
- Delete tasks
- Search tasks by title
- Filter tasks by priority
- CSRF protection for write operations
- PDO database access with emulated prepares disabled
- Responsive UI with Bootstrap 5
- Simple dark/light theme support

## Tech Stack

- **Backend:** PHP 8+ with PDO
- **Database:** MySQL / MariaDB
- **Frontend:** HTML, CSS, JavaScript
- **UI Framework:** Bootstrap 5.3
- **Local Dev Server:** XAMPP (Apache + MySQL)

## Project Structure

```text
mini task manager/
├── actions/
│   ├── add-task.php
│   ├── delete-task.php
│   ├── edit-task.php
│   └── update-task.php
├── css/
│   └── style.css
├── includes/
│   ├── db.php
│   └── functions.php
├── js/
│   ├── app.js
│   └── theme.js
├── database.sql
├── header.html
├── index.php
└── README.md
```

## Requirements

- XAMPP installed on Windows
- Apache enabled in XAMPP Control Panel
- MySQL enabled in XAMPP Control Panel
- PHP 8 or newer recommended
- Browser with JavaScript enabled

## Setup Instructions

### 1. Copy the project into `htdocs`

Place the project folder inside your XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\mini task manager
```

### 2. Start XAMPP services

Start the following services in XAMPP Control Panel:

- Apache
- MySQL

### 3. Create the database and tables

Import `database.sql` into phpMyAdmin or run it manually.

Database name:

- `intern_task_system`

You can import it using phpMyAdmin:

1. Open `http://localhost/phpmyadmin`
2. Create or select the `intern_task_system` database
3. Use the Import tab and select `database.sql`

### 4. Check database credentials

Database settings are in `includes/db.php`.

Default local XAMPP values:

- Host: `localhost`
- Username: `root`
- Password: empty
- Database: `intern_task_system`

If your XAMPP setup uses different credentials, update `includes/db.php` accordingly.

### 5. Open the application

Open the app in your browser:

```text
http://localhost/mini%20task%20manager/
```

If you rename the folder, update the URL to match the new folder name.

## How It Works

- `index.php` loads the task list and renders the UI.
- `includes/db.php` creates the PDO connection and initializes the session and CSRF token.
- `includes/functions.php` contains reusable database helpers.
- `js/app.js` handles search, priority filtering, edit modal population, and async task actions.
- `js/theme.js` toggles light/dark mode and saves the selected theme in `localStorage`.
- The `actions/` scripts handle form submissions and task operations.

## Action Endpoints / API

This project does not expose a public REST API. Instead, it uses internal PHP action endpoints.

### `POST actions/add-task.php`

Creates a new task.

**Form fields**
- `csrf` - CSRF token
- `title` - Task title
- `description` - Optional task description
- `priority` - `Low`, `Medium`, or `High`

**Response**
- Redirects back to `index.php` with `?success=1` or `?error=1`

### `POST actions/edit-task.php`

Updates an existing task.

**Form fields**
- `csrf` - CSRF token
- `id` - Task ID
- `title` - Updated task title
- `description` - Updated description
- `priority` - Updated priority

**Response**
- `OK` on success
- `400 Invalid request` if CSRF or ID is invalid
- `422 Validation failed` if data is invalid
- `500 Failed` on database error

### `POST actions/update-task.php`

Marks a task as completed.

**Form fields**
- `csrf` - CSRF token
- `id` - Task ID

**Response**
- `OK` on success
- `400 Invalid request` if CSRF or ID is invalid
- `405 Method Not Allowed` if not POST
- `500 Failed` on database error

### `POST actions/delete-task.php`

Deletes a task.

**Form fields**
- `csrf` - CSRF token
- `id` - Task ID

**Response**
- `OK` on success
- `400 Invalid request` if CSRF or ID is invalid
- `405 Method Not Allowed` if not POST
- `500 Failed` on database error

## Database Schema

### `tasks`

| Column | Type | Notes |
| --- | --- | --- |
| `id` | INT AUTO_INCREMENT PRIMARY KEY | Unique task identifier |
| `title` | VARCHAR(255) NOT NULL | Task title |
| `description` | TEXT | Optional description |
| `priority` | ENUM('Low', 'Medium', 'High') NOT NULL | Task priority |
| `status` | ENUM('Pending', 'Completed') DEFAULT 'Pending' | Task completion status |
| `created_at` | TIMESTAMP DEFAULT CURRENT_TIMESTAMP | Creation time |

## UI Features

- Search box filters tasks by title in real time
- Priority dropdown filters tasks by selected priority
- Edit modal lets you update task details without leaving the page
- Theme toggle switches between light and dark themes

## Security Notes

- Uses PDO prepared statements for database operations
- Disables PDO emulated prepares
- Adds CSRF token validation for state-changing requests
- Escapes output with `htmlspecialchars()` for task fields

## Troubleshooting

### Database connection error

Check the values in `includes/db.php` and make sure MySQL is running in XAMPP.

### No tasks appear

Make sure `database.sql` has been imported and the `tasks` table exists in `intern_task_system`.

### Edit or delete does nothing

Check browser devtools Console and Network tabs. Confirm that JavaScript is loaded and that the request includes the CSRF token.

### Styles do not load

Verify the app is served from the correct folder under `htdocs` and that `css/style.css` exists.

## Future Improvements

- Add pagination
- Add task categories/tags
- Add due dates and reminders
- Add user authentication
- Convert internal actions into a proper REST API

## License

No license has been specified for this project.
