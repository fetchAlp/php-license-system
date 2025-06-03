<?php
// models/LicenseModel.php
class LicenseModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS licenses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            license_key VARCHAR(64) UNIQUE NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            customer_email VARCHAR(255) NOT NULL,
            status ENUM('active', 'inactive', 'expired') DEFAULT 'active',
            domain VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            expires_at TIMESTAMP NULL,
            last_check TIMESTAMP NULL
        )";
        return $this->db->exec($sql);
    }
    
    public function generateLicense($productName, $customerEmail, $expiryDays = null) {
        $licenseKey = $this->generateLicenseKey();
        $expiresAt = $expiryDays ? date('Y-m-d H:i:s', strtotime("+{$expiryDays} days")) : null;
        
        $sql = "INSERT INTO licenses (license_key, product_name, customer_email, expires_at) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute([$licenseKey, $productName, $customerEmail, $expiresAt])) {
            return $licenseKey;
        }
        return false;
    }
    
    public function validateLicense($licenseKey, $domain = null) {
        $sql = "SELECT * FROM licenses WHERE license_key = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$licenseKey]);
        $license = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$license) {
            return ['valid' => false, 'message' => 'Lisans bulunamadı'];
        }
        
        // Süre kontrolü
        if ($license['expires_at'] && strtotime($license['expires_at']) < time()) {
            $this->updateStatus($licenseKey, 'expired');
            return ['valid' => false, 'message' => 'Lisans süresi dolmuş'];
        }
        
        // Status kontrolü
        if ($license['status'] !== 'active') {
            return ['valid' => false, 'message' => 'Lisans aktif değil'];
        }
        
        // Domain kontrolü (isteğe bağlı)
        if ($domain && $license['domain'] && $license['domain'] !== $domain) {
            return ['valid' => false, 'message' => 'Domain eşleşmiyor'];
        }
        
        // İlk kez domain kaydı
        if ($domain && !$license['domain']) {
            $this->updateDomain($licenseKey, $domain);
        }
        
        // Son kontrol zamanını güncelle
        $this->updateLastCheck($licenseKey);
        
        return [
            'valid' => true, 
            'message' => 'Lisans geçerli',
            'license' => $license
        ];
    }
    
    public function getAllLicenses() {
        $sql = "SELECT * FROM licenses ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($licenseKey, $status) {
        $sql = "UPDATE licenses SET status = ? WHERE license_key = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $licenseKey]);
    }
    
    private function updateDomain($licenseKey, $domain) {
        $sql = "UPDATE licenses SET domain = ? WHERE license_key = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$domain, $licenseKey]);
    }
    
    private function updateLastCheck($licenseKey) {
        $sql = "UPDATE licenses SET last_check = NOW() WHERE license_key = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$licenseKey]);
    }
    
    private function generateLicenseKey() {
        return strtoupper(bin2hex(random_bytes(16)) . '-' . time());
    }
}
?>