# DispatchBase

## Overview
DispatchBase is a web-based mortuary dispatch management system for coroners, funeral homes, and transport firms. It streamlines the process of tracking decedents, transports, pouches, and charges, with robust user management and audit logging.

## Features
- Customer, coroner, decedent, and transport management
- Pouch and transport charge tracking
- User authentication and role-based access (Admin, Coroner, Firm, Driver)
- Secure authentication and session management
- Live phone/email formatting and validation
- US state dropdowns with validation
- Data tables for easy record browsing
- CSRF protection for forms
- Error logging for database operations
- Audit logging for all changes
- Responsive, mobile-friendly UI
- Modular navigation and footer (dynamically loaded)

## Tech Stack
- **Frontend:** HTML, CSS, JavaScript, Bootstrap
- **Backend:** PHP (business logic, database access), Node.js (static file server for development)
- **Database:** MySQL (via PHP PDO)
- **Other Tools:** Node.js (for dev server), npm

## Directory Structure
- `/public/`: All files directly accessible by the web server (HTML, JS, CSS, entry-point PHP files)
- `/database/`: PHP files for database access and business logic (never accessible directly)
- `/includes/`: Shared PHP includes (validation, config, etc.)
- `/services/`: Service layer for business logic
- `/docs/`: Project documentation

## Database Schema (Key Tables)
- **customer:** Stores company info, phone, email, address, state, zip
- **coroner:** Coroner name, contact info, address, state, zip
- **decedent:** First/last name, ethnicity, gender, transport linkage
- **transport:** Firm, date, locations, coroner, pouch, times, transporters
- **users:** Name, email, role, phone, password
- **pouch:** Pouch types
- **location:** Location names and addresses

## Validation & Security
- All server-side validation logic is centralized in [`includes/validation.php`](includes/validation.php).
- Backend PHP files are kept outside the public directory for security.
- Navigation and footer are modular and loaded dynamically into each page.
- CSRF protection and input validation are enforced.
- Passwords are hashed using bcrypt.
- Output escaping (e.g., `htmlspecialchars`) is used for all user-facing data.

## Setup Instructions

### Prerequisites
- PHP installed (`php -v`)
- Node.js and npm installed (`node -v`, `npm -v`)
- MySQL server

### Running Locally
1. Install dependencies:
   ```cmd
   npm install
   ```
2. Start the static server:
   ```cmd
   node server.js
   ```
   - Opens at [http://localhost:3000/index.html](http://localhost:3000/index.html)
   - Serves files from the `public` directory

3. Ensure your PHP backend is accessible and configured (e.g., via Apache, Nginx, or PHP built-in server).

4. To stop the server, press `Ctrl+C` in the terminal.

## Documentation
See the [`docs/`](docs/) folder for architecture and setup details.

## License
Copyright (c) 2025 BaldDeveloper
All rights reserved.
