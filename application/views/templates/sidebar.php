<style>

/* ==========================================================
   SINERGI ASSET BACKEND SIDEBAR
   ========================================================== */

:root {

    --sa-navy: #071b3a;
    --sa-navy-soft: #0d2b55;

    --sa-gold: #d9ae2f;
    --sa-gold-light: #f4e5a9;

    --sa-text: #334155;
    --sa-muted: #94a3b8;

    --sa-bg: #f8fafc;
    --sa-border: #e5e7eb;

}


/* ==========================================================
   SIDEBAR
   ========================================================== */

.left-sidebar {

    width: 260px;

    background: #ffffff;

    border-right: 1px solid var(--sa-border);

    box-shadow: 4px 0 20px rgba(7, 27, 58, .04);

    transition: all .3s ease;

}


/* ==========================================================
   BRAND
   ========================================================== */

.left-sidebar .brand-logo {

    height: 80px;

    padding: 0 24px;

    display: flex;

    align-items: center;
    
    justify-content: center;

    border-bottom: 1px solid var(--sa-border);

    width: 100%

}


.left-sidebar .brand-logo .logo-img {

    display: flex;

    align-items: center;

    text-decoration: none;

}


.left-sidebar .brand-logo img {

    width: 145px;

    height: auto;

    object-fit: contain;

}


/* ==========================================================
   USER PROFILE
   ========================================================== */

.sidebar-profile {

    margin: 18px 16px 10px;

    padding: 16px;

    background: #f8fafc;

    border: 1px solid #eef1f5;

    border-radius: 14px;

    display: flex;

    align-items: center;

    gap: 12px;

}


.sidebar-profile__avatar {

    width: 42px;

    height: 42px;

    flex: 0 0 42px;

    border-radius: 50%;

    background: var(--sa-navy);

    color: #ffffff;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 15px;

    font-weight: 700;

    text-transform: uppercase;

}


.sidebar-profile__info {

    min-width: 0;

}


.sidebar-profile__name {

    margin: 0;

    color: var(--sa-navy);

    font-size: 13px;

    font-weight: 700;

    line-height: 1.4;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

}


.sidebar-profile__username {

    margin: 3px 0 0;

    color: var(--sa-muted);

    font-size: 11px;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

}


/* ==========================================================
   NAVIGATION
   ========================================================== */

.sidebar-nav {

    padding: 10px 12px 25px;

}


#sidebarnav {

    padding: 0;

    margin: 0;

}


/* SECTION TITLE */

.sidebar-section-title {

    padding: 18px 12px 8px;

    margin: 0;

    color: #a1aab5;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 1.2px;

}


/* ==========================================================
   SIDEBAR ITEM
   ========================================================== */

.sidebar-item {

    list-style: none;

    margin-bottom: 4px;

}


/* ==========================================================
   SIDEBAR LINK
   ========================================================== */

.left-sidebar .sidebar-link {

    min-height: 44px;

    padding: 10px 12px;

    border-radius: 10px;

    display: flex;

    align-items: center;

    gap: 12px;

    color: #64748b !important;

    font-size: 13px;

    font-weight: 500;

    text-decoration: none;

    transition: all .2s ease;

}


.left-sidebar .sidebar-link .sidebar-icon {

    width: 22px;

    display: flex;

    align-items: center;

    justify-content: center;

}


.left-sidebar .sidebar-link i {

    font-size: 18px;

    color: #94a3b8;

    transition: all .2s ease;

}


/* HOVER */

.left-sidebar .sidebar-link:hover {

    background: #f8fafc;

    color: var(--sa-navy) !important;

}


.left-sidebar .sidebar-link:hover i {

    color: var(--sa-gold);

}


/* ==========================================================
   ACTIVE
   ========================================================== */

.left-sidebar .sidebar-link.active {

    background: var(--sa-navy);

    color: #ffffff !important;

    box-shadow: 0 6px 15px rgba(7, 27, 58, .15);

}


.left-sidebar .sidebar-link.active i {

    color: var(--sa-gold);

}


/* ==========================================================
   SUBMENU
   ========================================================== */

.sidebar-submenu {

    margin: 4px 0 8px;

    padding: 4px 0 4px 34px;

}


.sidebar-submenu .sidebar-link {

    min-height: 38px;

    padding: 8px 12px;

    font-size: 12px;

}


.sidebar-submenu .sidebar-link i {

    font-size: 7px;

}


/* ==========================================================
   MENU ARROW
   ========================================================== */

.custom-arrow {

    margin-left: auto;

    font-size: 15px !important;

    color: #94a3b8 !important;

    transition: transform .25s ease;

}


.custom-arrow.rotate {

    transform: rotate(90deg);

}


/* ==========================================================
   LOGOUT AREA
   ========================================================== */

.sidebar-bottom {

    padding: 10px 12px 18px;

}


.sidebar-logout {

    display: flex;

    align-items: center;

    gap: 12px;

    width: 100%;

    min-height: 44px;

    padding: 10px 12px;

    border-radius: 10px;

    color: #64748b;

    text-decoration: none;

    font-size: 13px;

    font-weight: 500;

    transition: all .2s ease;

}


.sidebar-logout i {

    font-size: 18px;

}


.sidebar-logout:hover {

    background: #fff7ed;

    color: #c2410c;

}


/* ==========================================================
   HEADER
   ========================================================== */

.app-header {

    background: rgba(255,255,255,.94);

    border-bottom: 1px solid #eef1f5;

    box-shadow: 0 2px 12px rgba(7,27,58,.04);

    backdrop-filter: blur(10px);

}


.app-header .navbar {

    min-height: 80px;

    padding: 0 24px;

}


/* ==========================================================
   HEADER MENU BUTTON
   ========================================================== */

.app-header .nav-link {

    color: var(--sa-navy);

}


.app-header .nav-link:hover {

    color: var(--sa-gold);

}


/* ==========================================================
   HEADER PROFILE
   ========================================================== */

.header-profile {

    display: flex;

    align-items: center;

    gap: 10px;

}


.header-profile__avatar {

    width: 38px;

    height: 38px;

    border-radius: 50%;

    background: var(--sa-navy);

    color: #ffffff;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 13px;

    font-weight: 700;

}


.header-profile__name {

    color: var(--sa-navy);

    font-size: 12px;

    font-weight: 600;

}


/* ==========================================================
   DROPDOWN
   ========================================================== */

.header-profile-menu {

    min-width: 190px;

    border: 1px solid #eef1f5;

    border-radius: 12px;

    box-shadow: 0 12px 30px rgba(7,27,58,.12);

    padding: 8px;

}


.header-profile-menu .dropdown-item {

    border-radius: 8px;

    padding: 9px 10px;

    font-size: 13px;

}


.header-profile-menu .dropdown-item:hover {

    background: #f8fafc;

}


/* ==========================================================
   MOBILE
   ========================================================== */

@media(max-width:1199px){

    .left-sidebar {

        position: fixed;

        left: -260px;

        top: 0;

        bottom: 0;

        z-index: 1100;

    }


    .left-sidebar.show {

        left: 0;

    }

}


@media(max-width:576px){

    .app-header .navbar {

        padding: 0 15px;

    }


    .header-profile__name {

        display: none;

    }

}

</style>


<!-- ==========================================================
     SIDEBAR
========================================================== -->

<aside class="left-sidebar">

    <div>


        <!-- ==================================================
             BRAND
        ================================================== -->

        <div class="brand-logo">

            <a
                href="<?= base_url('dashboard'); ?>"
                class="logo-img">

                <img
                    src="<?= base_url('assets/frontend/img/logo-transparent.png'); ?>"
                    alt="Sinergi Asset">

            </a>


            <!-- MOBILE CLOSE -->

            <div
                class="close-btn d-xl-none d-block sidebartoggler cursor-pointer"
                id="sidebarCollapse">

                <i class="ti ti-x fs-6"></i>

            </div>

        </div>


        <!-- ==================================================
             USER PROFILE
        ================================================== -->

        <?php

        $userName =
            $this->session->userdata('name')
            ?: 'Administrator';

        $username =
            $this->session->userdata('username')
            ?: 'admin';

        $initials = '';

        $nameParts = preg_split(
            '/\s+/',
            trim($userName)
        );

        if (!empty($nameParts[0])) {

            $initials .= strtoupper(
                substr($nameParts[0], 0, 1)
            );

        }

        if (
            count($nameParts) > 1 &&
            !empty($nameParts[count($nameParts)-1])
        ) {

            $initials .= strtoupper(
                substr(
                    $nameParts[count($nameParts)-1],
                    0,
                    1
                )
            );

        }

        ?>


        <div class="sidebar-profile">

            <div class="sidebar-profile__avatar">

                <?= htmlspecialchars($initials); ?>

            </div>


            <div class="sidebar-profile__info">

                <p class="sidebar-profile__name">

                    <?= htmlspecialchars(
                        strtoupper($userName)
                    ); ?>

                </p>


                <p class="sidebar-profile__username">

                    <?= htmlspecialchars(
                        $username
                    ); ?>

                </p>

            </div>

        </div>


        <!-- ==================================================
             NAVIGATION
        ================================================== -->

        <nav
            class="sidebar-nav scroll-sidebar"
            data-simplebar="">


            <ul id="sidebarnav">


                <!-- ==================================================
                     MAIN
                ================================================== -->

                <li class="sidebar-section-title">

                    MAIN

                </li>


                <?php
                $currentUri = trim(
                    $this->uri->uri_string(),
                    '/'
                );

                $dashboardActive =
                    ($currentUri === 'dashboard' ||
                     $currentUri === '');
                ?>


                <li class="sidebar-item">

                    <a
                        href="<?= base_url('dashboard'); ?>"
                        class="sidebar-link <?= $dashboardActive ? 'active' : ''; ?>">

                        <span class="sidebar-icon">

                            <i class="ti ti-layout-dashboard"></i>

                        </span>

                        <span class="hide-menu">

                            Dashboard

                        </span>

                    </a>

                </li>


                <!-- ==================================================
                     PROPERTY MANAGEMENT
                ================================================== -->

                <li class="sidebar-section-title">

                    PROPERTY MANAGEMENT

                </li>


                <?php

                $propertyActive =
                    strpos(
                        $currentUri,
                        'properties'
                    ) === 0;

                ?>


                <!-- PROPERTIES -->

                <li class="sidebar-item">

                    <a
                        href="<?= base_url('properties'); ?>"
                        class="sidebar-link <?= $propertyActive ? 'active' : ''; ?>">

                        <span class="sidebar-icon">

                            <i class="ti ti-building"></i>

                        </span>

                        <span class="hide-menu">

                            Properties

                        </span>

                    </a>

                </li>


                <?php

                $categoryActive =
                    strpos(
                        $currentUri,
                        'property-categories'
                    ) === 0;

                ?>

                <!-- CATEGORIES -->

                <!-- <li class="sidebar-item">

                    <a
                        href="<?= base_url('property-categories'); ?>"
                        class="sidebar-link <?= $categoryActive ? 'active' : ''; ?>">

                        <span class="sidebar-icon">

                            <i class="ti ti-category"></i>

                        </span>

                        <span class="hide-menu">

                            Categories

                        </span>

                    </a>

                </li> -->

                <?php

                $locationActive =
                    strpos(
                        $currentUri,
                        'property-locations'
                    ) === 0;

                ?>

                <!-- LOCATIONS -->

                <!-- <li class="sidebar-item">

                    <a
                        href="<?= base_url('property-locations'); ?>"
                        class="sidebar-link <?= $locationActive ? 'active' : ''; ?>">

                        <span class="sidebar-icon">

                            <i class="ti ti-map-pin"></i>

                        </span>

                        <span class="hide-menu">

                            Locations

                        </span>

                    </a>

                </li> -->


                <!-- ==================================================
                     SYSTEM
                ================================================== -->

                <!-- <li class="sidebar-section-title">
                    SYSTEM
                </li> -->

                <?php

                $usersActive =
                    strpos(
                        $currentUri,
                        'users'
                    ) === 0;

                ?>

                <!-- <li class="sidebar-item">

                    <a
                        href="<?= base_url('users'); ?>"
                        class="sidebar-link <?= $usersActive ? 'active' : ''; ?>">

                        <span class="sidebar-icon">

                            <i class="ti ti-users"></i>

                        </span>

                        <span class="hide-menu">

                            Users

                        </span>

                    </a>

                </li> -->

            </ul>

        </nav>


        <!-- ==================================================
             LOGOUT
        ================================================== -->

        <div class="sidebar-bottom">

            <a
                href="<?= base_url('auth/logout'); ?>"
                class="sidebar-logout">

                <i class="ti ti-logout"></i>

                <span>

                    Logout

                </span>

            </a>

        </div>


    </div>

</aside>


<!-- ==========================================================
     BODY WRAPPER
========================================================== -->

<div class="body-wrapper">


    <!-- ==================================================
         HEADER
    ================================================== -->

    <header class="app-header">

        <nav class="navbar navbar-expand-lg navbar-light">


            <!-- MOBILE TOGGLE -->

            <ul class="navbar-nav">

                <li class="nav-item d-block d-xl-none">

                    <a
                        class="nav-link nav-icon-hover sidebartoggler"
                        id="headerCollapse"
                        href="javascript:void(0)">

                        <i class="ti ti-menu-2"></i>

                    </a>

                </li>

            </ul>


            <!-- ==================================================
                 RIGHT HEADER
            ================================================== -->

            <div
                class="navbar-collapse justify-content-end px-0"
                id="navbarNav">

                <ul
                    class="navbar-nav flex-row ms-auto align-items-center">


                    <li class="nav-item dropdown">

                        <a
                            class="nav-link nav-icon-hover"
                            href="javascript:void(0)"
                            id="drop2"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">


                            <div class="header-profile">

                                <div class="header-profile__avatar">

                                    <?= htmlspecialchars(
                                        $initials
                                    ); ?>

                                </div>


                                <span class="header-profile__name">

                                    <?= htmlspecialchars(
                                        strtoupper($userName)
                                    ); ?>

                                </span>


                                <i class="ti ti-chevron-down"></i>

                            </div>


                        </a>


                        <div
                            class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up header-profile-menu"
                            aria-labelledby="drop2">


                            <div class="message-body">


                                <!-- PROFILE -->

                                <div
                                    class="px-3 py-2 mb-1">

                                    <div
                                        style="
                                            font-size:12px;
                                            color:#94a3b8;
                                        ">

                                        Signed in as

                                    </div>

                                    <div
                                        style="
                                            font-size:13px;
                                            font-weight:700;
                                            color:#071b3a;
                                        ">

                                        <?= htmlspecialchars(
                                            $username
                                        ); ?>

                                    </div>

                                </div>


                                <!-- MY PROFILE -->

                                <a
                                    href="<?= base_url('users/profile'); ?>"
                                    class="dropdown-item d-flex align-items-center gap-2">

                                    <i class="ti ti-user fs-5"></i>

                                    <span>

                                        My Profile

                                    </span>

                                </a>


                                <!-- LOGOUT -->

                                <a
                                    href="<?= base_url('auth/logout'); ?>"
                                    class="dropdown-item d-flex align-items-center gap-2 text-danger">

                                    <i class="ti ti-logout fs-5"></i>

                                    <span>

                                        Logout

                                    </span>

                                </a>


                            </div>

                        </div>

                    </li>


                </ul>

            </div>


        </nav>

    </header>