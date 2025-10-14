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

## 🛡️ Privilege Boundaries & Access Control
- All sensitive backend operations (add, edit, delete) are protected by authentication and role-based access control (RBAC).
- User roles and permissions are enforced in backend service layers (PHP/Node.js). Only authorized users can access or modify protected resources.
- Privilege checks are performed before executing any business logic or database operation. Unauthorized access attempts are logged and denied.
- Ensure that any new endpoints or features include explicit privilege checks and are documented here.

## 📝 Auditability & Logging
- All critical actions are logged for traceability and compliance. This includes:
  - User logins and logouts
  - Data creation, updates, and deletions (CRUD operations)
  - Privilege escalations or permission changes
  - Failed authentication or authorization attempts
- Logs are stored in:
  - Log files (e.g., `database/decedent-errors.log` for PHP errors and sensitive actions)
  - Database tables (if implemented, e.g., `audit_log` for structured event tracking)
- Error logging:
  - All backend errors are logged with timestamps, user IDs (if available), and relevant context.
  - PHP errors are typically logged to `decedent-errors.log` or the default PHP error log.
  - Node.js errors are logged using a logger (e.g., `winston`) to a file or external service.
- Reviewing audit trails:
  - Log files can be accessed by administrators with server access.
  - Database audit logs can be queried by privileged users or via admin interfaces.
- Extending audit logging:
  - When adding new features or endpoints, ensure all sensitive actions are logged with sufficient detail (who, what, when, where).
  - Follow the existing logging pattern and update this documentation as needed.
- Access to audit logs should be restricted to authorized administrators only, to protect sensitive information.

## 📄 Documentation Maintenance
- This architecture document should be updated whenever the codebase or privilege model changes.
- For major changes, consider adding or updating diagrams (sequence, flow, or component diagrams) to visually represent data flow and privilege boundaries.

## 🗺️ System Architecture Diagram

Below is a high-level diagram showing the main components, privilege boundaries, and data flow in DispatchBase:

```mermaid
graph TD
    subgraph Client
        A[Browser (HTML/JS/CSS)]
    end
    subgraph Public
        B[public/ (Static Assets, Entry PHP)]
    end
    subgraph Backend
        C[Node.js/Express (API, Auth)]
        D[PHP Services (services/, includes/)]
        E[Database Access (database/)]
    end
    F[(MySQL Database)]

    A -- HTTP/HTTPS --> B
    B -- API/Data Requests --> C
    B -- Form Submits --> D
    C -- Auth/API Calls --> D
    D -- Queries --> E
    E -- SQL --> F
    C -- Session/Auth --> C
    D -- Session/Auth --> D

    classDef privBoundary fill:#f9f,stroke:#333,stroke-width:2px;
    class C,D privBoundary;

    %% Privilege boundary annotation
    classDef boundaryLine stroke-dasharray: 5 5;
    C -. Enforces RBAC .-> D
```

- **Purple boxes** indicate layers where privilege boundaries and access control are enforced.
- Data flows from the browser to the backend via both Node.js and PHP, with authentication and authorization checks at each backend layer.
- All sensitive operations and data access are protected by RBAC and audit logging.

## 🧠 Notes
- Modular folder structure for scalability
- Future-proofing for JWT or Redis session store
- Compatible with Docker and CI/CD pipelines
- Frontend components and layout are now fully modular and maintainable
