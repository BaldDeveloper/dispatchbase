
---

## 📁 `docs/auth-flow.md`

```markdown
# DispatchBase Authentication Flow

## 🔐 Technologies Used
- **bcryptjs**: Password hashing
- **express-session**: Session management
- **dotenv**: Secret management

## 🧭 Login Flow
1. User submits credentials via `/auth/login`
2. Backend queries MySQL for matching user
3. bcrypt compares password hash
4. If valid, session is created:
   ```js
   req.session.user = { id, username }
