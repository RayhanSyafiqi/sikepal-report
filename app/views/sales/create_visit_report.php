<?php
$title = 'Buat Laporan Visit';
$pageTitle = '📋 Buat Laporan Visit';
$pageSubtitle = 'Input data kunjungan hari ini';
$activePage = 'visit_create';
ob_start();
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3>📝 Form Laporan Visit</h3>
        <span style="font-size:0.8rem; color:var(--gray-400);">
            📅 <?= date('d F Y') ?>
        </span>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>public/index.php?route=visit_create" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="location_name">🏪 Nama Lokasi/Toko *</label>
                    <input type="text" id="location_name" name="location_name" placeholder="Contoh: Toko Sembako Jaya" required>
                </div>
                <div class="form-group">
                    <label for="pic_name">👤 Nama PIC *</label>
                    <input type="text" id="pic_name" name="pic_name" placeholder="Nama kontak" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="pic_phone">📱 Nomor PIC *</label>
                <input type="tel" id="pic_phone" name="pic_phone" placeholder="081234567890" required>
            </div>
            
            <div class="form-group">
                <label for="google_maps_link">📍 Link Google Maps</label>
                <input type="url" id="google_maps_link" name="google_maps_link" placeholder="https://maps.google.com/...">
            </div>
            
            <div class="form-group">
                <label for="photo">📸 Foto</label>
                <div class="photo-upload">
                    <input type="file" id="photo" name="photo" accept="image/*" capture="environment">
                    <label for="photo" class="photo-label">
                        📸 Ambil Foto atau Upload
                    </label>
                    <div class="photo-preview"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="notes">📝 Catatan</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Catatan kunjungan..."></textarea>
            </div>
            
            <div class="form-actions">
                <a href="<?= BASE_URL ?>public/index.php?route=visit" class="btn-secondary">⬅️ Batal</a>
                <button type="submit" class="btn-primary">📤 Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once '../app/views/layouts/main.php';
?>