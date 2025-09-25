<?php
require_once 'database/TransportData.php';
require_once 'database/Database.php';

// Initialize database connection
$db = new Database();
$transportRepo = new TransportData($db);

// Fetch all transport logs
$transports = $transportRepo->getAll() ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Transport Logs - DispatchBase</title>
  <link href="css/styles.css" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
</head>
<body class="nav-fixed">
  <div id="topnav"></div>
  <div id="layoutSidenav">
    <div id="layoutSidenav_nav">
      <div id="sidebar"></div>
    </div>
    <div id="layoutSidenav_content">
      <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-5" style="padding-bottom: 9%;">
          <div class="container-xl px-4">
            <div class="page-header-content pt-4">
              <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                </div>
              </div>
            </div>
          </div>
        </header>
        <div class="container-xl px-4 mt-n-custom-6">
          <div id="default">
            <div class="card mb-4 w-100">
              <div class="card-header">Transport Entries</div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Date</th>
                        <th>Firm ID</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Deceased First Name</th>
                        <th>Deceased Last Name</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($transports as $t): ?>
                        <tr>
                          <td><?= htmlspecialchars($t['form_date'] ?? '') ?></td>
                          <td><?= htmlspecialchars($t['firm_id'] ?? '') ?></td>
                          <td><?= htmlspecialchars($t['origin_location'] ?? '') ?></td>
                          <td><?= htmlspecialchars($t['destination_location'] ?? '') ?></td>
                          <td><?= htmlspecialchars($t['decedent_first_name'] ?? '') ?></td>
                          <td><?= htmlspecialchars($t['decedent_last_name'] ?? '') ?></td>
                          <td><a href="transport-edit.php?mode=edit&id=<?= urlencode($t['transport_id']) ?>">Edit</a></td>
                        </tr>
                      <?php endforeach; ?>
                      <?php if (empty($transports)): ?>
                        <tr>
                          <td colspan="7" class="text-danger">No transport logs found.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <div class="mt-3 d-flex justify-content-between align-items-center">
                  <div>Showing 1 to <?= count($transports) ?> of <?= count($transports) ?> entries</div>
                  <nav>
                    <ul class="pagination mb-0">
                      <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                      <li class="page-item active"><a class="page-link" href="#">1</a></li>
                      <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
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
  <script>
    fetch('topnav.html').then(res => res.text()).then(html => {
      document.getElementById('topnav').outerHTML = html;
      feather.replace();
    });
    fetch('sidebar.html').then(res => res.text()).then(html => {
      document.getElementById('sidebar').outerHTML = html;
      feather.replace();
    });
    fetch('footer.html').then(res => res.text()).then(html => {
      document.getElementById('footer').outerHTML = html;
    });
  </script>
</body>
</html>
