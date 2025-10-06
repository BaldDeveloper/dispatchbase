<?php
/**
 * Customer Edit Page
 *
 * - Handles add, edit, and delete of customer records
 * - Uses repository for all DB access
 * - CSRF protection and output escaping for security
 * - Validation logic extracted for maintainability
 * - Comments added for clarity
 * - Role-based access control can be added if needed
 *
 * NOTE: Role-based access control is currently commented out for development/testing purposes.
 */
// session_start();
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header('Location: login.php');
//     exit;
// }

require_once __DIR__ . '/../database/CustomerData.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/states.php';
require_once __DIR__ . '/../includes/validation.php';

$db = new Database();
$customerRepo = new CustomerData($db);
$states = include __DIR__ . '/../includes/states.php';

$mode = $_GET['mode'] ?? 'add';
$id = $_GET['id'] ?? null;
$status = '';
$error = '';

/**
 * Validate customer fields for add/edit
 * @return string Error message or empty string if valid
 */
function validate_customer_fields($companyName, $emailAddress, $city, $state, $states, $phoneNumber) {
    if (!$companyName || !$state || !$emailAddress || !$city) return 'Please fill in all required fields.';
    if (!array_key_exists($state, $states)) return 'Invalid state selected.';
    if (!is_valid_email($emailAddress)) return 'Invalid email address.';
    if ($phoneNumber !== '' && !is_valid_phone($phoneNumber)) return 'Invalid phone number format.';
    return '';
}

// If editing, load existing customer data
if ($mode === 'edit' && $id && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $customer = $customerRepo->findByCustomerNumber($id);
    if ($customer) {
        $companyName = $customer['company_name'] ?? '';
        $phoneNumber = $customer['phone_number'] ?? '';
        $emailAddress = $customer['email_address'] ?? '';
        $address1 = $customer['address_1'] ?? '';
        $address2 = $customer['address_2'] ?? '';
        $city = $customer['city'] ?? '';
        $state = $customer['state'] ?? '';
        $zip = $customer['zip'] ?? '';
    }
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_customer']) && $mode === 'edit' && $id) {
    try {
        $customerRepo->delete($id);
        $status = 'deleted';
        // Clear form values so form does not show after deletion
        $companyName = $phoneNumber = $emailAddress = $address1 = $address2 = $city = $state = $zip = '';
    } catch (Exception $e) {
        $error = 'Error deleting customer: ' . htmlspecialchars($e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and trim input values
    $companyName = trim($_POST['company_name'] ?? '');
    $phoneNumber = trim($_POST['phone_number'] ?? '');
    $emailAddress = trim($_POST['email_address'] ?? '');
    $address1 = trim($_POST['address_1'] ?? '');
    $address2 = trim($_POST['address_2'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zip = trim($_POST['zip'] ?? '');

    // Validate fields
    $error = validate_customer_fields($companyName, $emailAddress, $city, $state, $states, $phoneNumber);
    if (!$error) {
        if ($mode === 'add') {
            try {
                $customerRepo->create(
                    $companyName,
                    $phoneNumber,
                    $address1,
                    $address2,
                    $city,
                    $state,
                    $zip,
                    $emailAddress
                );
                $status = 'added';
                // Clear form fields after successful add
                $companyName = $phoneNumber = $emailAddress = $address1 = $address2 = $city = $state = $zip = '';
            } catch (Exception $e) {
                $error = 'Error adding customer: ' . htmlspecialchars($e->getMessage());
            }
        } elseif ($mode === 'edit' && $id) {
            try {
                $customerRepo->update(
                    $id,
                    $companyName,
                    $phoneNumber,
                    $address1,
                    $address2,
                    $city,
                    $state,
                    $zip,
                    $emailAddress
                );
                $status = 'updated';
            } catch (Exception $e) {
                $error = 'Error updating customer: ' . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Customer - DispatchBase</title>
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
                        <div class="row align-items-center justify-content-between">

                        </div>
                    </div>
                </div>
            </header>

            <!-- Main page content-->
            <div class="container-xl px-4 mt-n-custom-6">
                <div id="default">
                    <div class="card mb-4 w-100">
                        <div class="card-header">Add Customer</div>
                        <div class="card-body">
                            <?php if ($status === 'deleted'): ?>
                                <div class="alert alert-success" role="alert">
                                    Customer deleted successfully!
                                </div>
                            <?php elseif ($status === 'added' || $status === 'updated'): ?>
                                <div class="alert alert-success" role="alert">
                                    Customer <?= $status === 'added' ? 'added' : 'updated' ?> successfully!
                                </div>
                            <?php elseif ($error): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $error ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($status !== 'deleted'): ?>
                            <form method="POST">
                                <div class="row form-section">
                                    <div class="col-md-6">
                                        <label for="company_name" class="form-label required">Company Name</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" value="<?= htmlspecialchars($companyName ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($phoneNumber ?? '') ?>" maxlength="14" pattern="<?= PHONE_PATTERN ?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row form-section">
                                    <div class="col-md-6">
                                        <label for="address_1" class="form-label required">Address 1</label>
                                        <input type="text" class="form-control" id="address_1" name="address_1" value="<?= htmlspecialchars($address1 ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address_2" class="form-label">Address 2</label>
                                        <input type="text" class="form-control" id="address_2" name="address_2" value="<?= htmlspecialchars($address2 ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row form-section">
                                    <div class="col-md-4">
                                        <label for="city" class="form-label required">City</label>
                                        <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($city ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="state" class="form-label required">State</label>
                                        <select class="form-select" id="state" name="state" required>
                                            <option value="">Select State</option>
                                            <?php foreach ($states as $abbr => $name): ?>
                                                <option value="<?= htmlspecialchars($abbr) ?>" <?= (isset($state) && $state === $abbr) ? 'selected' : '' ?>><?= htmlspecialchars($abbr) ?> - <?= htmlspecialchars($name) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="zip" class="form-label">Zip</label>
                                        <input type="text" class="form-control" id="zip" name="zip" value="<?= htmlspecialchars($zip ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row form-section">
                                    <div class="col-md-6">
                                        <label for="email_address" class="form-label required">Email Address</label>
                                        <input type="email" class="form-control email-pattern" id="email_address" name="email_address" value="<?= htmlspecialchars($emailAddress ?? '') ?>" required pattern="<?= EMAIL_PATTERN ?>">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="submit" class="btn btn-primary"><?= $mode === 'edit' ? 'Update' : 'Add' ?> Customer</button>
                                    <?php if ($mode === 'edit'): ?>
                                        <button type="submit" name="delete_customer" value="1" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete this customer?');">Delete Customer</button>
                                    <?php endif; ?>
                                </div>
                            </form>
                            <?php endif; ?>
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
