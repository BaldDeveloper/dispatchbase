# DispatchBase Setup Guide

## 🚀 Prerequisites
- Node.js (v18+ recommended)
- PHP (v8+ recommended)
  - Required PHP extensions: `mysqli`, `pdo_mysql`, `mbstring`, `json`, `curl` (install via your package manager or enable in `php.ini`)
- MySQL server running locally or remotely
- Web server (Apache, Nginx, or use PHP built-in server for development)
- WebStorm or preferred IDE

## 📦 Installation Steps
1. Clone the repo:
   ```bash
   git clone https://github.com/your-username/DispatchBase.git
   cd DispatchBase
   ```
2. Install Node.js dependencies:
   ```bash
   npm install
   ```
3. Set up the MySQL database:
   - Create a new MySQL database (e.g., `dispatchbase`).
   - Import the database schema and initial data (if provided) using a tool like phpMyAdmin or the MySQL CLI:
     ```bash
     mysql -u youruser -p dispatchbase < path/to/schema.sql
     ```
   - If a schema file is not present, create tables as described in the documentation or contact the project maintainer.
4. Configure PHP backend:
   - Copy `includes/config.php.example` to `includes/config.php` (if present).
   - Edit `includes/config.php` to set your database credentials and any other required settings.

## 🖥️ Frontend Structure
- All static assets (HTML, CSS, JS, images) are in the `public` directory.
- Navigation (sidebar), top navigation bar, and footer are modular and stored as separate HTML files (`sidebar.html`, `topnav.html`, `footer.html`).
- These components are loaded dynamically into each page using JavaScript, so you will not see their markup in every HTML file.
- Custom CSS utilities (e.g., `mt-n-custom-6`) are used for advanced layout control.
- Serve the `public` directory as your static root for development and production.

## ⚡ Running the App
- Start the Node.js server:
  ```bash
  node server.js
  ```
- Start the PHP backend (for development, from the project root):
  ```bash
  php -S localhost:8000 -t public
  ```
  Or configure Apache/Nginx to serve the `public` directory and process PHP files for production.
- Ensure both the Node.js and PHP servers are running for full functionality.
- Access the app at `http://localhost:PORT` (Node.js default port is set in your server.js, PHP default is 8000).

## 🧠 Notes
- Update `public/sidebar.html`, `public/topnav.html`, or `public/footer.html` to change navigation or footer site-wide.
- For custom layout tweaks, edit `public/styles.css`.
- For backend/API changes, update PHP files in `/database/`, `/services/`, `/includes/`, or `server.js` as needed.
