# DispatchBase Setup Guide

## 🚀 Prerequisites
- Node.js (v18+ recommended)
- MySQL server running locally or remotely
- WebStorm or preferred IDE

## 📦 Installation Steps
1. Clone the repo:
   ```bash
   git clone https://github.com/your-username/DispatchBase.git
   cd DispatchBase
   ```

## 🖥️ Frontend Structure
- All static assets (HTML, CSS, JS, images) are in the `public` directory.
- Navigation (sidebar), top navigation bar, and footer are modular and stored as separate HTML files (`sidebar.html`, `topnav.html`, `footer.html`).
- These components are loaded dynamically into each page using JavaScript, so you will not see their markup in every HTML file.
- Custom CSS utilities (e.g., `mt-n-custom-6`) are used for advanced layout control.
- Serve the `public` directory as your static root for development and production.

## ⚡ Running the App
- Install dependencies:
  ```bash
  npm install
  ```
- Start the server:
  ```bash
  node server.js
  ```
- Access the app at `http://localhost:PORT` (default port is set in your server.js)

## 🧠 Notes
- Update `public/sidebar.html`, `public/topnav.html`, or `public/footer.html` to change navigation or footer site-wide.
- For custom layout tweaks, edit `public/styles.css`.
- For backend/API changes, update `server.js` and related files.
