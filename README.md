# Full-Stack Web Portfolio

A professional, dynamic full-stack web portfolio designed to showcase projects, skills, and handle administrative inquiries. This project comprehensively demonstrates proficiency in standard web development technologies including HTML5, CSS3, JavaScript, PHP, and MySQL.

---

## 🛠️ Architecture & Technologies Used

This project is built from scratch without any heavy frontend frameworks (like React) or backend MVC frameworks (like Laravel), strictly adhering to native full-stack constraints.

### 1. Semantic HTML & Advanced CSS (Frontend UI)
- **HTML5:** Semantic architecture utilizing standard tags (`<section>`, `<header>`, `<footer>`, `<nav>`, `<aside>`) to ensure accessibility and great SEO.
- **CSS3:** Custom stylesheet (`assets/css/style.css`) built fully responsive utilizing the **CSS Box Model, Flexbox**, and **CSS Grid**. 
- **CSS Variables:** Theming uses CSS variables allowing a smooth mapping.

### 2. Client-Side Interactivity (JavaScript)
- **Dark Mode Toggle:** A custom UI interaction built with DOM manipulation that persists the state using LocalStorage.
- **Form Validation:** The "Contact Me" form dynamically checks inputs right in the browser enforcing Regex email constraints and input length limits without needing to spam the server.
- **AJAX (Fetch API):** The project gallery is dynamically fetched via `api/get_projects.php` and rendered dynamically as DOM nodes completely asynchronously without reloading the page. Form submissions (`api/submit_contact.php`) also work seamlessly through `fetch()`.

### 3. Server-Side Logic (PHP)
- **RESTful Endpoints:** The backend hosts endpoint processors connecting natively to PDO MySQL to return JSON resources. Check `api/` directory.
- **File Uploads System:** Through the Admin Control Panel, Admins can upload their project screenshots directly via HTTP Multi-Part Form Data into a secure `assets/img/uploads/` persistent storage directory on the server.

### 4. Database & State Management (MySQL & Sessions)
- **MySQL Database:** Fully relational configuration to capture dynamically registered `projects`, secure inbound `messages` from the front-end application, and robust user schema handling authenticated admins directly.
- **Persisted State:** Leveraging PHP `$_SESSION[]` state handling routing constraints making sure un-authorized users cannot hit `admin.php`. Cookies (`setcookie()`) manage returning administrative patterns.

---

## 📁 Project Structure

```text
/
├── index.php             # Main portfolio entry point / Frontend Gallery
├── login.php             # Secure Administrator login splash page
├── admin.php             # Secure Dashboard (CMS, Manage projects, etc.)
├── /assets/
│   ├── /css/style.css    # Global responsive stylesheet & Themings
│   ├── /js/script.js     # AJAX / Form validations / Theme toggle
│   └── /img/
│       └── /uploads/     # Storage directory where uploaded screenshots reside
├── /api/                 
│   ├── db.php            # Core PDO connection logic 
│   ├── get_projects.php  # JSON resource server reading `projects` table
│   └── submit_contact.php# Secure insert engine storing `messages` table
└── portfolio.sql         # Local DB export schema to reconstruct architecture
```

---

## 🚀 Installation & Setup Guide

### 1. Requirements
To execute PHP scripts and build out the MySQL database, you need a local web server cluster containing PHP 8.x and MariaDB/MySQL. 
- **Windows:** Download & Install [XAMPP](https://www.apachefriends.org/)
- **Mac:** Download & Install [MAMP](https://www.mamp.info/) 

### 2. Move Project to Server Directory (Crucial)
You must execute this inside your web root.
- **XAMPP users:** Move the `walid-portfolio` folder into `C:\xampp\htdocs\`
- **MAMP users:** Move the `walid-portfolio` folder into `/Applications/MAMP/htdocs/`
*(Ensure the application path is ultimately: `htdocs/walid-portfolio/`)*

### 3. Boot Software Modules
Open the XAMPP/MAMP Control Panel interface and explicitly **START** the following two modules:
1. **Apache** (Handles HTTP processing)
2. **MySQL** (Handles the Database)

### 4. Database Setup & Configuration
The project includes a `portfolio.sql` migration file doing the heavy lifting.
1. Open your web browser and navigate to: **http://localhost/phpmyadmin**
2. In the top navigation, click on the **Databases** tab.
3. Under the "Create database" input field, type **`portfolio_db`**, leave format defaulted to `utf8mb4_general_ci`, and hit **Create**.
4. With the newly created `portfolio_db` open on the left sidebar pane, click the **Import** tab on the top menu.
5. Under "File to import", click **Choose File** and locate the `portfolio.sql` inside your `walid-portfolio` project folder.
6. Scroll down and hit **Import/GO**.
*(Database tables `admin_users`, `messages`, and `projects` are now successfully populated!)*

### 5. Running the Application
Check if everything connected appropriately. Open your internet browser and visit:
👉 **http://localhost/walid-portfolio/**
*At this point your main portfolio should correctly appear with dummy project data, form validation should work, and the dark mode switch functional!*

---

## 🔒 Administration Access & System

The application features a fully encapsulated CMS (Content Management System) designed only to be accessed by authorized owners.

**Admin Panel URL:** `http://localhost/walid-portfolio/login.php`

### 🔑 Verified Administrator Credentials
- **Username:** `admin`
- **Password:** `password123`

*(These are hard-migrated upon your SQL initialization utilizing rigorous PHP `password_hash()` methods guaranteeing plaintext is never physically saved for the admin database row).*

### Included Features inside the Admin Dashboard:
- ✅ **View Contact Messages:** Read realtime queries asynchronously sent from the Front End Form.
- ✅ **Storage Drive / Project Uploader:** Submitting new project ideas within `admin.php` features an `<input type="file" enctype="multipart/form-data">`. The PHP engine will securely write the `image_file` physically onto your device into `assets/img/uploads/` attaching the relative path back to your database entry!
- ✅ **Logout Engine:** Destroys `$session` data and actively locks down route mapping forcing the admin state to close natively out via PHP logic.
