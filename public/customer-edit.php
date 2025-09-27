<?php
require_once 'database/CustomerData.php';
require_once 'database/Database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/states.php';

$db = new Database();
$customerRepo = new CustomerData($db);
$states = include __DIR__ . '/../includes/states.php';

$mode = $_GET['mode'] ?? 'add';
$id = $_GET['id'] ?? null;
$success = false;
$error = '';

if ($mode === 'edit' && $id && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $sql = "SELECT * FROM customer WHERE customer_number = ?";
    $params = [$id];
    $result = $db->query($sql, $params);

    if (count($result) > 0) {
        $customer = $result[0];
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_customer']) && $mode === 'edit' && $id) {
    try {
        $customerRepo->delete($id);
        $success = 'deleted';
        // Clear form values so form does not show after deletion
        $companyName = $phoneNumber = $emailAddress = $address1 = $address2 = $city = $state = $zip = '';
    } catch (Exception $e) {
        $error = 'Error deleting customer: ' . htmlspecialchars($e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName = trim($_POST['company_name'] ?? '');
    $phoneNumber = trim($_POST['phone_number'] ?? '');
    $emailAddress = trim($_POST['email_address'] ?? '');
    $address1 = trim($_POST['address_1'] ?? '');
    $address2 = trim($_POST['address_2'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    // Validate state abbreviation
    if (!array_key_exists($state, $states)) {
        $error = 'Invalid state selected.';
        $state = '';
    }
    $zip = trim($_POST['zip'] ?? '');

    // Ensure all optional fields are blank if not set
    $phoneNumber = $phoneNumber ?: '';
    $address1 = $address1 ?: '';
    $address2 = $address2 ?: '';
    $city = $city ?: '';
    $zip = $zip ?: '';

    // Basic validation
    if ($companyName && $state && $emailAddress && $city && !$error) {
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
                $success = true;
                // Clear form fields after successful add
                if ($mode === 'add') {
                    $companyName = $phoneNumber = $emailAddress = $address1 = $address2 = $city = $state = $zip = '';
                }
            } catch (Exception $e) {
                $error = 'Error adding customer: ' . htmlspecialchars($e->getMessage());
            }
        } elseif ($mode === 'edit' && $id) {
            try {
                $customerRepo->update(
                    $id, // no need to assign $customerNumber here
                    $companyName,
                    $phoneNumber,
                    $address1,
                    $address2,
                    $city,
                    $state,
                    $zip,
                    $emailAddress
                );
                $success = true;
            } catch (Exception $e) {
                $error = 'Error updating customer: ' . htmlspecialchars($e->getMessage());
            }
        }
    } else {
        $error = 'Please fill in all required fields.';
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
    <title>Add Customer - DispatchBase</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/field-error.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
    <style>
        .required::after {
            content: " *";
            color: red;
        }
        .form-section {
            margin-bottom: 1rem;
        }
    </style>
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

                        </div>
                    </div>
                </div>
            </header>
            <div class="container-xl px-4 mt-n-custom-6">
                <div id="default">
                    <div class="card mb-4 w-100">
                        <div class="card-header">Add Customer</div>
                        <div class="card-body">
                            <?php if ($success === 'deleted'): ?>
                                <div class="alert alert-success" role="alert">
                                    Customer deleted successfully!
                                </div>
                            <?php elseif ($success): ?>
                                <div class="alert alert-success" role="alert">
                                    Customer added/updated successfully!
                                </div>
                            <?php elseif ($error): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $error ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($success !== 'deleted'): ?>
                            <form method="POST">
                                <div class="row form-section">
                                    <div class="col-md-6">
                                        <label for="company_name" class="form-label required">Company Name</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" value="<?= htmlspecialchars($companyName ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($phoneNumber ?? '') ?>" maxlength="14" pattern="\(\d{3}\)\d{3}-\d{4}" autocomplete="off">
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
                                        <input type="email" class="form-control email-pattern" id="email_address" name="email_address" value="<?= htmlspecialchars($emailAddress ?? '') ?>" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
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
            document.getElementById('topnav').outerHTML = html;
            feather.replace();
        });
    // Dynamically load sidebar.html into #sidebar
    fetch('sidebar.html')
        .then(response => response.text())
        .then(html => {
            document.getElementById('sidebar').outerHTML = html;
            feather.replace();
        });
    // Dynamically load footer.html into #footer
    fetch('footer.html')
        .then(response => response.text())
        .then(html => {
            document.getElementById('footer').outerHTML = html;
        });
</script>
<script>
// Generic required field validation for all forms
function validateRequiredFields(form) {
    let firstInvalid = null;
    // Remove previous error highlighting
    form.querySelectorAll('.field-error').forEach(function(field) {
        field.classList.remove('field-error');
    });
    // Validate required fields
    form.querySelectorAll('[required]').forEach(function(field) {
        if (!field.value || field.value.trim() === '') {
            field.classList.add('field-error');
            if (!firstInvalid) firstInvalid = field;
        }
    });
    // Special case: phone number pattern validation (if present)
    var phoneInput = form.querySelector('#phone_number');
    if (phoneInput && phoneInput.value) {
        var phonePattern = /^\(\d{3}\)\d{3}-\d{4}$/;
        if (!phonePattern.test(phoneInput.value)) {
            phoneInput.classList.add('field-error');
            if (!firstInvalid) firstInvalid = phoneInput;
        }
    }
    if (firstInvalid) {
        firstInvalid.focus();
        return false;
    }
    return true;
}

// Generic email field validation
function validateEmailFields(form) {
    let firstInvalid = null;
    // Remove previous error highlighting
    form.querySelectorAll('.email-error').forEach(function(field) {
        field.classList.remove('email-error');
    });
    // Validate email fields
    form.querySelectorAll('.email-pattern').forEach(function(field) {
        if (!field.value || field.value.trim() === '') {
            field.classList.add('email-error');
            if (!firstInvalid) firstInvalid = field;
        } else {
            // Use the same pattern as the HTML attribute
            var pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!pattern.test(field.value)) {
                field.classList.add('email-error');
                if (!firstInvalid) firstInvalid = field;
            }
        }
    });
    if (firstInvalid) {
        firstInvalid.focus();
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form[method="POST"]');
    if (!form) return;
    form.addEventListener('submit', function(e) {
        // Only validate for add/update, not delete
        var submitter = e.submitter || document.activeElement;
        if (submitter && submitter.name === 'delete_customer') return;
        if (!validateRequiredFields(form) || !validateEmailFields(form)) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>
