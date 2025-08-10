<?php

$current_page = basename($_SERVER['PHP_SELF']);
?>

<button class="admin-toggle" id="adminToggle" aria-label="Toggle admin menu">
    <span class="toggle-icon">&lt;</span>
</button>

<div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <a href="<?= BASE_URL ?>/admin/index.php" class="logo">YAYD</a>
        <span>Admin</span>
        <button class="sidebar-close" id="sidebarClose" aria-label="Close menu">&times;</button>
    </div>
    <ul class="sidebar-nav">
        <li class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/index.php"><i class="fa fa-tachometer-alt"></i> Dasbor</a>
        </li>
        
        <li class="<?= ($current_page == 'kelola_donasi.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/kelola_donasi.php"><i class="fa fa-donate"></i> Kelola Donasi</a>
        </li>
        <li class="<?= ($current_page == 'kelola_distribusi.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/kelola_distribusi.php"><i class="fa fa-shipping-fast"></i> Distribusi Donasi</a>
        </li>
        <li class="<?= ($current_page == 'kelola_pengguna.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/kelola_pengguna.php"><i class="fa fa-users"></i> Kelola Pengguna</a>
        </li>

        <li class="<?= ($current_page == 'kelola_kegiatan.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/kelola_kegiatan.php"><i class="fa fa-calendar-alt"></i> Kelola Kegiatan</a>
        </li>
        
        <li class="<?= ($current_page == 'edit_profil_yayasan.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/edit_profil_yayasan.php"><i class="fa fa-info-circle"></i> Edit Profil Yayasan</a>
        </li>

        <li class="<?= ($current_page == 'laporan_keuangan.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/laporan_keuangan.php"><i class="fa fa-file-excel"></i> Laporan Keuangan</a>
        </li>
        <li class="<?= ($current_page == 'laporan_selesai.php') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/laporan_selesai.php"><i class="fa fa-file-alt"></i> Laporan Donasi</a>
        </li>
    </ul>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('adminToggle');
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('adminSidebarOverlay');
    const sidebarClose = document.getElementById('sidebarClose');
    const mainContent = document.querySelector('.admin-main-content');

    const burgerMenu = document.getElementById('burgerMenu');
    const navMenu = document.getElementById('navMenu');

    if (!toggle || !sidebar) {
        console.error('Essential admin sidebar elements not found!');
        return;
    }

    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        toggle.classList.remove('active');
        toggle.querySelector('.toggle-icon').innerHTML = '&lt;';
        document.body.style.overflow = '';
    } else {
        const isCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
        if (isCollapsed) {
            collapseSidebar();
        } else {
            toggle.querySelector('.toggle-icon').innerHTML = '&lt;';
        }
    }

    function closeBurgerMenu() {
        if (burgerMenu && navMenu) {
            burgerMenu.classList.remove('active');
            navMenu.classList.remove('active');
        }
    }

    function closeAdminSidebar() {
        collapseSidebar();
    }

    function expandSidebar() {
        const isMobile = window.innerWidth <= 768;
        
        if (!isMobile) {
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('expanded');
            toggle.classList.remove('active');
            toggle.querySelector('.toggle-icon').innerHTML = '&lt;';
            localStorage.setItem('adminSidebarCollapsed', 'false');
        } else {
            closeBurgerMenu();
            
            sidebar.classList.add('active');
            overlay.classList.add('active');
            toggle.classList.add('active');
            toggle.querySelector('.toggle-icon').innerHTML = '&gt;';
            document.body.style.overflow = 'hidden';
            
            document.dispatchEvent(new CustomEvent('adminSidebarOpened'));
        }
    }

    function collapseSidebar() {
        const isMobile = window.innerWidth <= 768;
        
        if (!isMobile) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
            toggle.classList.add('active');
            toggle.querySelector('.toggle-icon').innerHTML = '&gt;';
            localStorage.setItem('adminSidebarCollapsed', 'true');
        } else {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            toggle.classList.remove('active');
            toggle.querySelector('.toggle-icon').innerHTML = '&lt;';
            document.body.style.overflow = '';
        }
    }

    if (burgerMenu) {
        burgerMenu.addEventListener('click', function() {
            if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                closeAdminSidebar();
            }
        });
    }

    if (toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isMobile = window.innerWidth <= 768;
            
            if (isMobile) {
                if (sidebar.classList.contains('active')) {
                    collapseSidebar();
                } else {
                    expandSidebar();
                }
            } else {
                if (sidebar.classList.contains('collapsed')) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', collapseSidebar);
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', collapseSidebar);
    }

    const navLinks = sidebar.querySelectorAll('.sidebar-nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                collapseSidebar();
            }
        });
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            
            const isCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                toggle.classList.add('active');
                toggle.querySelector('.toggle-icon').innerHTML = '&gt;';
            } else {
                toggle.querySelector('.toggle-icon').innerHTML = '&lt;';
            }
        } else {
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('expanded');
            toggle.classList.remove('active');
            toggle.querySelector('.toggle-icon').innerHTML = '&lt;';
            
            if (sidebar.classList.contains('active')) {
                collapseSidebar();
            }
        }
    });

    document.addEventListener('burgerMenuOpened', function() {
        if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
            closeAdminSidebar();
        }
    });
});
</script>