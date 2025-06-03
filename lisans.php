<?php
// lisans.php - Ana dosya
session_start();

// Dosyaları dahil et
require_once 'config.php';
require_once 'models/LicenseModel.php';
require_once 'controllers/LicenseController.php';

// Basit routing
$action = $_GET['action'] ?? 'index';
$controller = new LicenseController();

switch ($action) {
    case 'generate':
        $controller->generate();
        break;
    case 'validate':
        $controller->validate();
        break;
    case 'update_status':
        $controller->updateStatus();
        break;
    default:
        $controller->index();
        break;
}
?>