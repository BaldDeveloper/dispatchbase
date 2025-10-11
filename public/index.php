<?php
// Front Controller: public/index.php
// All requests are routed through this file

// List of allowed pages (add more as needed)
$allowedPages = [
    'customer-list' => 'customer-list.php',
    'customer-edit' => 'customer-edit.php',
    'coroner-list' => 'coroner-list.php',
    'coroner-edit' => 'coroner-edit.php',
    'transport-list' => 'transport-list.php',
    'transport-edit' => 'transport-edit.php',
    'pouch-list' => 'pouch-list.php',
    'pouch-edit' => 'pouch-edit.php',
    'user-list' => 'user-list.php',
    'user-edit' => 'user-edit.php',
    'charges-edit' => 'charges-edit.php',
    'location-list' => 'location-list.php',
    'location-edit' => 'location-edit.php',
    'mileage-edit' => 'mileage-edit.php',
    'firm-edit' => 'firm-edit.php',
    'times-edit' => 'times-edit.php',
    'transit-edit' => 'transit-edit.php',
    'decedent-edit' => 'decedent-edit.php',
    'dashboard' => 'dashboard.html',
];

// Get requested page, default to dashboard
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Sanitize and resolve page
if (array_key_exists($page, $allowedPages)) {
    $pageFile = __DIR__ . '/' . $allowedPages[$page];
    if (file_exists($pageFile)) {
        include $pageFile;
        exit;
    }
}
// If not found, show 404
http_response_code(404);
echo '<h1>404 Not Found</h1><p>The requested page does not exist.</p>';
