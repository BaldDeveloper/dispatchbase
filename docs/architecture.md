# DispatchBase Architecture Overview

## 🧱 Stack Summary
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: Node.js + Express
- **Database**: MySQL
- **Authentication**: bcryptjs + express-session
- **Environment Management**: dotenv

## 🧭 Layered Architecture

| Layer       | Technology         | Purpose                          |
|-------------|--------------------|----------------------------------|
| Frontend    | HTML/CSS/JS        | UI and client-side logic         |
| Backend     | Node.js + Express  | API routing and business logic   |
| Database    | MySQL              | Persistent data storage          |
| Auth        | bcryptjs + session | Secure login and session control |

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
