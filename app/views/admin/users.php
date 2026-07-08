<?php
$title = 'Kelola User';
$pageTitle = '👥 Kelola User';
$pageSubtitle = 'Tambah dan kelola akun user';
$activePage = 'users';
ob_start();
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<!-- Form Tambah User -->
<div class="card">
    <div class="card-header">
        <h3>➕ Tambah User Baru</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>public/index.php?route=users_create" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="sales">🟢 Sales</option>
                        <option value="admin">🔴 Admin</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="photo">Foto Profile</label>
                <div class="photo-upload">
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <label for="photo" class="photo-label">📸 Upload Foto Profile</label>
                    <div class="photo-preview"></div>
                </div>
            </div>
            <button type="submit" class="btn-primary">💾 Tambah User</button>
        </form>
    </div>
</div>

<!-- Daftar User -->
<div class="card">
    <div class="card-header">
        <h3>📋 Daftar User</h3>
        <span style="font-size:0.8rem; color:var(--gray-400);">
            Total: <?= count($data['users']) ?> user
        </span>
    </div>
    <div class="card-body">
        <?php if (!empty($data['users'])): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['users'] as $user): ?>
                            <tr>
                                <td>
                                    <?php 
                                    $photoUrl = $user['photo'] ?? 'default.jpg';
                                    if (empty($photoUrl) || $photoUrl === 'default.jpg') {
                                        $photoUrl = BASE_URL . 'uploads/photos/default.jpg';
                                    }
                                    ?>
                                    <img src="<?= htmlspecialchars($photoUrl) ?>" 
                                         alt="Foto" 
                                         class="user-avatar"
                                         onerror="this.src='<?= BASE_URL ?>uploads/photos/default.jpg'">
                                </td>
                                <td><strong><?= htmlspecialchars($user['name']) ?></strong></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><span class="role-badge <?= $user['role'] ?>"><?= $user['role'] ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= BASE_URL ?>public/index.php?route=users_edit&id=<?= $user['id'] ?>" class="btn-edit">✏️ Edit</a>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <a href="<?= BASE_URL ?>public/index.php?route=users_delete&id=<?= $user['id'] ?>" 
                                               class="btn-danger delete-action"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">🗑️ Hapus</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">👤</div>
                <p>Belum ada user terdaftar.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once '../app/views/layouts/main.php';
?>