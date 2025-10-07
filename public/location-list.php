<?php
/**
 * Location List Page
 *
 * - Displays a paginated list of locations
 * - Output escaping for XSS prevention
 * - UI and code structure matches customer-list.php
 */
require_once __DIR__ . '/../database/LocationsData.php';
require_once __DIR__ . '/../database/Database.php';

// Pagination setup
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$pageSize = isset($_GET['pageSize']) ? max(1, intval($_GET['pageSize'])) : 10;
$offset = ($page - 1) * $pageSize;

// Initialize database connection
$locationRepo = new LocationsData();
$totalLocations = $locationRepo->getCount();
$locations = $locationRepo->getPaginated($pageSize, $offset) ?? [];
$totalPages = ceil($totalLocations / $pageSize);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Location List - DispatchBase</title>
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
                        <div class="card-header">Location Details</div>
                        <div class="card-body">
                            <div class="dataTables_wrapper dt-bootstrap5">
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <form method="get" class="d-inline">
                                            Show
                                            <select name="pageSize" aria-controls="entries" class="form-select form-select-sm" onchange="this.form.submit()">
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
                                            <th>Location ID</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Zip Code</th>
                                            <th>Phone Number</th>
                                            <th>Location Type</th>
                                            <th>Created At</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($locations as $loc): ?>
                                            <tr>
                                                <td><a href="location-edit.php?mode=edit&id=<?= htmlspecialchars($loc['id']) ?>"><?= htmlspecialchars($loc['id']) ?></a></td>
                                                <td><?= htmlspecialchars($loc['name'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($loc['address'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($loc['city'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($loc['state'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($loc['zip_code'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($loc['phone_number'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($loc['location_type'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($loc['created_at'] ?? '') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($locations)): ?>
                                            <tr>
                                                <td colspan="9" class="text-danger">No locations found.</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Pagination controls -->
                                <nav aria-label="Location list pagination" class="mt-3">
                                    <ul class="pagination justify-content-center">
                                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                            <li class="page-item<?= $p == $page ? ' active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $p ?>&pageSize=<?= $pageSize ?>"><?= $p ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

