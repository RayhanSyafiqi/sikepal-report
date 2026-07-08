
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            if (overlay) {
                overlay.classList.toggle('active');
            }
        });
    }
    
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
    
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('open');
            if (overlay) {
                overlay.classList.remove('active');
            }
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 4000);
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const photoInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    photoInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            const preview = this.closest('.photo-upload').querySelector('.photo-preview');
            if (this.files && this.files[0] && preview) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview Foto">`;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const deleteBtns = document.querySelectorAll('.btn-delete, .delete-action');
    deleteBtns.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if (!confirm('⚠️ Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan!')) {
                e.preventDefault();
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname + window.location.search;
    const navLinks = document.querySelectorAll('.sidebar-nav-item');
    
    navLinks.forEach(function(link) {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href)) {
            link.classList.add('active');
        }
    });
});