<?php
// example.php - Tema/Plugin'den API kullanımı örneği

// WordPress tema/plugin dosyanızda kullanım örneği
function check_license($license_key, $domain = null) {
    $api_url = 'https://siteadi/lisans.php?action=validate&api=1';
    
    $data = array(
        'license_key' => $license_key,
        'domain' => $domain ?: $_SERVER['HTTP_HOST']
    );
    
    // cURL ile API çağrısı
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200 && $response) {
        $result = json_decode($response, true);
        return $result;
    }
    
    return ['valid' => false, 'message' => 'API bağlantı hatası'];
}

// WordPress wp_remote_post ile kullanım
function check_license_wp($license_key, $domain = null) {
    $api_url = 'https://siteadi/lisans.php?action=validate&api=1';
    
    $response = wp_remote_post($api_url, array(
        'body' => array(
            'license_key' => $license_key,
            'domain' => $domain ?: $_SERVER['HTTP_HOST']
        ),
        'timeout' => 30
    ));
    
    if (is_wp_error($response)) {
        return ['valid' => false, 'message' => 'API bağlantı hatası'];
    }
    
    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);
    
    return $result ?: ['valid' => false, 'message' => 'Geçersiz API yanıtı'];
}

// Kullanım örnekleri
$license_result = check_license('XXXXXXXXXXXXXXXX-123456789');

if ($license_result['valid']) {
    echo "Lisans geçerli!";
    // Tema/plugin çalışmaya devam etsin
} else {
    echo "Lisans hatası: " . $license_result['message'];
    // Tema/plugin devre dışı kalsın
}

// Tema functions.php dosyasında kullanım örneği
function my_theme_license_check() {
    $license_key = get_option('my_theme_license_key');
    
    if (!$license_key) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Lütfen tema lisans anahtarınızı girin.</p></div>';
        });
        return false;
    }
    
    $result = check_license_wp($license_key);
    
    if (!$result['valid']) {
        add_action('admin_notices', function() use ($result) {
            echo '<div class="notice notice-error"><p>Lisans hatası: ' . $result['message'] . '</p></div>';
        });
        return false;
    }
    
    return true;
}

// Tema/plugin başlatılmadan önce kontrol et
if (!my_theme_license_check()) {
    // Lisans geçersizse temel özellikleri devre dışı bırak
    return;
}
?>