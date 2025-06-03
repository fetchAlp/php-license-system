<?php
// controllers/LicenseController.php
class LicenseController {
    private $model;
    
    public function __construct() {
        $this->model = new LicenseModel();
        $this->model->createTable(); // Tablo yoksa oluştur
    }
    
    public function index() {
        $licenses = $this->model->getAllLicenses();
        include 'views/dashboard.php';
    }
    
    public function generate() {
        $success = $error = '';
        
        if ($_POST) {
            $productName = $_POST['product_name'] ?? '';
            $customerEmail = $_POST['customer_email'] ?? '';
            $expiryDays = $_POST['expiry_days'] ?? null;
            
            if ($productName && $customerEmail) {
                $licenseKey = $this->model->generateLicense($productName, $customerEmail, $expiryDays);
                if ($licenseKey) {
                    $success = "Lisans başarıyla oluşturuldu: " . $licenseKey;
                } else {
                    $error = "Lisans oluşturulamadı!";
                }
            } else {
                $error = "Tüm alanları doldurun!";
            }
        }
        include 'views/generate.php';
    }
    
    public function validate() {
        $validation_result = null;
        
        if ($_POST || $_GET) {
            $licenseKey = $_POST['license_key'] ?? $_GET['license_key'] ?? '';
            $domain = $_POST['domain'] ?? $_GET['domain'] ?? null;
            
            if ($licenseKey) {
                $result = $this->model->validateLicense($licenseKey, $domain);
                
                // API çağrısı ise JSON döndür
                if (isset($_GET['api']) || isset($_POST['api'])) {
                    header('Content-Type: application/json');
                    echo json_encode($result);
                    exit;
                }
                
                $validation_result = $result;
            } else {
                $validation_result = ['valid' => false, 'message' => 'Lisans anahtarı gerekli'];
            }
        }
        include 'views/validate.php';
    }
    
    public function updateStatus() {
        if ($_POST) {
            $licenseKey = $_POST['license_key'] ?? '';
            $status = $_POST['status'] ?? '';
            
            if ($licenseKey && $status) {
                $this->model->updateStatus($licenseKey, $status);
            }
        }
        header('Location: ?action=index');
    }
}
?>