<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisans Oluştur</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; }
        input, select { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        input:focus, select:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .btn { background: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; }
        .btn:hover { background: #2563eb; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .nav-back { margin-bottom: 20px; }
        .nav-back a { color: #6b7280; text-decoration: none; }
        .nav-back a:hover { color: #374151; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-back">
            <a href="?action=index">← Geri Dön</a>
        </div>
        
        <div class="card">
            <h2>Yeni Lisans Oluştur</h2>
            
            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            
            <?php if (isset($error) && $error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label>Ürün Adı *</label>
                    <input type="text" name="product_name" required placeholder="Tema/Plugin adı">
                </div>
                
                <div class="form-group">
                    <label>Müşteri Email *</label>
                    <input type="email" name="customer_email" required placeholder="musteri@email.com">
                </div>
                
                <div class="form-group">
                    <label>Süre (Gün)</label>
                    <select name="expiry_days">
                        <option value="">Süresiz</option>
                        <option value="30">30 Gün</option>
                        <option value="90">90 Gün</option>
                        <option value="365">1 Yıl</option>
                        <option value="730">2 Yıl</option>
                    </select>
                </div>
                
                <button type="submit" class="btn">Lisans Oluştur</button>
            </form>
        </div>
    </div>
</body>
</html>