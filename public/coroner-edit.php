<?php
require_once 'database/CoronerData.php';
require_once 'database/Database.php';

$db = new Database();
$coronerRepo = new CoronerData($db);

$mode = $_GET['mode'] ?? 'add';
$id = $_GET['id'] ?? null;
$success = false;
$error = '';

if ($mode === 'edit' && $id && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $coroner = $coronerRepo->findByCoronerNumber($id);
    if ($coroner) {
        $coronerName = $coroner['cooroner_name'] ?? '';
        $phoneNumber = $coroner['phone_number'] ?? '';
        $emailAddress = $coroner['email_address'] ?? '';
        $address1 = $coroner['address_1'] ?? '';
        $address2 = $coroner['address_2'] ?? '';
        $city = $coroner['city'] ?? '';
        $state = $coroner['state'] ?? '';
        $zip = $coroner['zip'] ?? '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $coronerName = trim($_POST['cooroner_name'] ?? '');
    $phoneNumber = trim($_POST['phone_number'] ?? '');
    $emailAddress = trim($_POST['email_address'] ?? '');
    $address1 = trim($_POST['address_1'] ?? '');
    $address2 = trim($_POST['address_2'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zip = trim($_POST['zip'] ?? '');

    // Ensure all optional fields are blank if not set
    $phoneNumber = $phoneNumber ?: '';
    $address1 = $address1 ?: '';
    $address2 = $address2 ?: '';
    $city = $city ?: '';
    $zip = $zip ?: '';

    // Basic validation
    if ($coronerName && $state && $emailAddress && $city) {
        if ($mode === 'add') {
            try {
                $coronerRepo->create(
                    $coronerName,
                    $phoneNumber,
                    $emailAddress,
                    $address1,
                    $address2,
                    $city,
                    $state,
                    $zip
                );
                $success = true;
            } catch (Exception $e) {
                $error = 'Error adding coroner: ' . htmlspecialchars($e->getMessage());
            }
        } elseif ($mode === 'edit' && $id) {
            try {
                $coronerRepo->update(
                    $id,
                    $coronerName,
                    $phoneNumber,
                    $emailAddress,
                    $address1,
                    $address2,
                    $city,
                    $state,
                    $zip
                );
                $success = true;
            } catch (Exception $e) {
                $error = 'Error updating coroner: ' . htmlspecialchars($e->getMessage());
            }
        } elseif ($mode === 'delete' && $id) {
            try {
                $coronerRepo->delete($id);
                header('Location: coroner-list.php?success=deleted');
                exit;
            } catch (Exception $e) {
                $error = 'Error deleting coroner: ' . htmlspecialchars($e->getMessage());
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
    <title>Add Coroner - DispatchBase</title>
    <link href="css/styles.css" rel="stylesheet" />
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
                        <div class="card-header">Add Coroner</div>
                        <div class="card-body">
                            <?php if ($success): ?>
                                <div class="alert alert-success" role="alert">
                                    Coroner saved successfully!
                                </div>
                            <?php elseif ($error): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $error ?>
                                </div>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="row form-section">
                                    <div class="col-md-6">
                                        <label for="cooroner_name" class="form-label required">Coroner Name</label>
                                        <input type="text" class="form-control" id="cooroner_name" name="cooroner_name" value="<?= htmlspecialchars($coronerName ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($phoneNumber ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row form-section">
                                    <div class="col-md-6">
                                        <label for="address_1" class="form-label">Address 1</label>
                                        <input type="text" class="form-control" id="address_1" name="address_1" value="<?= htmlspecialchars($address1 ?? '') ?>">
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
                                        <input type="text" class="form-control" id="state" name="state" value="<?= htmlspecialchars($state ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="zip" class="form-label">Zip</label>
                                        <input type="text" class="form-control" id="zip" name="zip" value="<?= htmlspecialchars($zip ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row form-section">
                                    <div class="col-md-6">
                                        <label for="email_address" class="form-label required">Email Address</label>
                                        <input type="email" class="form-control" id="email_address" name="email_address" value="<?= htmlspecialchars($emailAddress ?? '') ?>" required>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="submit" class="btn btn-primary"><?= $mode === 'edit' ? 'Update' : 'Add' ?> Coroner</button>
                                    <?php if ($mode === 'edit'): ?>
                                        <a href="coroner-edit.php?mode=delete&id=<?= htmlspecialchars($id) ?>" class="btn btn-secondary">Delete Coroner</a>
                                    <?php endif; ?>
                                </div>
                            </form>
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
</body>
</html>
