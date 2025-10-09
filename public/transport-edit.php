<?php
// transport-edit.php
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../services/TransportService.php';
require_once __DIR__ . '/../services/LocationService.php';
require_once __DIR__ . '/../services/CoronerService.php';
require_once __DIR__ . '/../services/PouchService.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../database/TransportData.php';
require_once __DIR__ . '/../database/CustomerData.php';
require_once __DIR__ . '/../database/TransportChargesData.php';
require_once __DIR__ . '/../database/DecedentData.php';

$db = new Database();
$transportService = new TransportService($db);
$locationService = new LocationService($db);
$coronerService = new CoronerService($db);
$pouchService = new PouchService($db);
$userService = new UserService($db);
$transportRepo = new TransportData($db);
$customerRepo = new CustomerData($db);
$chargesData = new TransportChargesData($db);

$success = false;
$error = '';

$mode = $_GET['mode'] ?? 'add';
$id = $_GET['id'] ?? null;

// Validate transport_id
if ($mode === 'edit') {
    if (empty($id) || !ctype_digit(strval($id)) || (int)$id <= 0) {
        die('Invalid or missing transport_id.');
    }
}

// Default values for form fields
$customerId = '';
$firmDate = '';
$accountType = '';
$originLocation = '';
$destinationLocation = '';
$originLocationId = '';
$destinationLocationId = '';
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
$allLocations = $locationService->getAll();
$originLocations = array_filter($allLocations, function($loc) {
    return $loc['location_type'] === 'origin' || $loc['location_type'] === 'both';
});
$destinationLocations = array_filter($allLocations, function($loc) {
    return $loc['location_type'] === 'destination' || $loc['location_type'] === 'both';
});
$coroners = $coronerService->getAll();
$pouchTypes = $pouchService->getAll();
$drivers = $userService->getDrivers();

if ($mode === 'edit' && $id && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $transport = $transportService->findById($id);
    $charges = $chargesData->findByTransportId($id) ?: [
        'removal_charge' => '',
        'pouch_charge' => '',
        'embalming_charge' => '',
        'transport_fees' => '',
        'cremation_charge' => '',
        'wait_charge' => '',
        'mileage_fees' => '',
        'other_charge_1' => '',
        'other_charge_1_description' => '',
        'other_charge_2' => '',
        'other_charge_2_description' => '',
        'other_charge_3' => '',
        'other_charge_3_description' => '',
        'other_charge_4' => '',
        'other_charge_4_description' => '',
        'total_charge' => ''
    ];
    if ($transport) {
        $customerId = $transport['customer_id'] ?? '';
        $firmDate = $transport['firm_date'] ?? '';
        $accountType = $transport['account_type'] ?? '';
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
        $mileage = $transport['mileage'] ?? '';
        $mileage_rate = $transport['mileage_rate'] ?? '';
        $mileage_total_charge = $transport['mileage_total_charge'] ?? '';
        $originLocationId = $transport['origin_location'] ?? '';
        $destinationLocationId = $transport['destination_location'] ?? '';
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
    // Set charge variables for form
    $removal_charge = $charges['removal_charge'] ?? '';
    $pouch_charge = $charges['pouch_charge'] ?? '';
    $embalming_charge = $charges['embalming_charge'] ?? '';
    $transport_fees = $charges['transport_fees'] ?? '';
    $cremation_charge = $charges['cremation_charge'] ?? '';
    $wait_charge = $charges['wait_charge'] ?? '';
    $mileage_fees = $charges['mileage_fees'] ?? '';
    $other_charge_1 = $charges['other_charge_1'] ?? '';
    $other_charge_1_description = $charges['other_charge_1_description'] ?? '';
    $other_charge_2 = $charges['other_charge_2'] ?? '';
    $other_charge_2_description = $charges['other_charge_2_description'] ?? '';
    $other_charge_3 = $charges['other_charge_3'] ?? '';
    $other_charge_3_description = $charges['other_charge_3_description'] ?? '';
    $other_charge_4 = $charges['other_charge_4'] ?? '';
    $other_charge_4_description = $charges['other_charge_4_description'] ?? '';
    $total_charge = $charges['total_charge'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_transport']) && $mode === 'edit' && $id) {
    try {
        $decedentRepo = new DecedentData($db);
        $decedentRepo->deleteByTransportId((int)$id);
        $chargesData = new TransportChargesData($db);
        $chargesData->deleteByTransportId((int)$id);
        $transportRepo->delete((int)$id);
        $success = 'deleted';
        // Clear form values so form does not show after deletion
        $customerId = $firmDate = $accountType = $originLocation = $destinationLocation = $permitNumber = $tagNumber = '';
        $decedentFirstName = $decedentMiddleName = $decedentLastName = $decedentEthnicity = $decedentGender = '';
    } catch (Exception $e) {
        $error = 'Error deleting transport: ' . htmlspecialchars($e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retain all form values from POST, fallback to previous value if not set
    $customerId = $_POST['customer_id'] ?? $customerId;
    $firmDate = $_POST['firm_date'] ?? $firmDate;
    $accountType = $_POST['account_type'] ?? $accountType;
    $decedentFirstName = $_POST['first_name'] ?? $decedentFirstName;
    $decedentMiddleName = $_POST['middle_name'] ?? $decedentMiddleName;
    $decedentLastName = $_POST['last_name'] ?? $decedentLastName;
    $decedentEthnicity = $_POST['ethnicity'] ?? $decedentEthnicity;
    $decedentGender = $_POST['gender'] ?? $decedentGender;
    $originLocation = $_POST['origin_location'] ?? $originLocation;
    $destinationLocation = $_POST['destination_location'] ?? $destinationLocation;
    // Ensure location IDs are set for DB update
    $originLocationId = $originLocation;
    $destinationLocationId = $destinationLocation;
    $coronerName = $_POST['coroner'] ?? $coronerName;
    // If coronerName is an ID (from POST), convert to name for dropdown selection
    if (!empty($coronerName) && is_array($coroners)) {
        foreach ($coroners as $coroner) {
            $coronerId = $coroner['id'] ?? $coroner['coroner_number'] ?? null;
            if ($coronerId == $_POST['coroner']) {
                $coronerName = $coroner['coroner_name'];
                break;
            }
        }
    }
    $pouchType = $_POST['pouch_type'] ?? $pouchType;
    $transitPermitNumber = $_POST['transit_permit_number'] ?? $transitPermitNumber;
    $tagNumber = $_POST['tag_number'] ?? $tagNumber;
    $primaryTransporter = $_POST['primary_transporter'] ?? $primaryTransporter;
    $assistantTransporter = $_POST['assistant_transporter'] ?? $assistantTransporter;
    $callTime = $_POST['call_time'] ?? $callTime;
    $arrivalTime = $_POST['arrival_time'] ?? $arrivalTime;
    $departureTime = $_POST['departure_time'] ?? $departureTime;
    $deliveryTime = $_POST['delivery_time'] ?? $deliveryTime;
    $mileage = $_POST['mileage'] ?? $mileage;
    $mileage_rate = $_POST['mileage_rate'] ?? $mileage_rate;
    $mileage_total_charge = $_POST['mileage_total_charge'] ?? $mileage_total_charge;
    $removal_charge = $_POST['removal_charge'] ?? $removal_charge;
    $pouch_charge = $_POST['pouch_charge'] ?? $pouch_charge;
    $embalming_charge = $_POST['embalming_charge'] ?? $embalming_charge;
    $transport_fees = $_POST['transport_fees'] ?? $transport_fees;
    $cremation_charge = $_POST['cremation_charge'] ?? $cremation_charge;
    $wait_charge = $_POST['wait_charge'] ?? $wait_charge;
    $mileage_fees = $_POST['mileage_fees'] ?? $mileage_fees;
    $other_charge_1 = $_POST['other_charge_1'] ?? $other_charge_1;
    $other_charge_1_description = $_POST['other_charge_1_description'] ?? $other_charge_1_description;
    $other_charge_2 = $_POST['other_charge_2'] ?? $other_charge_2;
    $other_charge_2_description = $_POST['other_charge_2_description'] ?? $other_charge_2_description;
    $other_charge_3 = $_POST['other_charge_3'] ?? $other_charge_3;
    $other_charge_3_description = $_POST['other_charge_3_description'] ?? $other_charge_3_description;
    $other_charge_4 = $_POST['other_charge_4'] ?? $other_charge_4;
    $other_charge_4_description = $_POST['other_charge_4_description'] ?? $other_charge_4_description;
    $total_charge = $_POST['total_charge'] ?? $total_charge;
    $decedentRepo = new DecedentData($db); // Ensure this is initialized before use
    $chargesData = new TransportChargesData($db); // Ensure this is initialized before use
    $transport_id = $id ? (int)$id : null;
    $existingCharges = ($transport_id !== null) ? $chargesData->findByTransportId($transport_id) : null;
    if ($customerId && $firmDate && $accountType && $callTime && $arrivalTime && $departureTime && $deliveryTime) {
        try {
            $chargesData = new TransportChargesData($db);
            // Cast all numeric charge fields to float before DB operations
            $removal_charge = (float)$removal_charge;
            $pouch_charge = (float)$pouch_charge;
            $embalming_charge = (float)$embalming_charge;
            $transport_fees = (float)$transport_fees;
            $cremation_charge = (float)$cremation_charge;
            $wait_charge = (float)$wait_charge;
            $mileage_fees = (float)$mileage_fees;
            $other_charge_1 = (float)$other_charge_1;
            $other_charge_2 = (float)$other_charge_2;
            $other_charge_3 = (float)$other_charge_3;
            $other_charge_4 = (float)$other_charge_4;
            $total_charge = (float)$total_charge;
            $mileage = isset($mileage) && $mileage !== '' ? (float)$mileage : null;
            $mileage_rate = isset($mileage_rate) && $mileage_rate !== '' ? (float)$mileage_rate : null;
            $mileage_total_charge = isset($mileage_total_charge) && $mileage_total_charge !== '' ? (float)$mileage_total_charge : null;
            if ($mode === 'edit' && $transport_id) {
                // Update transport record
                $transportRepo->update(
                    $transport_id,
                    (int)$customerId,
                    $firmDate,
                    $accountType,
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
                    $assistantTransporter ? (int)$assistantTransporter : null,
                    $mileage,
                    $mileage_rate,
                    $mileage_total_charge
                );
                // Update charges record
                if ($existingCharges) {
                    $chargesData->update(
                        $existingCharges['id'],
                        $transport_id,
                        $removal_charge,
                        $pouch_charge,
                        $embalming_charge,
                        $transport_fees,
                        $cremation_charge,
                        $wait_charge,
                        $mileage_fees,
                        $other_charge_1,
                        $other_charge_1_description,
                        $other_charge_2,
                        $other_charge_2_description,
                        $other_charge_3,
                        $other_charge_3_description,
                        $other_charge_4,
                        $other_charge_4_description,
                        $total_charge
                    );
                } else {
                    $chargesData->create(
                        $transport_id,
                        $removal_charge,
                        $pouch_charge,
                        $embalming_charge,
                        $transport_fees,
                        $cremation_charge,
                        $wait_charge,
                        $mileage_fees,
                        $other_charge_1,
                        $other_charge_1_description,
                        $other_charge_2,
                        $other_charge_2_description,
                        $other_charge_3,
                        $other_charge_3_description,
                        $other_charge_4,
                        $other_charge_4_description,
                        $total_charge
                    );
                }
                // Update decedent table with matching transport_id
                $decedentRepo->updateByTransportId(
                    $transport_id,
                    $decedentFirstName,
                    $decedentLastName,
                    $decedentEthnicity,
                    $decedentGender
                );
            } else {
                $transport_id = $transportRepo->create(
                    (int)$customerId,
                    $firmDate,
                    $accountType,
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
                    $assistantTransporter ? (int)$assistantTransporter : null,
                    $mileage,
                    $mileage_rate,
                    $mileage_total_charge
                );
                // Create charges record
                $chargesData->create(
                    $transport_id,
                    $removal_charge,
                    $pouch_charge,
                    $embalming_charge,
                    $transport_fees,
                    $cremation_charge,
                    $wait_charge,
                    $mileage_fees,
                    $other_charge_1,
                    $other_charge_1_description,
                    $other_charge_2,
                    $other_charge_2_description,
                    $other_charge_3,
                    $other_charge_3_description,
                    $other_charge_4,
                    $other_charge_4_description,
                    $total_charge
                );
                // Check if decedent record exists for this transport_id
                $existingDecedent = $db->query("SELECT * FROM decedent WHERE transport_id = ?", [$transport_id]);
                if (empty($existingDecedent)) {
                    $decedentRepo->insertByTransportId(
                        $transport_id,
                        $decedentFirstName,
                        $decedentLastName,
                        $decedentEthnicity,
                        $decedentGender
                    );
                } else {
                    $decedentRepo->updateByTransportId(
                        $transport_id,
                        $decedentFirstName,
                        $decedentLastName,
                        $decedentEthnicity,
                        $decedentGender
                    );
                }
            }
            $success = true;
            // Clear form fields after successful add, like customer-edit.php and coroner-edit.php
            if ($mode !== 'edit') {
                $customerId = $firmDate = $accountType = $originLocation = $destinationLocation = $permitNumber = $tagNumber = '';
                $decedentFirstName = $decedentMiddleName = $decedentLastName = $decedentEthnicity = $decedentGender = '';
                $coronerName = $pouchType = $primaryTransporter = $assistantTransporter = '';
                $callTime = $arrivalTime = $departureTime = $deliveryTime = '';
                $removal_charge = $pouch_charge = $embalming_charge = $transport_fees = $cremation_charge = $wait_charge = $mileage_fees = $other_charge_1 = $other_charge_2 = $other_charge_3 = $other_charge_4 = $total_charge = '';
                $other_charge_1_description = $other_charge_2_description = $other_charge_3_description = $other_charge_4_description = '';
                $mileage = $mileage_rate = $mileage_total_charge = '';
                $originLocationId = $destinationLocationId = '';
                $transitPermitNumber = '';
            }
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
    <title>Transport Edit - DispatchBase</title>
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

                                <?php include('mileage-edit.php'); ?>

                                <?php include('charges-edit.php'); ?>


                                <!-- Hidden fields for all other transport columns with default values -->
                                <input type="hidden" name="decedent_first_name" value="<?= htmlspecialchars($decedentFirstName) ?>" />
                                <input type="hidden" name="decedent_middle_name" value="<?= htmlspecialchars($decedentMiddleName) ?>" />
                                <input type="hidden" name="decedent_last_name" value="<?= htmlspecialchars($decedentLastName) ?>" />
                                <input type="hidden" name="decedent_ethnicity" value="<?= htmlspecialchars($decedentEthnicity) ?>" />
                                <input type="hidden" name="decedent_gender" value="<?= htmlspecialchars($decedentGender) ?>" />
                                <input type="hidden" name="permit_number" value="<?= htmlspecialchars($permitNumber) ?>" />
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
</script>


</body>
</html>
