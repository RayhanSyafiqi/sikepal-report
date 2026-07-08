<?php
$title = 'Dashboard Admin';
$pageTitle = '📊 Dashboard Admin';
$pageSubtitle = 'Laporan penjualan hari ini';
$activePage = 'dashboard';
ob_start();
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card" style="border-left-color: #EF4444;">
        <div class="stat-card-content">
            <div class="stat-card-number"><?= number_format($data['stats']['total_transactions'] ?? 0) ?></div>
            <div class="stat-card-label">📝 Total Transaksi</div>
        </div>
        <div class="stat-card-icon">📊</div>
    </div>
    <div class="stat-card" style="border-left-color: #3B82F6;">
        <div class="stat-card-content">
            <div class="stat-card-number"><?= number_format($data['stats']['total_sales'] ?? 0) ?></div>
            <div class="stat-card-label">🍙 Total Onigiri Terjual</div>
        </div>
        <div class="stat-card-icon">🍙</div>
    </div>
    <div class="stat-card" style="border-left-color: #10B981;">
        <div class="stat-card-content">
            <div class="stat-card-number">Rp <?= number_format($data['stats']['total_revenue'] ?? 0, 0, ',', '.') ?></div>
            <div class="stat-card-label">💰 Total Pendapatan</div>
        </div>
        <div class="stat-card-icon">💰</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>📊 Detail Penjualan Per Varian</h3>
        <span style="font-size:0.8rem; color:var(--gray-400);"><?= date('d F Y') ?></span>
    </div>
    <div class="card-body">
        <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr);">
            <div class="stat-card" style="border-left-color: #F59E0B;">
                <div class="stat-card-content">
                    <div class="stat-card-number" style="font-size:1.2rem;"><?= number_format($data['stats']['total_tuna_mayo'] ?? 0) ?></div>
                    <div class="stat-card-label">🐟 Tuna Mayo</div>
                </div>
            </div>
            <div class="stat-card" style="border-left-color: #EF4444;">
                <div class="stat-card-content">
                    <div class="stat-card-number" style="font-size:1.2rem;"><?= number_format($data['stats']['total_tuna_pedas'] ?? 0) ?></div>
                    <div class="stat-card-label">🌶️ Tuna Pedas</div>
                </div>
            </div>
            <div class="stat-card" style="border-left-color: #10B981;">
                <div class="stat-card-content">
                    <div class="stat-card-number" style="font-size:1.2rem;"><?= number_format($data['stats']['total_ayam_mayo'] ?? 0) ?></div>
                    <div class="stat-card-label">🐔 Ayam Mayo</div>
                </div>
            </div>
            <div class="stat-card" style="border-left-color: #8B5CF6;">
                <div class="stat-card-content">
                    <div class="stat-card-number" style="font-size:1.2rem;"><?= number_format($data['stats']['total_ayam_pedas'] ?? 0) ?></div>
                    <div class="stat-card-label">🌶️ Ayam Pedas</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>📋 Laporan Penjualan Hari Ini</h3>
        <span style="font-size:0.8rem; color:var(--gray-400);"><?= date('d F Y') ?></span>
    </div>
    <div class="card-body">
        <?php if (!empty($data['reports'])): ?>
            <?php foreach ($data['reports'] as $report): ?>
                <div class="report-card">
                    <div class="report-header">
                        <h4>🏪 <?= htmlspecialchars($report['location_name']) ?></h4>
                        <span class="report-time">⏰ <?= date('H:i', strtotime($report['created_at'])) ?></span>
                    </div>
                    <div class="report-body">
                        <p><strong>👤 Sales:</strong> <?= htmlspecialchars($report['sales_name']) ?></p>
                        <p><strong>👤 PIC:</strong> <?= htmlspecialchars($report['pic_name']) ?></p>
                        <p><strong>📱 Telepon:</strong> <?= htmlspecialchars($report['pic_phone']) ?></p>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin:0.5rem 0; padding:0.75rem; background:var(--gray-50); border-radius:var(--radius);">
                            <div>🐟 Tuna Mayo: <strong><?= $report['tuna_mayo'] ?></strong></div>
                            <div>🌶️ Tuna Pedas: <strong><?= $report['tuna_pedas'] ?></strong></div>
                            <div>🐔 Ayam Mayo: <strong><?= $report['ayam_mayo'] ?></strong></div>
                            <div>🌶️ Ayam Pedas: <strong><?= $report['ayam_pedas'] ?></strong></div>
                        </div>
                        <p><strong>📦 Total:</strong> <?= $report['total_sales'] ?> onigiri</p>
                        <p><strong>💰 Total:</strong> Rp <?= number_format($report['total_revenue'], 0, ',', '.') ?></p>
                        <?php if ($report['photo'] !== 'default.jpg'): ?>
                            <img src="<?= BASE_URL ?>uploads/photos/<?= $report['photo'] ?>" alt="Foto" class="report-photo">
                        <?php endif; ?>
                        <?php if (!empty($report['notes'])): ?>
                            <div class="report-notes"><?= nl2br(htmlspecialchars($report['notes'])) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p>Belum ada laporan penjualan hari ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once '../app/views/layouts/main.php';
?>