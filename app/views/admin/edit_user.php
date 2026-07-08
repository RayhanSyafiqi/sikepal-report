<?php
$title = 'Edit User';
$pageTitle = '✏️ Edit User';
$pageSubtitle = 'Edit data user: ' . htmlspecialchars($data['user']['name']);
$activePage = 'users';
ob_start();
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3>📝 Edit Data User</h3>
        <span style="font-size:0.8rem; color:var(--gray-400);">
            ID: #<?= $data['user']['id'] ?>
        </span>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>public/index.php?route=users_edit&id=<?= $data['user']['id'] ?>" enctype="multipart/form-data" id="editUserForm">
            
            <div class="form-group">
                <label for="name">👤 Nama Lengkap <span style="color: #EF4444;">*</span></label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="<?= htmlspecialchars($data['user']['name']) ?>" 
                       placeholder="Masukkan nama lengkap" 
                       required
                       minlength="3"
                       maxlength="100">
                <small style="color: var(--gray-400);">Minimal 3 karakter</small>
            </div>
            <div class="form-group">
                <label for="email">📧 Email</label>
                <div style="position: relative;">
                    <input type="email" 
                           id="email" 
                           value="<?= htmlspecialchars($data['user']['email']) ?>" 
                           disabled 
                           style="background: #f1f5f9; cursor: not-allowed; padding-right: 40px;">
                    <span style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 0.8rem;">
                        🔒
                    </span>
                </div>
                <small style="color: var(--gray-400);">
                    📌 Email tidak dapat diubah karena digunakan sebagai identifier
                </small>
                <input type="hidden" name="email" value="<?= htmlspecialchars($data['user']['email']) ?>">
            </div>
            <div class="form-group">
                <label for="password">🔑 Password Baru</label>
                <div style="position: relative;">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Kosongkan jika tidak diubah"
                           minlength="6">
                    <button type="button" 
                            class="password-toggle" 
                            id="togglePassword" 
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94A3B8; font-size: 1.1rem; padding: 4px;">
                        👁️
                    </button>
                </div>
                <small style="color: var(--gray-400);">
                    📌 Isi hanya jika ingin mengganti password (minimal 6 karakter)
                </small>
            </div>
            <div class="form-group">
                <label for="role">🎯 Role <span style="color: #EF4444;">*</span></label>
                <select id="role" name="role" required>
                    <option value="sales" <?= $data['user']['role'] === 'sales' ? 'selected' : '' ?>>
                        🟢 Sales
                    </option>
                    <option value="admin" <?= $data['user']['role'] === 'admin' ? 'selected' : '' ?>>
                        🔴 Admin
                    </option>
                </select>
                <small style="color: var(--gray-400);">
                    📌 Role menentukan akses menu yang tersedia
                </small>
            </div>

            <div class="form-group">
                <label>📸 Foto Profile Saat Ini</label>
                <div style="margin: 0.75rem 0; display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
                    <?php 
                    // Tentukan URL foto yang benar
                    $photoUrl = $data['user']['photo'] ?? 'default.jpg';
                    // Jika URL tidak valid atau masih default, gunakan default
                    if (empty($photoUrl) || $photoUrl === 'default.jpg' || !filter_var($photoUrl, FILTER_VALIDATE_URL)) {
                        $photoUrl = '/sikepal-report/uploads/photos/default.jpg';
                    }
                    ?>
                    <img src="<?= htmlspecialchars($photoUrl) ?>" 
                         alt="Foto Profile" 
                         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--marigold); box-shadow: var(--shadow-md);"
                         id="currentPhoto"
                         onerror="this.src='/sikepal-report/uploads/photos/default.jpg'">
                    <div>
                        <div style="font-size: 0.85rem; color: var(--gray-600);">
                            <strong>Public ID:</strong> 
                            <span style="font-family: monospace; font-size: 0.75rem; color: var(--gray-400);">
                                <?= $data['user']['photo_public_id'] ?? 'Tidak ada' ?>
                            </span>
                        </div>
                        <div style="font-size: 0.8rem; color: var(--gray-400); margin-top: 0.25rem;">
                            <?php if (empty($data['user']['photo']) || $data['user']['photo'] === 'default.jpg'): ?>
                                ⚠️ Menggunakan foto default
                            <?php else: ?>
                                ✅ Foto tersimpan di Cloudinary
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($data['user']['photo']) && $data['user']['photo'] !== 'default.jpg'): ?>
                            <div style="font-size: 0.75rem; color: var(--gray-400); margin-top: 0.25rem; word-break: break-all;">
                                <strong>URL:</strong> <?= htmlspecialchars($data['user']['photo']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="photo">📸 Upload Foto Baru</label>
                <div class="photo-upload">
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <label for="photo" class="photo-label">
                        <span style="font-size: 1.5rem;">📤</span>
                        Klik untuk Upload Foto Baru
                    </label>
                    <div class="photo-preview" id="photoPreview"></div>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <small style="color: var(--gray-400);">
                        📌 Format: JPG, PNG, GIF, WEBP (Maks 5MB)
                    </small>
                    <small style="color: var(--gray-400);">
                        📌 Kosongkan jika tidak ingin mengubah foto
                    </small>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= BASE_URL ?>public/index.php?route=users" class="btn-secondary">
                    ⬅️ Kembali
                </a>
                <button type="submit" class="btn-primary" id="submitBtn">
                    💾 Update User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.textContent = type === 'text' ? '🙈' : '👁️';
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');
        const currentPhoto = document.getElementById('currentPhoto');
        
        if (photoInput && photoPreview) {
            photoInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.innerHTML = `
                            <div style="margin-top: 0.75rem;">
                                <p style="font-size: 0.8rem; color: var(--gray-500); margin-bottom: 0.25rem;">
                                    📸 Preview Foto Baru
                                </p>
                                <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: var(--radius); box-shadow: var(--shadow-sm); border: 2px solid var(--marigold);">
                                <p style="font-size: 0.75rem; color: var(--gray-400); margin-top: 0.25rem;">
                                    Nama: ${this.files[0].name} (${(this.files[0].size / 1024).toFixed(2)} KB)
                                </p>
                            </div>
                        `;
                    }
                    reader.readAsDataURL(this.files[0]);
                } else {
                    photoPreview.innerHTML = '';
                }
            });
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editUserForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name');
                const password = document.getElementById('password');
                const role = document.getElementById('role');
                let errors = [];
                
                // Validasi nama
                if (!name.value.trim()) {
                    errors.push('Nama lengkap harus diisi!');
                    name.style.borderColor = '#EF4444';
                } else if (name.value.trim().length < 3) {
                    errors.push('Nama lengkap minimal 3 karakter!');
                    name.style.borderColor = '#EF4444';
                } else {
                    name.style.borderColor = '';
                }
                
                // Validasi password (jika diisi)
                if (password.value.trim() && password.value.trim().length < 6) {
                    errors.push('Password minimal 6 karakter!');
                    password.style.borderColor = '#EF4444';
                } else {
                    password.style.borderColor = '';
                }
                
                // Validasi role
                if (!role.value) {
                    errors.push('Role harus dipilih!');
                    role.style.borderColor = '#EF4444';
                } else {
                    role.style.borderColor = '';
                }
                
                if (errors.length > 0) {
                    e.preventDefault();
                    alert('⚠️ ' + errors.join('\n'));
                    return false;
                }
                
                // Konfirmasi sebelum update
                return confirm('⚠️ Apakah Anda yakin ingin mengupdate data user ini?');
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            }, 4000);
        });
    });
</script>

<style>

    .photo-upload input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }
    
    .photo-upload .photo-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        border: 2px dashed var(--gray-300);
        border-radius: var(--radius);
        background: var(--gray-50);
        cursor: pointer;
        transition: var(--transition);
        font-weight: 500;
        color: var(--gray-500);
        gap: 0.5rem;
        min-height: 120px;
    }
    
    .photo-upload .photo-label:hover {
        border-color: var(--marigold);
        background: var(--gray-100);
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #94A3B8;
        font-size: 1.1rem;
        padding: 4px;
        border-radius: 50%;
        transition: var(--transition);
    }
    
    .password-toggle:hover {
        color: var(--marigold);
        background: rgba(234, 160, 35, 0.1);
    }
    
    @media (max-width: 480px) {
        .card-body {
            padding: 1rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions .btn-primary,
        .form-actions .btn-secondary {
            width: 100%;
            justify-content: center;
        }
        
        .photo-upload .photo-label {
            padding: 1rem;
            min-height: 80px;
            font-size: 0.9rem;
        }
    }
</style>

<?php
$content = ob_get_clean();
require_once '../app/views/layouts/main.php';
?>