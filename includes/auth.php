<?php
// Authentication check for all protected pages
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

