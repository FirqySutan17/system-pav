<style>
:root {
    --pav-black: #171717;
    --pav-black-soft: #242424;
    --pav-orange: #f97316;
    --pav-orange-light: #fff0e5;
    --pav-gold: #d6a84f;
    --pav-text: #374151;
    --pav-muted: #9ca3af;
    --pav-bg: #f9fafb;
    --pav-border: #e5e7eb;
}
.left-sidebar { width:260px; background:#fff; border-right:1px solid var(--pav-border); box-shadow:4px 0 20px rgba(0,0,0,.05); transition:all .3s ease; }
.left-sidebar .brand-logo { height:80px; padding:0 24px; display:flex; align-items:center; justify-content:center; border-bottom:1px solid var(--pav-border); width:100%; position:relative; }
.left-sidebar .brand-logo .logo-img { display:flex; align-items:center; justify-content:center; text-decoration:none; }
.left-sidebar .brand-logo img { width:145px; max-height:60px; height:auto; object-fit:contain; }
.left-sidebar .close-btn { position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--pav-black); }
.sidebar-profile { margin:18px 16px 10px; padding:14px; background:#fffaf5; border:1px solid #ffeadb; border-radius:14px; display:flex; align-items:center; gap:12px; }
.sidebar-profile__avatar,.header-profile__avatar { flex:0 0 42px; width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg,var(--pav-black),#3b3b3b); border:2px solid var(--pav-gold); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:800; text-transform:uppercase; }
.sidebar-profile__info { min-width:0; }
.sidebar-profile__name { margin:0; color:var(--pav-black); font-size:12px; font-weight:800; line-height:1.4; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sidebar-profile__username { margin:3px 0 0; color:var(--pav-muted); font-size:11px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sidebar-nav { padding:10px 12px 25px; }
#sidebarnav { padding:0; margin:0; }
.sidebar-section-title { list-style:none; padding:18px 12px 8px; margin:0; color:#a1a1aa; font-size:10px; font-weight:800; letter-spacing:1.2px; }
.sidebar-item { list-style:none; margin-bottom:4px; }
.left-sidebar .sidebar-link { min-height:44px; padding:10px 12px; border-radius:10px; display:flex; align-items:center; gap:12px; color:#626262 !important; font-size:13px; font-weight:600; text-decoration:none; transition:all .2s ease; }
.left-sidebar .sidebar-link .sidebar-icon { width:22px; display:flex; align-items:center; justify-content:center; }
.left-sidebar .sidebar-link i { font-size:18px; color:#9ca3af; transition:all .2s ease; }
.left-sidebar .sidebar-link:hover { background:var(--pav-orange-light); color:var(--pav-black) !important; }
.left-sidebar .sidebar-link:hover i { color:var(--pav-orange); }
.left-sidebar .sidebar-link.active { background:linear-gradient(110deg,var(--pav-black),#303030); color:#fff !important; box-shadow:0 6px 15px rgba(0,0,0,.15); }
.left-sidebar .sidebar-link.active i { color:var(--pav-orange); }
.sidebar-bottom { padding:10px 12px 18px; }
.sidebar-logout { display:flex; align-items:center; gap:12px; width:100%; min-height:44px; padding:10px 12px; border-radius:10px; color:#6b7280; text-decoration:none; font-size:13px; font-weight:600; transition:all .2s ease; }
.sidebar-logout i { font-size:18px; }
.sidebar-logout:hover { background:#fff0e5; color:#c2410c; }
.app-header { background:rgba(255,255,255,.96); border-bottom:1px solid #f0f0f0; box-shadow:0 2px 12px rgba(0,0,0,.04); backdrop-filter:blur(10px); }
.app-header .navbar { min-height:80px; padding:0 24px; }
.app-header .nav-link { color:var(--pav-black); }
.app-header .nav-link:hover { color:var(--pav-orange); }
.header-profile { display:flex; align-items:center; gap:10px; }
.header-profile__avatar { width:38px; height:38px; flex-basis:38px; font-size:12px; }
.header-profile__name { color:var(--pav-black); font-size:12px; font-weight:700; }
.header-profile-menu { min-width:190px; border:1px solid #f0f0f0; border-radius:12px; box-shadow:0 12px 30px rgba(0,0,0,.12); padding:8px; }
.header-profile-menu .dropdown-item { border-radius:8px; padding:9px 10px; font-size:13px; }
.header-profile-menu .dropdown-item:hover { background:#fff0e5; color:#c2410c; }
@media(max-width:1199px) { .left-sidebar { position:fixed; left:-260px; top:0; bottom:0; z-index:1100; overflow-y:auto; } .left-sidebar.show { left:0; } }
@media(max-width:576px) { .app-header .navbar { padding:0 15px; } .header-profile__name { display:none; } }
</style>

<!-- SIDEBAR -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo">
            <a href="<?= base_url('dashboard'); ?>" class="logo-img">
                <img src="<?= base_url('assets/frontend/img/pav-logo.png'); ?>" alt="PT Pitek Balap Perkasa">
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-6"></i>
            </div>
        </div>

        <?php
        $userName = $this->session->userdata('name') ?: 'Administrator';
        $username = $this->session->userdata('username') ?: 'admin';
        $nameParts = preg_split('/\s+/', trim($userName));
        $initials = '';
        if (!empty($nameParts[0])) $initials .= strtoupper(substr($nameParts[0], 0, 1));
        if (count($nameParts) > 1 && !empty($nameParts[count($nameParts)-1])) $initials .= strtoupper(substr($nameParts[count($nameParts)-1], 0, 1));
        if ($initials === '') $initials = 'AD';
        $currentUri = trim($this->uri->uri_string(), '/');
        $dashboardActive = ($currentUri === 'dashboard' || $currentUri === '');
        $propertyActive = (strpos($currentUri, 'properties') === 0);
        ?>

        <div class="sidebar-profile">
            <div class="sidebar-profile__avatar"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="sidebar-profile__info">
                <p class="sidebar-profile__name"><?= htmlspecialchars(strtoupper($userName), ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="sidebar-profile__username"><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <!-- <li class="sidebar-section-title">Main</li> -->
                <li class="sidebar-item">
                    <a href="<?= base_url('dashboard'); ?>" class="sidebar-link <?= $dashboardActive ? 'active' : ''; ?>">
                        <span class="sidebar-icon"><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-section-title">Master Data</li>
                <li class="sidebar-item">
                    <a href="<?= base_url('properties'); ?>" class="sidebar-link <?= $propertyActive ? 'active' : ''; ?>">
                        <span class="sidebar-icon"><i class="ti ti-building"></i></span>
                        <span class="hide-menu">CD CODE</span>
                    </a>
                </li>
                <!-- Aktifkan menu berikut jika controller/route-nya sudah tersedia:
                <li class="sidebar-item"><a href="<?= base_url('property-categories'); ?>" class="sidebar-link"><span class="sidebar-icon"><i class="ti ti-category"></i></span><span class="hide-menu">Categories</span></a></li>
                <li class="sidebar-item"><a href="<?= base_url('property-locations'); ?>" class="sidebar-link"><span class="sidebar-icon"><i class="ti ti-map-pin"></i></span><span class="hide-menu">Locations</span></a></li>
                <li class="sidebar-item"><a href="<?= base_url('users'); ?>" class="sidebar-link"><span class="sidebar-icon"><i class="ti ti-users"></i></span><span class="hide-menu">Users</span></a></li>
                -->
            </ul>
        </nav>

        <div class="sidebar-bottom">
            <a href="<?= base_url('auth/logout'); ?>" class="sidebar-logout">
                <i class="ti ti-logout"></i><span>Logout</span>
            </a>
        </div>
    </div>
</aside>

<!-- BODY WRAPPER + HEADER -->
<div class="body-wrapper">
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item d-block d-xl-none">
                    <a class="nav-link nav-icon-hover sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>

            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                <ul class="navbar-nav flex-row ms-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="header-profile">
                                <div class="header-profile__avatar"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?></div>
                                <span class="header-profile__name"><?= htmlspecialchars(strtoupper($userName), ENT_QUOTES, 'UTF-8'); ?></span>
                                <i class="ti ti-chevron-down"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end header-profile-menu" aria-labelledby="drop2">
                            <div class="px-3 py-2">
                                <div class="fw-bold"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></div>
                                <small class="text-muted"><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('users/profile'); ?>"><i class="ti ti-user me-2"></i>My Profile</a>
                            <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>"><i class="ti ti-logout me-2"></i>Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
