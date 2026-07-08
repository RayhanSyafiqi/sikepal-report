<?php
$title = 'Profile';
$pageTitle = '👤 Edit Profile';
$pageSubtitle = 'Update informasi profile Anda';
$activePage = 'profile';
ob_start();
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>public/index.php?route=profile_update" enctype="multipart/form-data">
            <div class="form-group" style="text-align: center;">
                <label>Foto Profile</label>
                <div style="margin: 0.5rem 0;">
                    <img src="<?= BASE_URL ?>uploads/photos/<?= $data['user']['photo'] ?>" 
                         alt="Foto Profile" 
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--marigold); box-shadow: var(--shadow-md);">
                </div>
                <div class="photo-upload" style="max-width: 300px; margin: 0 auto;">
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <label for="photo" class="photo-label" style="font-size:0.9rem; padding:0.8rem;">
                        📸 Ganti Foto
                    </label>
                    <div class="photo-preview"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($data['user']['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="<?= htmlspecialchars($data['user']['email']) ?>" disabled>
                <small style="color: var(--gray-400);">📌 Email tidak dapat diubah</small>
            </div>
            
            <div class="form-actions">
                <a href="<?= BASE_URL ?>public/index.php?route=dashboard" class="btn-secondary">⬅️ Kembali</a>
                <button type="submit" class="btn-primary">💾 Update Profile</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once '../app/views/layouts/main.php';
?>