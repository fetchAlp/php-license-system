<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisans Doğrula</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; }
        input { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .btn { background: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; }
        .btn:hover { background: #2563eb; }
        .result { padding: 20px; border-radius: 8px; margin-top: 20px; }
        .result.valid { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .result.invalid { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .nav-back { margin-bottom: 20px; }
        .nav-back a { color: #6b7280; text-decoration: none; }
        .nav-back a:hover { color: #374151; }
        .api-info { background: #f0f9ff; padding: 20px; border-radius: 8px; border: 1px solid #e0f2fe; }
        .code { background: #1f2937; color: #f9fafb; padding: 15px; border-radius: 6px; font-family: monospace; font-size: 13px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-back">
            <a href="?action=index">← Geri Dön</a>
        </div>
        
        <div class="card">
            <h2>Lisans Doğrula</h2>
            
            <form method="post">
                <div class="form-group">
                    <label>Lisans Anahtarı *</label>
                    <input type="text" name="license_key" required placeholder="Lisans anahtarını girin" value="<?= $_POST['license_key'] ?? '' ?>">
                </div>
                
                <div class="form-group">
                    <label>Domain (Opsiyonel)</label>
                    <input type="text" name="domain" placeholder="example.com" value="<?= $_POST['domain'] ?? '' ?>">
                </div>
                
                <button type="submit" class="btn">Doğrula</button>
            </form>
            
            <?php if (isset($validation_result)): ?>
                <div class="result <?= $validation_result['valid'] ? 'valid' : 'invalid' ?>">
                    <strong><?= $validation_result['valid'] ? '✓ Geçerli' : '✗ Geçersiz' ?></strong><br>
                    <?= $validation_result['message'] ?>
                    
                    <?php if ($validation_result['valid'] && isset($validation_result['license'])): ?>
                        <br><br>
                        <strong>Detaylar:</strong><br>
                        Ürün: <?= htmlspecialchars($validation_result['license']['product_name']) ?><br>
                        Email: <?= htmlspecialchars($validation_result['license']['customer_email']) ?><br>
                        <?php if ($validation_result['license']['expires_at']): ?>
                            Bitiş: <?= date('d.m.Y H:i', strtotime($validation_result['license']['expires_at'])) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="card api-info">
            <h3>API Kullanımı</h3>
            <p>Tema/plugin'inizden lisans doğrulamak için:</p>
            <div class="code">
POST <?= $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ?>?action=validate&amp;api=1
Content-Type: application/x-www-form-urlencoded

license_key=LISANS_ANAHTARI&amp;domain=DOMAIN
            </div>
            <br>
            <p><strong>Dönüş:</strong> JSON formatında validasyon sonucu</p>
        </div>
    </div>
</body>
</html>