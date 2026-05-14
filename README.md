# Full-Stack Web Portfolio

A professional, dynamic full-stack web portfolio designed to showcase projects, skills, and handle inquiries. This project demonstrates proficiency in standard web technologies including HTML5, CSS3, JavaScript, PHP, and MySQL.

## 🚀 Features & Technical Requirements

### 1. Semantic HTML & Advanced CSS
- Built with semantic **HTML5** tags, tables, and forms.
- Fully responsive design utilizing the **CSS Box Model, Flexbox/Grid**.
- External, organized CSS for consistent formatting, branding, and layouts.

### 2. Client-Side Interactivity (JavaScript/DOM)
- **Dynamic UI Elements:** Includes dark mode toggling / interactive menus.
- **Form Validation:** Client-side JavaScript validation for contact forms before submission.
- **DOM Manipulation:** Event-driven updates without page reloads.

### 3. Server-Side Logic & Database (PHP/MySQL)
- **Contact Management:** A functional "Contact Me" section that securely records incoming messages into a MySQL database.
- **Dynamic Content:** Fetches and displays portfolio projects/blog posts directly from the database.
- **AJAX Integration:** Asynchronous data fetching (via Fetch API / XMLHttpRequest) to ensure seamless reading without page refreshes.

### 4. State Management & Persistence
- **Admin Dashboard:** A secure administrative panel for adding and editing projects.
- **Sessions & Cookies:** Utilizes PHP sessions and cookies to handle secure admin login and state management.

## 📁 Project Structure

```text
/
├── index.php             # Main portfolio entry point
├── admin.php             # Secure administrative dashboard
├── /assets/
│   ├── /css/style.css    # Global stylesheets
│   ├── /js/script.js     # Global JS & AJAX calls
│   └── /img/             # Image resources
├── /api/                 # PHP endpoints for AJAX requests
└── portfolio.sql         # SQL Database schema export
```

## 🛠️ Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone <your-github-repo-url>
   ```
2. **Environment Setup:** Make sure you have a local server environment like XAMPP, MAMP, or WAMP installed.
3. **Database setup:** 
   - Open phpMyAdmin.
   - Create a new database (e.g., `portfolio_db`).
   - Import the included `portfolio.sql` file.
4. **Configuration:** Update the database connection credentials in your PHP scripts to match your local setup (`root`, empty password for XAMPP by default).
5. **Run the app:** Move the project folder into your server's root directory (`htdocs` for XAMPP) and access it via `http://localhost/walid-portfolio/`.
# walid-portfolio
