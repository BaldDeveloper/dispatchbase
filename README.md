# DispatchBase

DispatchBase is a web application for managing transport and dispatch services. It provides a modern, modular interface for users to view and interact with dispatch operations.

## Features
- Modular navigation and footer (sidebar, topnav, footer) loaded dynamically via JavaScript
- Shared Bootstrap-based layout and custom CSS utilities for advanced spacing and overlap
- Serve static web content from the public directory
- Simple Express.js backend
- Easy setup and deployment

## Getting Started

### Prerequisites
- Node.js (v14 or higher recommended)
- npm (comes with Node.js)

### Installation
1. Clone the repository:
   ```bash
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```bash
   cd dispatchbase
   ```
3. Install dependencies:
   ```bash
   npm install
   ```

### Running the Application
Start the server with:
```bash
node server.js
```
The application will be available at [http://localhost:3000](http://localhost:3000) by default.

- All static assets (HTML, CSS, JS, images) are served from the `public` directory.
- Navigation (sidebar), top navigation bar, and footer are modular and stored as separate HTML files (`sidebar.html`, `topnav.html`, `footer.html`).
- These components are loaded dynamically into each page using JavaScript, so you will not see their markup in every HTML file.
- Custom CSS utilities (e.g., `mt-n-custom-6`) are used for advanced layout control.

## Project Structure
```
├── server.js         # Express.js server
├── package.json      # Project metadata and dependencies
├── public/           # Static files (HTML, CSS, JS, images)
│   ├── index.html    # Main entry point
│   ├── customer.html # Customer page
│   ├── sidebar.html  # Modular sidebar navigation
│   ├── topnav.html   # Modular top navigation bar
│   ├── footer.html   # Modular footer
│   ├── styles.css    # Shared and custom styles
│   └── assets/       # Images, fonts, and other assets
├── docs/             # Documentation
│   ├── architecture.md
│   ├── auth-flow.md
│   └── setup-guide.md
└── README.md         # Project information
```

## License
Specify your license here.

## Author
Specify author information here.
