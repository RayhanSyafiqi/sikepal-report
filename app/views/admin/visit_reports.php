<?php
$title = 'Semua Laporan Visit';
$pageTitle = '📋 Semua Laporan Visit';
$pageSubtitle = 'Daftar semua laporan kunjungan';
$activePage = 'visit_reports';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h3>📋 Semua Laporan Visit</h3>
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
                <p>Belum ada laporan visit.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once '../app/views/layouts/main.php';
?>