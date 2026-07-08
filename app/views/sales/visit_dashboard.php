<?php
$title = 'Laporan Visit';
$pageTitle = '📋 Laporan Visit';
$pageSubtitle = 'Daftar kunjungan hari ini';
$activePage = 'visit';
ob_start();
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<!-- Action Button -->
<div style="margin-bottom:1.5rem;">
    <a href="<?= BASE_URL ?>public/index.php?route=visit_create" class="btn-primary">
        📋 Buat Laporan Visit
    </a>
    <a href="<?= BASE_URL ?>public/index.php?route=dashboard" class="btn-secondary">
        ⬅️ Kembali ke Dashboard
    </a>
</div>

<!-- Visit Reports List -->
<div class="card">
    <div class="card-header">
        <h3>📋 Laporan Visit Hari Ini</h3>
        <span style="font-size:0.8rem; color:var(--gray-400);">
            <?= date('d F Y') ?>
        </span>
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
                        <p><strong>👤 PIC:</strong> <?= htmlspecialchars($report['pic_name']) ?></p>
                        <p><strong>📱 Telepon:</strong> <?= htmlspecialchars($report['pic_phone']) ?></p>
                        <?php if ($report['photo'] !== 'default.jpg'): ?>
                            <img src="<?= BASE_URL ?>uploads/photos/<?= $report['photo'] ?>" alt="Foto Visit" class="report-photo">
                        <?php endif; ?>
                        <?php if (!empty($report['google_maps_link'])): ?>
                            <a href="<?= htmlspecialchars($report['google_maps_link']) ?>" target="_blank" class="map-link">
                                📍 Lihat di Google Maps
                            </a>
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
                <p>Belum ada laporan visit hari ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once '../app/views/layouts/main.php';
?>