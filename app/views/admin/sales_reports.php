<?php
$title = 'Semua Laporan Penjualan';
$pageTitle = '📊 Semua Laporan Penjualan';
$pageSubtitle = 'Ringkasan semua laporan penjualan onigiri';
$activePage = 'sales_reports';
ob_start();
?>

<!-- Stats Overview -->
<div class="stats-grid">
    <div class="stat-card" style="border-left-color: #EF4444;">
        <div class="stat-card-content">
            <div class="stat-card-number"><?= number_format($data['stats']['total_transactions'] ?? 0) ?></div>
            <div class="stat-card-label">📝 Total Transaksi</div>
        </div>
    </div>
    <div class="stat-card" style="border-left-color: #3B82F6;">
        <div class="stat-card-content">
            <div class="stat-card-number"><?= number_format($data['stats']['total_sales'] ?? 0) ?></div>
            <div class="stat-card-label">🍙 Total Onigiri Terjual</div>
        </div>
    </div>
    <div class="stat-card" style="border-left-color: #10B981;">
        <div class="stat-card-content">
            <div class="stat-card-number">Rp <?= number_format($data['stats']['total_revenue'] ?? 0, 0, ',', '.') ?></div>
            <div class="stat-card-label">💰 Total Pendapatan</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>📋 Semua Laporan Penjualan</h3>
        <span style="font-size:0.8rem; color:var(--gray-400);">
            Total: <?= count($data['reports']) ?> laporan
        </span>
    </div>
    <div class="card-body">
        <?php if (!empty($data['reports'])): ?>
            <?php foreach ($data['reports'] as $report): ?>
                <div class="report-card">
                    <div class="report-header">
                        <h4>🏪 <?= htmlspecialchars($report['location_name']) ?></h4>
                        <span class="report-time">📅 <?= date('d-m-Y H:i', strtotime($report['created_at'])) ?></span>
                    </div>
                    <div class="report-body">
                        <p><strong>👤 Sales:</strong> <?= htmlspecialchars($report['sales_name']) ?></p>
                        <p><strong>👤 PIC:</strong> <?= htmlspecialchars($report['pic_name']) ?></p>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin:0.5rem 0; padding:0.75rem; background:var(--gray-50); border-radius:var(--radius);">
                            <div>🐟 Tuna Mayo: <strong><?= $report['tuna_mayo'] ?></strong></div>
                            <div>🌶️ Tuna Pedas: <strong><?= $report['tuna_pedas'] ?></strong></div>
                            <div>🐔 Ayam Mayo: <strong><?= $report['ayam_mayo'] ?></strong></div>
                            <div>🌶️ Ayam Pedas: <strong><?= $report['ayam_pedas'] ?></strong></div>
                        </div>
                        <p><strong>📦 Total:</strong> <?= $report['total_sales'] ?> onigiri</p>
                        <p><strong>💰 Total:</strong> Rp <?= number_format($report['total_revenue'], 0, ',', '.') ?></p>
                        <?php if (!empty($report['notes'])): ?>
                            <div class="report-notes"><?= nl2br(htmlspecialchars($report['notes'])) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p>Belum ada laporan penjualan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once '../app/views/layouts/main.php';
?>