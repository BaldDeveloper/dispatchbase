# DispatchBase

## Overview
DispatchBase is a web application for managing transport services, including customer, coroner, decedent, and transport records. It streamlines the workflow for dispatchers and transport firms, providing a centralized platform for data entry, tracking, and reporting.

## Features
- Customer, coroner, decedent, and transport management
- User authentication and role-based access
- Live phone/email formatting and validation
- US state dropdowns with validation
- Data tables for easy record browsing
- CSRF protection for forms
- Error logging for database operations

## Tech Stack
- **Languages:** PHP, JavaScript, HTML, CSS
- **Frameworks/Libraries:** None (custom PHP, vanilla JS)
- **Database:** MySQL (via PHP PDO)
- **Other Tools:** Node.js (for dev server), npm

## Database Schema (Key Tables)
- **customer:** Stores company info, phone, email, address, state, zip
- **coroner:** Coroner name, contact info, address, state, zip
- **decedent:** First/last name, ethnicity, gender, transport linkage
- **transport:** Firm, date, locations, coroner, pouch, times, transporters
- **users:** Name, email, role, phone, password
- **pouch:** Pouch types
- **location:** Location names and addresses

## Setup Instructions
### Prerequisites
- PHP installed (`php -v`)
- Node.js and npm installed (`node -v`, `npm -v`)

### Running Locally
1. Open a terminal and navigate to the project root:
   ```
   cd "C:\Users\baldd\OneDrive\Documents\Dispatch Base\Web Application\Repositories\dispatchbase"
   ```
2. Start the server:
   ```
   npm start
   ```
   - Opens at [http://localhost:8000/index.html](http://localhost:8000/index.html)
   - Serves files from the `public` directory

3. To stop the server, press `Ctrl+C` in the terminal.

### Notes
- Ensure your `.env` file is in the project root for DB connectivity.
- For database errors, check `public/database/db_error.log`.

## Security Notes
- CSRF protection is enabled for all forms.
- Input validation for phone, email, and US state fields matches the customer page across the app.
- Server-side validation for all critical fields.
- Passwords are stored securely (ensure best practices in production).

## Future Plans
- Enhanced reporting and analytics
- Improved user management and permissions
- REST API for integrations
- Mobile-friendly UI improvements
- Audit logging and activity tracking

---

For more details, see the documentation in the `docs/` folder.
