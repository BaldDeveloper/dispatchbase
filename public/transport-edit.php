<?php
// transport-edit.php
require_once 'database/TransportData.php';
require_once 'database/Database.php';
require_once 'database/DecedentData.php';
require_once 'database/CustomerData.php';
require_once 'database/LocationsData.php';
require_once 'database/CoronerData.php';
require_once 'database/PouchData.php';
require_once 'database/UserData.php';

$db = new Database();
$transportRepo = new TransportData($db);
$customerRepo = new CustomerData($db);
$locationsData = new LocationsData($db);
$coronerData = new CoronerData($db);
$pouchData = new PouchData($db);
$userData = new UserData($db);

$success = false;
$error = '';

$mode = $_GET['mode'] ?? 'add';
$id = $_GET['id'] ?? null;

// Default values for form fields
$firmId = '';
$firmDate = '';
$firmAccountType = '';
$originLocation = '';
$destinationLocation = '';
$permitNumber = '';
$tagNumber = '';
$decedentFirstName = '';
$decedentMiddleName = '';
$decedentLastName = '';
$decedentEthnicity = '';
$decedentGender = '';
$coronerName = '';
$pouchType = '';
$primaryTransporter = '';
$assistantTransporter = '';

// Add default values for new time fields
$callTime = '';
$arrivalTime = '';
$departureTime = '';
$deliveryTime = '';

// Transit section data preparation
$allLocations = $locationsData->getAllLocations();
$originLocations = array_filter($allLocations, function($loc) {
    return $loc['location_type'] === 'origin' || $loc['location_type'] === 'both';
});
$destinationLocations = array_filter($allLocations, function($loc) {
    return $loc['location_type'] === 'destination' || $loc['location_type'] === 'both';
});
$coroners = $coronerData->getAll();
$pouchTypes = $pouchData->getAll();
$drivers = $userData->getDrivers();

if ($mode === 'edit' && $id && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $transport = $transportRepo->findById($id);
    if ($transport) {
        $firmId = $transport['firm_id'] ?? '';
        $firmDate = $transport['firm_date'] ?? '';
        $firmAccountType = $transport['firm_account_type'] ?? '';
        $originLocation = $transport['origin_location'] ?? '';
        $destinationLocation = $transport['destination_location'] ?? '';
        $coronerName = $transport['coroner_name'] ?? '';
        $permitNumber = $transport['permit_number'] ?? '';
        $tagNumber = $transport['tag_number'] ?? '';
        $pouchType = $transport['pouch_type'] ?? '';
        $transitPermitNumber = $transport['transit_permit_number'] ?? '';
        $primaryTransporter = $transport['primary_transporter'] ?? '';
        $assistantTransporter = $transport['assistant_transporter'] ?? '';
        $callTime = $transport['call_time'] ?? '';
        $arrivalTime = $transport['arrival_time'] ?? '';
        $departureTime = $transport['departure_time'] ?? '';
        $deliveryTime = $transport['delivery_time'] ?? '';
        $decedentRepo = new DecedentData($db);
        $decedent = $db->query("SELECT * FROM decedent WHERE transport_id = ?", [$id]);
        if (!empty($decedent[0])) {
            $decedentFirstName = $decedent[0]['first_name'] ?? '';
            $decedentMiddleName = $decedent[0]['middle_name'] ?? '';
            $decedentLastName = $decedent[0]['last_name'] ?? '';
            $decedentEthnicity = $decedent[0]['ethnicity'] ?? '';
            $decedentGender = $decedent[0]['gender'] ?? '';
        } else {
            $decedentFirstName = $transport['decedent_first_name'] ?? '';
            $decedentMiddleName = $transport['decedent_middle_name'] ?? '';
            $decedentLastName = $transport['decedent_last_name'] ?? '';
            $decedentEthnicity = $transport['decedent_ethnicity'] ?? '';
            $decedentGender = $transport['decedent_gender'] ?? '';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_transport']) && $mode === 'edit' && $id) {
    try {
        $decedentRepo = new DecedentData($db);
        $decedentRepo->deleteByTransportId((int)$id);
        $transportRepo->delete((int)$id);
        $success = 'deleted';
        // Clear form values so form does not show after deletion
        $firmId = $firmDate = $firmAccountType = $originLocation = $destinationLocation = $permitNumber = $tagNumber = '';
        $decedentFirstName = $decedentMiddleName = $decedentLastName = $decedentEthnicity = $decedentGender = '';
    } catch (Exception $e) {
        $error = 'Error deleting transport: ' . htmlspecialchars($e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firmId = $_POST['firm_id'] ?? '';
    $firmDate = $_POST['firm_date'] ?? '';
    $firmAccountType = $_POST['firm_account_type'] ?? '';
    $firstName = $_POST['first_name'] ?? $decedentFirstName;
    $lastName = $_POST['last_name'] ?? $decedentLastName;
    $ethnicity = $_POST['ethnicity'] ?? $decedentEthnicity;
    $gender = $_POST['gender'] ?? $decedentGender;
    // Get posted transit values
    $originLocationId = $_POST['origin_location'] ?? $originLocation;
    $destinationLocationId = $_POST['destination_location'] ?? $destinationLocation;
    $coronerId = $_POST['coroner'] ?? '';
    $pouchType = $_POST['pouch_type'] ?? '';
    $transitPermitNumber = $_POST['transit_permit_number'] ?? '';
    $tagNumber = $_POST['tag_number'] ?? '';
    $primaryTransporter = $_POST['primary_transporter'] ?? $primaryTransporter;
    $assistantTransporter = $_POST['assistant_transporter'] ?? $assistantTransporter;
    $callTime = $_POST['call_time'] ?? '';
    $arrivalTime = $_POST['arrival_time'] ?? '';
    $departureTime = $_POST['departure_time'] ?? '';
    $deliveryTime = $_POST['delivery_time'] ?? '';
    // Lookup coroner name from ID
    $coronerName = '';
    foreach ($coroners as $c) {
        if (($c['id'] ?? $c['coroner_number']) == $coronerId) {
            $coronerName = $c['coroner_name'];
            break;
        }
    }
    $decedentRepo = new DecedentData($db);
    $transport_id = $id ? (int)$id : null;
    if ($firmId && $firmDate && $firmAccountType && $callTime && $arrivalTime && $departureTime && $deliveryTime) {
        try {
            if ($mode === 'edit' && $transport_id) {
                // Update transport record
                $transportRepo->update(
                    $transport_id,
                    (int)$firmId,
                    $firmDate,
                    $firmAccountType,
                    $originLocationId,
                    $destinationLocationId,
                    $coronerName,
                    $pouchType,
                    $transitPermitNumber,
                    $tagNumber,
                    $callTime,
                    $arrivalTime,
                    $departureTime,
                    $deliveryTime,
                    $primaryTransporter ? (int)$primaryTransporter : null,
                    $assistantTransporter ? (int)$assistantTransporter : null
                );
                // Update decedent table with matching transport_id
                $decedentRepo->updateByTransportId(
                    $transport_id,
                    $firstName,
                    $lastName,
                    $ethnicity,
                    $gender
                );
            } else {
                $transport_id = $transportRepo->create(
                    (int)$firmId,
                    $firmDate,
                    $firmAccountType,
                    $originLocationId,
                    $destinationLocationId,
                    $coronerName,
                    $pouchType,
                    $transitPermitNumber,
                    $tagNumber,
                    $callTime,
                    $arrivalTime,
                    $departureTime,
                    $deliveryTime,
                    $primaryTransporter ? (int)$primaryTransporter : null,
                    $assistantTransporter ? (int)$assistantTransporter : null
                );
                // Check if decedent record exists for this transport_id
                $existingDecedent = $db->query("SELECT * FROM decedent WHERE transport_id = ?", [$transport_id]);
                if (empty($existingDecedent)) {
                    $decedentRepo->insertByTransportId(
                        $transport_id,
                        $firstName,
                        $lastName,
                        $ethnicity,
                        $gender
                    );
                } else {
                    $decedentRepo->updateByTransportId(
                        $transport_id,
                        $firstName,
                        $lastName,
                        $ethnicity,
                        $gender
                    );
                }
            }
            $success = true;
        } catch (Exception $e) {
            $error = 'Error saving transport: ' . htmlspecialchars($e->getMessage());
        }
    } else {
        $error = 'Please fill in all required fields.';
    }
}

$customers = $customerRepo->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Add Transport - DispatchBase</title>
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
    <?php include 'sidebar.html'; ?>
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
                <!-- Main content -->
                <div id="default">
                    <div class="card mb-4 w-100">
                        <div class="card-header">Add Transport</div>
                        <div class="card-body">
                            <?php if ($success === 'deleted'): ?>
                                <div class="alert alert-success" role="alert">
                                    Transport deleted successfully!
                                </div>
                            <?php elseif ($success): ?>
                                <div class="alert alert-success" role="alert">
                                    Transport added/updated successfully!
                                </div>
                            <?php elseif ($error): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $error ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($success !== 'deleted'): ?>
                            <form method="POST">
                                <?php include('firm-edit.php'); ?>

                                <?php include('decedent-edit.php'); ?>

                                <?php include('transit-edit.php'); ?>

                                <?php include('times-edit.php'); ?>

                                <div id="charges-section">
                                    <div class="container-xl px-1">
                                        <div class="page-header-content pt-4">
                                            <div class="row align-items-center justify-content-between">
                                                <h4>Charges Information</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- add includes here when ready -->


                                </div>
                                <!-- Hidden fields for all other transport columns with default values -->
                                <input type="hidden" name="decedent_first_name" value="<?= htmlspecialchars($decedentFirstName) ?>" />
                                <input type="hidden" name="decedent_middle_name" value="<?= htmlspecialchars($decedentMiddleName) ?>" />
                                <input type="hidden" name="decedent_last_name" value="<?= htmlspecialchars($decedentLastName) ?>" />
                                <input type="hidden" name="decedent_ethnicity" value="<?= htmlspecialchars($decedentEthnicity) ?>" />
                                <input type="hidden" name="decedent_gender" value="<?= htmlspecialchars($decedentGender) ?>" />
                                <input type="hidden" name="permit_number" value="<?= htmlspecialchars($permitNumber) ?>" />
                                <input type="hidden" name="tag_number" value="<?= htmlspecialchars($tagNumber) ?>" />
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="submit" class="btn btn-primary" id="saveTransportBtn">
                                        <?= $mode === 'edit' ? 'Update' : 'Add' ?> Transport
                                    </button>
                                    <?php if ($mode === 'edit' && $id): ?>
                                        <button type="submit" name="delete_transport" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete this transport? This will also delete the associated decedent record.');">Delete Transport</button>
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

    document.addEventListener('DOMContentLoaded', function() {
        var transportForm = document.getElementById('transportForm');
        var clientError = document.getElementById('client-error');
        transportForm.addEventListener('submit', function(e) {
            // Remove previous error messages and highlighting
            clientError.style.display = 'none';
            var previousAlerts = transportForm.querySelectorAll('.field-error-alert');
            previousAlerts.forEach(function(alert) { alert.remove(); });
            var requiredFields = transportForm.querySelectorAll('[required]');
            var firstInvalid = null;
            requiredFields.forEach(function(field) {
                field.classList.remove('is-invalid');
            });
            for (var i = 0; i < requiredFields.length; i++) {
                var field = requiredFields[i];
                if (!field.value || field.value.trim() === '') {
                    firstInvalid = field;
                    break;
                }
            }
            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.classList.add('is-invalid');
                // Create and insert error message next to the field
                var errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger field-error-alert';
                errorDiv.style.marginTop = '5px';
                errorDiv.innerText = 'Please fill in all required fields.';
                if (firstInvalid.parentNode) {
                    // If field is inside a table cell or div, insert after
                    if (firstInvalid.nextSibling) {
                        firstInvalid.parentNode.insertBefore(errorDiv, firstInvalid.nextSibling);
                    } else {
                        firstInvalid.parentNode.appendChild(errorDiv);
                    }
                }
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
</script>
</body>
</html>
