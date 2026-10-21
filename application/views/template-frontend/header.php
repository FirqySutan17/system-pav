<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO -->
    <title><?= isset($title) ? $title . ' | PT Sinergi Aset Jagat Integrasi' : 'PT Sinergi Aset Jagat Integrasi'; ?></title>

    <meta name="description" content="<?= isset($description) ? $description : ''; ?>">
    <meta name="keywords" content="<?= isset($keywords) ? $keywords : ''; ?>">
    <meta name="author" content="PT Sinergi Aset Jagat Integrasi">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/frontend/img/favicon-192.png'); ?>">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- AOS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/frontend/css/style.css'); ?>">

</head>

<body class="<?= isset($body_class) ? $body_class : ''; ?>">

<!-- ===============================
Mobile Overlay
=============================== -->

<div class="mobile-overlay"></div>

<!-- ===============================
Header
=============================== -->

<header
    id="header"
    class="header <?= !empty($transparent_header) ? '' : 'scrolled'; ?>"
    data-transparent="<?= !empty($transparent_header) ? 'true' : 'false'; ?>">

    <div class="container">

        <nav class="navbar">

            <!-- ===============================
            Logo
            =============================== -->

            <a href="<?= base_url(); ?>" class="navbar__logo">

                <img
                    src="<?= base_url('assets/frontend/img/logo-white.png');?>"
                    data-white="<?= base_url('assets/frontend/img/logo-white.png');?>"
                    data-dark="<?= base_url('assets/frontend/img/logo-blue.png');?>"
                    id="navbarLogo"
                    alt="Logo">

            </a>

            <!-- ===============================
            Desktop Menu
            =============================== -->

            <?php
            $current_uri = $this->uri->segment(1);

            /*
            |--------------------------------------------------------------------------
            | ACTIVE MENU
            |--------------------------------------------------------------------------
            | Home       = URI kosong
            | Properties = /property/...
            | Contact    = #contact di homepage
            |--------------------------------------------------------------------------
            */

            $is_home = empty($current_uri);

            $is_property = (
                $current_uri === 'property'
            );
            ?>

            <ul class="navbar__menu">

                <!-- HOME -->

                <li class="navbar__item">

                    <a
                        href="<?= base_url(); ?>"
                        class="navbar__link <?= $is_home ? 'active' : ''; ?>">

                        Home

                    </a>

                </li>


                <!-- PROPERTIES -->

                <li class="navbar__item">

                    <a
                        href="<?= base_url('property'); ?>"
                        class="navbar__link <?= $is_property ? 'active' : ''; ?>">

                        Properties

                    </a>

                </li>


                <!-- CONTACT -->

                <li class="navbar__item">

                    <a
                        href="<?= base_url(); ?>#contact"
                        class="navbar__link">

                        Contact

                    </a>

                </li>

            </ul>

            <!-- ===============================
            Right Side
            =============================== -->

            <div class="navbar__action">

                <a
                    href="https://wa.me/6281316874613?text=Hello%2C%20I%20would%20like%20to%20get%20a%20property%20consultation."
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn-whatsapp">

                    <i class="bi bi-whatsapp"></i>

                    <span>WhatsApp</span>

                </a>

                <!-- Hamburger -->

                <button class="navbar__toggle"
                        id="navbarToggle"
                        aria-label="Toggle Navigation">

                    <span></span>
                    <span></span>
                    <span></span>

                </button>

            </div>

        </nav>

    </div>

</header>

<!-- ===============================
Mobile Navigation
=============================== -->

<div class="mobile-menu" id="mobileMenu">

    <div class="mobile-menu__header">

        <img src="<?= base_url('assets/frontend/img/logo-blue.png'); ?>"
             alt="Logo">

        <button class="mobile-menu__close"
                id="mobileClose">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>

    <ul class="mobile-menu__nav">

        <li class="navbar__item">
            <a href="<?= base_url(); ?>"
            class="navbar__link <?= ($this->uri->segment(1) == '') ? 'active' : ''; ?>">
                Home
            </a>
        </li>

        <li class="navbar__item">
            <a href="<?= base_url('property'); ?>"
            class="navbar__link <?= ($this->uri->segment(1) == 'property') ? 'active' : ''; ?>">
                Properties
            </a>
        </li>

        <li>

            <a href="<?= base_url(); ?>#contact">

                Contact

            </a>

        </li>

    </ul>

    <div class="mobile-menu__footer">

        <a
            href="https://wa.me/6281316874613?text=Hello%2C%20I%20would%20like%20to%20get%20a%20property%20consultation."
            target="_blank"
            rel="noopener noreferrer"
            class="btn-whatsapp btn-full">

            <i class="bi bi-whatsapp"></i>

            WhatsApp

        </a>

    </div>

</div>

<!-- ===============================
Main Content
=============================== -->

<main style="overflow: hidden;">