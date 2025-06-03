<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisans Yönetim Sistemi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .nav { display: flex; gap: 15px; margin-top: 15px; }
        .nav a { padding: 8px 16px; background: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; }
        .nav a:hover { background: #2563eb; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .table th { background: #f9fafb; font-weight: 600; }
        .status { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; }
        .status.active { background: #dcfce7; color: #166534; }
        .status.inactive { background: #fef2f2; color: #991b1b; }
        .status.expired { background: #fef3c7; color: #92400e; }
        .license-key { font-family: monospace; background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .actions { display: flex; gap: 8px; }
        .btn { padding: 4px 8px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-success { background: #16a34a; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Lisans Yönetim Sistemi</h1>
            <div class="nav">
                <a href="?action=index">Dashboard</a>
                <a href="?action=generate">Lisans Oluştur</a>
                <a href="?action=validate">Lisans Doğrula</a>
            </div>
        </div>

        <div class="card">
            <h2>Lisanslar (<?= count($licenses) ?>)</h2>
            <?php if (!empty($licenses)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lisans Anahtarı</th>
                            <th>Ürün</th>
                            <th>Email</th>
                            <th>Domain</th>
                            <th>Durum</th>
                            <th>Bitiş</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($licenses as $license): ?>
                        <tr>
                            <td><span class="license-key"><?= substr($license['license_key'], 0, 20) ?>...</span></td>
                            <td><?= htmlspecialchars($license['product_name']) ?></td>
                            <td><?= htmlspecialchars($license['customer_email']) ?></td>
                            <td><?= $license['domain'] ?: '-' ?></td>
                            <td><span class="status <?= $license['status'] ?>"><?= ucfirst($license['status']) ?></span></td>
                            <td><?= $license['expires_at'] ? date('d.m.Y', strtotime($license['expires_at'])) : 'Süresiz' ?></td>
                            <td class="actions">
                                <form method="post" action="?action=update_status" style="display: inline;">
                                    <input type="hidden" name="license_key" value="<?= $license['license_key'] ?>">
                                    <?php if ($license['status'] === 'active'): ?>
                                        <input type="hidden" name="status" value="inactive">
                                        <button type="submit" class="btn btn-danger">Deaktif Et</button>
                                    <?php else: ?>
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="btn btn-success">Aktif Et</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Henüz lisans oluşturulmamış.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>