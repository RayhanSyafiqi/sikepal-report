<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sikepal Report' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="dashboard-page">
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay"></div>
    
    <aside class="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <span class="sidebar-brand-icon">🍙</span>
            <span class="sidebar-brand-text">Sikepal Report</span>
        </div>
        
        <!-- User Profile -->
        <div class="sidebar-user">
            <?php 
            $sidebarPhoto = $_SESSION['photo'] ?? 'default.jpg';
            if (empty($sidebarPhoto) || $sidebarPhoto === 'default.jpg') {
                $sidebarPhoto = BASE_URL . 'uploads/photos/default.jpg';
            }
            ?>
            <img src="<?= htmlspecialchars($sidebarPhoto) ?>" 
                 alt="Avatar" 
                 class="sidebar-user-avatar"
                 onerror="this.src='<?= BASE_URL ?>uploads/photos/default.jpg'">
            <div class="sidebar-user-info">
                <div class="sidebar-user-name"><?= htmlspecialchars($_SESSION['name'] ?? 'User') ?></div>
                <div class="sidebar-user-role"><?= htmlspecialchars($_SESSION['role'] ?? 'Guest') ?></div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="sidebar-nav">
            <?php 
            $role = $_SESSION['role'] ?? '';
            
            if ($role === 'admin'): 
            ?>
                <div class="sidebar-nav-label">📊 Dashboard</div>
                <a href="<?= BASE_URL ?>public/index.php?route=dashboard" class="sidebar-nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">📊</span>
                    <span class="sidebar-nav-text">Dashboard</span>
                </a>
                
                <div class="sidebar-nav-label">🍙 Laporan Penjualan</div>
                <a href="<?= BASE_URL ?>public/index.php?route=sales_reports" class="sidebar-nav-item <?= $activePage === 'sales_reports' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">🍙</span>
                    <span class="sidebar-nav-text">Semua Penjualan</span>
                    <span class="sidebar-nav-badge">All</span>
                </a>
                
                <div class="sidebar-nav-label">📋 Laporan Visit</div>
                <a href="<?= BASE_URL ?>public/index.php?route=visit_reports" class="sidebar-nav-item <?= $activePage === 'visit_reports' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">📋</span>
                    <span class="sidebar-nav-text">Semua Visit</span>
                    <span class="sidebar-nav-badge">All</span>
                </a>
                
                <div class="sidebar-nav-label">⚙️ Manajemen</div>
                <a href="<?= BASE_URL ?>public/index.php?route=users" class="sidebar-nav-item <?= $activePage === 'users' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">👥</span>
                    <span class="sidebar-nav-text">Kelola User</span>
                </a>
                
            <?php elseif ($role === 'sales'): ?>
                <div class="sidebar-nav-label">📊 Dashboard</div>
                <a href="<?= BASE_URL ?>public/index.php?route=dashboard" class="sidebar-nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">📊</span>
                    <span class="sidebar-nav-text">Dashboard Penjualan</span>
                </a>
                
                <div class="sidebar-nav-label">🍙 Penjualan Onigiri</div>
                <a href="<?= BASE_URL ?>public/index.php?route=sales_create" class="sidebar-nav-item <?= $activePage === 'sales_create' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">➕</span>
                    <span class="sidebar-nav-text">Tambah Penjualan</span>
                </a>
                
                <div class="sidebar-nav-label">📋 Laporan Visit</div>
                <a href="<?= BASE_URL ?>public/index.php?route=visit" class="sidebar-nav-item <?= $activePage === 'visit' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">📋</span>
                    <span class="sidebar-nav-text">Daftar Visit</span>
                </a>
                <a href="<?= BASE_URL ?>public/index.php?route=visit_create" class="sidebar-nav-item <?= $activePage === 'visit_create' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">➕</span>
                    <span class="sidebar-nav-text">Tambah Visit</span>
                </a>
                
                <div class="sidebar-nav-label">👤 Akun</div>
                <a href="<?= BASE_URL ?>public/index.php?route=profile" class="sidebar-nav-item <?= $activePage === 'profile' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">👤</span>
                    <span class="sidebar-nav-text">Profile</span>
                </a>
                
            <?php else: ?>
                <div class="sidebar-nav-label">📊 Dashboard</div>
                <a href="<?= BASE_URL ?>public/index.php?route=dashboard" class="sidebar-nav-item">
                    <span class="sidebar-nav-icon">📊</span>
                    <span class="sidebar-nav-text">Dashboard</span>
                </a>
                <a href="<?= BASE_URL ?>public/index.php?route=logout" class="sidebar-nav-item">
                    <span class="sidebar-nav-icon">🚪</span>
                    <span class="sidebar-nav-text">Logout</span>
                </a>
            <?php endif; ?>
            
            <div class="sidebar-nav-label">🔒 Keamanan</div>
            <a href="<?= BASE_URL ?>public/index.php?route=logout" class="sidebar-nav-item" onclick="return confirm('Yakin ingin logout?')">
                <span class="sidebar-nav-icon">🚪</span>
                <span class="sidebar-nav-text">Logout</span>
            </a>
        </nav>
        
        <!-- Footer -->
        <div class="sidebar-footer">
            <div>
                <strong style="color: var(--marigold);">🍙 Sikepal Report</strong><br>
                v1.0.0<br>
                <span style="font-size: 0.6rem; color: var(--gray-600);">
                    <?= date('Y') ?> &copy; All Rights Reserved
                </span>
            </div>
        </div>
    </aside>
    
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="top-header-left">
                <button class="sidebar-toggle" aria-label="Toggle Sidebar">☰</button>
                <div class="top-header-title">
                    <h2><?= $pageTitle ?? 'Dashboard' ?></h2>
                    <p><?= $pageSubtitle ?? '' ?></p>
                </div>
            </div>
            
            <div class="top-header-right">
                <div class="top-header-actions">
                    <button class="btn-icon" title="Notifikasi" onclick="alert('📢 Tidak ada notifikasi baru')">🔔</button>
                    <button class="btn-icon" title="Bantuan" onclick="alert('📖 Hubungi admin untuk bantuan')">❓</button>
                    <?php 
                    $topPhoto = $_SESSION['photo'] ?? 'default.jpg';
                    if (empty($topPhoto) || $topPhoto === 'default.jpg') {
                        $topPhoto = BASE_URL . 'uploads/photos/default.jpg';
                    }
                    ?>
                    <img src="<?= htmlspecialchars($topPhoto) ?>" 
                         alt="Avatar" 
                         style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--marigold); margin-left: 0.5rem;"
                         onerror="this.src='<?= BASE_URL ?>uploads/photos/default.jpg'">
                </div>
            </div>
        </header>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <?= $content ?? '' ?>
        </div>
    </main>
    
    <script src="<?= BASE_URL ?>public/js/script.js"></script>
</body>
</html>