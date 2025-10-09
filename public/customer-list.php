<?php
/**
 * Customer List Page
 *
 * - Displays a paginated list of customers
 * - Output escaping for XSS prevention
 * - Comments added for maintainability
 * - Ready for future search/filter/pagination enhancements
 *
 * NOTE: Role-based access control is currently commented out for development/testing purposes.
 */

// session_start();
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header('Location: login.php');
//     exit;
// }

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../services/CustomerService.php'; // Use service class only

// Pagination setup
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$pageSize = isset($_GET['pageSize']) ? max(1, intval($_GET['pageSize'])) : 10;
$offset = ($page - 1) * $pageSize;

// Initialize database connection
$db = new Database();
$customerService = new CustomerService($db); // Pass db to service if required

// Fetch paginated customers using service
$totalCustomers = $customerService->getCount();
$customers = $customerService->getPaginated($pageSize, $offset) ?? [];
$totalPages = ceil($totalCustomers / $pageSize);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Customer List - DispatchBase</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
</head>
<body class="nav-fixed">
<div id="topnav"></div>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav"></div>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-5" style="padding-bottom: 9%;">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                        <div class="row align-items-center justify-content-between"></div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-n-custom-6">
                <div id="default">
                    <div class="card mb-4 w-100">
                        <div class="card-header">Customer Details</div>
                        <div class="card-body">
                            <div class="dataTables_wrapper dt-bootstrap5">
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <form method="get" class="d-inline">
                                            <label for="pageSize" class="visually-hidden">Page size</label>
                                            Show
                                            <select id="pageSize" name="pageSize" aria-controls="entries" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="10"<?= $pageSize == 10 ? ' selected' : '' ?>>10</option>
                                                <option value="25"<?= $pageSize == 25 ? ' selected' : '' ?>>25</option>
                                                <option value="50"<?= $pageSize == 50 ? ' selected' : '' ?>>50</option>
                                                <option value="100"<?= $pageSize == 100 ? ' selected' : '' ?>>100</option>
                                            </select>
                                        </form>
                                    </div>
                                    <div class="col-sm-12 col-md-6 text-end">
                                        <label>
                                            Search:
                                            <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="entries" disabled>
                                        </label>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Customer #</th>
                                            <th>Company Name</th>
                                            <th>Address 1</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Zip</th>
                                            <th>Phone Number</th>
                                            <th>Email Address</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($customers as $customer): ?>
                                            <tr>
                                                <td><a href="customer-edit.php?mode=edit&id=<?= htmlspecialchars($customer['customer_number'] ?? '') ?>"><?= htmlspecialchars($customer['customer_number'] ?? '') ?></a></td>
                                                <td><?= htmlspecialchars($customerService->formatDisplayName($customer)) ?></td>
                                                <td><?= htmlspecialchars($customer['address_1'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($customer['city'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($customer['state'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($customer['zip'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($customer['phone_number'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($customer['email_address'] ?? '') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($customers)): ?>
                                            <tr>
                                                <td colspan="8" class="text-danger">No customers found.</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Pagination controls -->
                                <nav aria-label="Customer list pagination" class="mt-3">
                                    <ul class="pagination justify-content-center">
                                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                            <li class="page-item<?= $p == $page ? ' active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $p ?>&pageSize=<?= $pageSize ?>"><?= $p ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            </div> <!-- /.dataTables_wrapper -->
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div> <!-- /#default -->
            </div> <!-- /.container-xl -->
        </main>
        <div id="footer"></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="js/phone-format.js"></script>
<script>
    // Dynamically load topnav.html into #topnav
    fetch('topnav.html')
        .then(response => response.text())
        .then(html => {
            var topnav = document.getElementById('topnav');
            if (topnav) {
                topnav.innerHTML = html;
                feather.replace();
                if (typeof initSidebarToggle === 'function') initSidebarToggle();
            }
        });
    // Dynamically load sidebar.html into #layoutSidenav_nav
    fetch('sidebar.html')
        .then(response => response.text())
        .then(html => {
            var sidenav = document.getElementById('layoutSidenav_nav');
            if (sidenav) {
                sidenav.innerHTML = html;
                feather.replace();
            }
        });
    // Dynamically load footer.html into #footer
    fetch('footer.html')
        .then(response => response.text())
        .then(html => {
            var footer = document.getElementById('footer');
            if (footer) {
                footer.innerHTML = html;
            }
        });
</script>
</body>
</html>
