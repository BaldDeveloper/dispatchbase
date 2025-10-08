<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../database/PouchData.php';
require_once __DIR__ . '/../database/Database.php';

$db = new Database();
$pouchRepo = new PouchData($db);

$mode = $_GET['mode'] ?? 'add';
$id = $_GET['id'] ?? null;
$pouchType = '';
$success = false;
$error = '';

if ($mode === 'edit' && $id && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $pouch = $pouchRepo->findById((int)$id);
    if ($pouch) {
        $pouchType = $pouch['pouch_type'] ?? '';
    } else {
        $error = 'Pouch not found.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_pouch']) && $mode === 'edit' && $id) {
    try {
        $pouchRepo->delete((int)$id);
        $success = 'deleted';
        $pouchType = '';
    } catch (Exception $e) {
        $error = 'Error deleting pouch: ' . htmlspecialchars($e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pouchType = trim($_POST['pouch_type'] ?? '');
    if ($pouchType) {
        // Prevent duplicate pouch_type on add
        if (($mode === 'add' && $pouchRepo->findByType($pouchType)) ||
            ($mode === 'edit' && $pouchRepo->findByType($pouchType) && $pouchRepo->findByType($pouchType)['pouch_id'] != $id)) {
            $error = 'A pouch with this type already exists.';
        } else {
            try {
                if ($mode === 'edit' && $id) {
                    $pouchRepo->update((int)$id, $pouchType);
                    $success = true;
                } else {
                    $pouchRepo->create($pouchType);
                    $success = true;
                    $pouchType = '';
                }
            } catch (Exception $e) {
                $error = 'Error saving pouch: ' . htmlspecialchars($e->getMessage());
            }
        }
    } else {
        $error = 'Pouch type is required.';
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
    <title>Pouch Type - DispatchBase</title>
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
                            <h4><?= $mode === 'edit' ? 'Edit' : 'Add' ?> Pouch Type</h4>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main page content-->
            <div class="container-xl px-4 mt-n-custom-6">
                <div class="card mb-4 w-100">
                    <div class="card-header"><?= $mode === 'edit' ? 'Edit' : 'Add' ?> Pouch Type</div>
                    <div class="card-body">
                        <?php if ($success === 'deleted'): ?>
                            <div class="alert alert-success" role="alert">
                                Pouch deleted successfully!
                            </div>
                        <?php elseif ($success): ?>
                            <div class="alert alert-success" role="alert">
                                Pouch saved successfully!
                            </div>
                        <?php elseif ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($success !== 'deleted'): ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="pouch_type" class="form-label required">Pouch Type</label>
                                    <input type="text" class="form-control" id="pouch_type" name="pouch_type" value="<?= htmlspecialchars($pouchType ?? '') ?>" required maxlength="100">
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="submit" class="btn btn-primary"><?= $mode === 'edit' ? 'Update' : 'Add' ?> Pouch Type</button>
                                    <?php if ($mode === 'edit'): ?>
                                        <button type="submit" name="delete_pouch" value="1" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete this pouch type?');">Delete Pouch Type</button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        <?php endif; ?>
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
