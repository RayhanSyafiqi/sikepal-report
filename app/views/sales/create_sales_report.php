<?php
$title = 'Laporan Penjualan Onigiri';
$pageTitle = '🍙 Laporan Penjualan Onigiri';
$pageSubtitle = 'Input data penjualan onigiri hari ini';
$activePage = 'sales_create';
ob_start();
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3>📝 Form Laporan Penjualan</h3>
        <span style="font-size:0.8rem; color:var(--gray-400);">📅 <?= date('d F Y') ?></span>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>public/index.php?route=sales_create" enctype="multipart/form-data">
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
            
            <div style="background:var(--gray-50); border-radius:var(--radius); padding:1.25rem; margin-bottom:1.25rem;">
                <h4 style="margin-bottom:0.75rem; color:var(--gray-700);">🍙 Detail Penjualan Onigiri</h4>
                <p style="font-size:0.85rem; color:var(--gray-400); margin-bottom:1rem;">
                    💰 Harga per unit: Rp <?= number_format($data['price_per_unit'], 0, ',', '.') ?>
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tuna_mayo">🐟 Tuna Mayo</label>
                        <input type="number" id="tuna_mayo" name="tuna_mayo" min="0" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="tuna_pedas">🌶️ Tuna Pedas</label>
                        <input type="number" id="tuna_pedas" name="tuna_pedas" min="0" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="ayam_mayo">🐔 Ayam Mayo</label>
                        <input type="number" id="ayam_mayo" name="ayam_mayo" min="0" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="ayam_pedas">🌶️ Ayam Pedas</label>
                        <input type="number" id="ayam_pedas" name="ayam_pedas" min="0" value="0" required>
                    </div>
                </div>
                
                <div style="display:flex; gap:2rem; padding:0.75rem; background:var(--white); border-radius:var(--radius); margin-top:0.5rem;">
                    <div><strong>📦 Total:</strong> <span id="totalDisplay">0</span> onigiri</div>
                    <div><strong>💰 Total:</strong> Rp <span id="revenueDisplay">0</span></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="photo">📸 Foto</label>
                <div class="photo-upload">
                    <input type="file" id="photo" name="photo" accept="image/*" capture="environment">
                    <label for="photo" class="photo-label">📸 Ambil Foto atau Upload</label>
                    <div class="photo-preview"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="notes">📝 Catatan</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Catatan tambahan..."></textarea>
            </div>
            
            <div class="form-actions">
                <a href="<?= BASE_URL ?>public/index.php?route=dashboard" class="btn-secondary">⬅️ Batal</a>
                <button type="submit" class="btn-primary">📤 Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('change', calculateTotal);
    input.addEventListener('input', calculateTotal);
});

function calculateTotal() {
    const price = <?= $data['price_per_unit'] ?>;
    const tunaMayo = parseInt(document.getElementById('tuna_mayo').value) || 0;
    const tunaPedas = parseInt(document.getElementById('tuna_pedas').value) || 0;
    const ayamMayo = parseInt(document.getElementById('ayam_mayo').value) || 0;
    const ayamPedas = parseInt(document.getElementById('ayam_pedas').value) || 0;
    
    const total = tunaMayo + tunaPedas + ayamMayo + ayamPedas;
    const revenue = total * price;
    
    document.getElementById('totalDisplay').textContent = total;
    document.getElementById('revenueDisplay').textContent = revenue.toLocaleString('id-ID');
}
</script>

<?php
$content = ob_get_clean();
require_once '../app/views/layouts/main.php';
?>