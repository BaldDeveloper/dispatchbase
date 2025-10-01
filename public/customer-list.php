<?php
require_once 'database/CustomerData.php';
require_once 'database/Database.php';

// Initialize database connection
$db = new Database();
$customerRepo = new CustomerData($db);

// Fetch all customers
$customers = $customerRepo->getAll() ?? [];
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
                                        <label>
                                            Show
                                            <select name="entries_length" aria-controls="entries" class="form-select form-select-sm">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                            entries
                                        </label>
                                    </div>
                                    <div class="col-sm-12 col-md-6 text-end">
                                        <label>
                                            Search:
                                            <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="entries">
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
                                                <td><?= htmlspecialchars($customer['company_name'] ?? '') ?></td>
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
                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info">Showing 1 to <?= count($customers) ?> of <?= count($customers) ?> entries</div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <nav aria-label="Table pagination">
                                            <ul class="pagination justify-content-end">
                                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
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
