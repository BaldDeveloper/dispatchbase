# DispatchBase Architecture Overview

## 🧱 Stack Summary
- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Backend**: Node.js + Express
- **Database**: MySQL
- **Authentication**: bcryptjs + express-session
- **Environment Management**: dotenv

## 🧭 Layered Architecture

| Layer       | Technology         | Purpose                          |
|-------------|--------------------|----------------------------------|
| Frontend    | HTML/CSS/JS/Bootstrap | UI, client-side logic, modular layout |
| Backend     | Node.js + Express  | API routing and business logic   |
| Database    | MySQL              | Persistent data storage          |
| Auth        | bcryptjs + session | Secure login and session control |

## 🖥️ Frontend Modularization
- **Navigation and Footer**: Sidebar, top navigation bar, and footer are split into separate HTML files (sidebar.html, topnav.html, footer.html).
- **Dynamic Loading**: These components are loaded into each page using JavaScript fetch, ensuring updates are reflected site-wide.
- **Shared Layout**: All pages use a consistent Bootstrap-based layout, with shared containers and utility classes for spacing and overlap.
- **Custom Utilities**: Custom CSS classes (e.g., mt-n-custom-6) extend Bootstrap for advanced layout control.
- **Asset Management**: Static assets (images, CSS, JS) are organized under the public directory. External libraries are loaded via CDN or can be referenced locally.

## 🔄 Data Flow
1. User submits login form via frontend
2. Request sent to `/auth/login` route
3. Backend validates credentials and starts session
4. Session data stored in memory or external store
5. Authenticated user gains access to protected routes

## 🧠 Notes
- Modular folder structure for scalability
- Future-proofing for JWT or Redis session store
- Compatible with Docker and CI/CD pipelines
- Frontend components and layout are now fully modular and maintainable
