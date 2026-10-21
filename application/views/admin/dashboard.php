<div class="container-fluid dashboard-modern">

    <style>

        /* ==========================================================
           DASHBOARD
        ========================================================== */

        .dashboard-modern {
            padding: 28px;
            /* background: #f7f9fc; */
            min-height: calc(100vh - 70px);
        }


        /* ==========================================================
           WELCOME
        ========================================================== */

        .dashboard-welcome {
            position: relative;
            overflow: hidden;

            background:
                radial-gradient(
                    circle at 90% 20%,
                    rgba(217,170,43,.16),
                    transparent 30%
                ),
                linear-gradient(
                    135deg,
                    #071c3a 0%,
                    #0b2b55 100%
                );

            border-radius: 22px;
            padding: 30px 34px;
            margin-bottom: 24px;

            color: #fff;

            box-shadow:
                0 15px 40px rgba(7,28,58,.12);
        }


        .dashboard-welcome::after {
            content: "";

            position: absolute;

            width: 220px;
            height: 220px;

            border: 1px solid rgba(255,255,255,.08);
            border-radius: 50%;

            right: -70px;
            top: -80px;
        }


        .dashboard-welcome__content {
            position: relative;
            z-index: 2;
        }


        .dashboard-welcome__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            font-size: 11px;
            font-weight: 700;

            letter-spacing: 2px;
            text-transform: uppercase;

            color: #e2b63f;

            margin-bottom: 10px;
        }


        .dashboard-welcome__eyebrow::before {
            content: "";

            width: 24px;
            height: 2px;

            background: #d9aa2b;

            display: inline-block;
        }


        .dashboard-welcome h1 {
            margin: 0 0 8px;

            font-size: 30px;
            font-weight: 700;

            color: #fff;
        }


        .dashboard-welcome p {
            margin: 0;

            font-size: 14px;

            color: rgba(255,255,255,.72);
        }


        .dashboard-welcome__user {
            color: #fff;
            font-weight: 600;
        }


        /* ==========================================================
           SUMMARY CARDS
        ========================================================== */

        .dashboard-summary {
            margin-bottom: 26px;
        }


        .summary-card {
            position: relative;
            overflow: hidden;

            height: 100%;

            background: #fff;

            border: 1px solid #edf0f5;

            border-radius: 18px;

            padding: 22px;

            box-shadow:
                0 8px 25px rgba(15,23,42,.05);

            transition:
                transform .25s ease,
                box-shadow .25s ease;
        }


        .summary-card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 15px 35px rgba(15,23,42,.09);
        }


        .summary-card__top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;

            margin-bottom: 20px;
        }


        .summary-card__icon {
            width: 46px;
            height: 46px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 13px;

            background: #fff8e6;

            color: #c99a21;

            font-size: 18px;
        }


        .summary-card__label {
            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 1.1px;

            color: #8490a3;
        }


        .summary-card__value {
            font-size: 32px;

            line-height: 1;

            font-weight: 800;

            color: #071c3a;

            margin-bottom: 8px;
        }


        .summary-card__description {
            margin: 0;

            font-size: 12px;

            color: #8b95a5;
        }


        .summary-card__accent {
            position: absolute;

            width: 90px;
            height: 90px;

            right: -40px;
            bottom: -45px;

            border-radius: 50%;

            background: rgba(217,170,43,.06);
        }


        /* ==========================================================
           SECTION HEADER
        ========================================================== */

        .dashboard-section {
            margin-bottom: 26px;
        }


        .dashboard-section__header {
            display: flex;

            align-items: flex-end;
            justify-content: space-between;

            margin-bottom: 16px;
        }


        .dashboard-section__eyebrow {
            display: block;

            font-size: 10px;

            font-weight: 700;

            letter-spacing: 1.8px;

            color: #c99a21;

            text-transform: uppercase;

            margin-bottom: 6px;
        }


        .dashboard-section__title {
            margin: 0;

            font-size: 22px;

            font-weight: 750;

            color: #071c3a;
        }


        .dashboard-section__description {
            margin: 4px 0 0;

            font-size: 13px;

            color: #8993a4;
        }


        /* ==========================================================
           RECENT PROPERTIES
        ========================================================== */

        .property-dashboard-card {
            height: 100%;

            background: #fff;

            border: 1px solid #edf0f5;

            border-radius: 20px;

            overflow: hidden;

            box-shadow:
                0 8px 25px rgba(15,23,42,.05);

            transition:
                transform .25s ease,
                box-shadow .25s ease;
        }


        .property-dashboard-card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 15px 35px rgba(15,23,42,.09);
        }


        .property-dashboard-card__image {
            position: relative;

            height: 180px;

            overflow: hidden;

            background: #e9edf3;
        }


        .property-dashboard-card__image img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            transition: transform .45s ease;
        }


        .property-dashboard-card:hover
        .property-dashboard-card__image img {
            transform: scale(1.04);
        }


        .property-dashboard-card__featured {
            position: absolute;

            top: 14px;
            left: 14px;

            padding: 6px 10px;

            border-radius: 999px;

            background: #d9aa2b;

            color: #fff;

            font-size: 9px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .8px;
        }


        .property-dashboard-card__status {
            position: absolute;

            top: 14px;
            right: 14px;

            padding: 6px 10px;

            border-radius: 999px;

            background: rgba(255,255,255,.92);

            color: #176b45;

            font-size: 9px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .5px;
        }


        .property-dashboard-card__content {
            padding: 20px;
        }


        .property-dashboard-card__category {
            display: block;

            margin-bottom: 7px;

            font-size: 9px;

            font-weight: 700;

            letter-spacing: 1.2px;

            text-transform: uppercase;

            color: #c99a21;
        }


        .property-dashboard-card__title {
            margin: 0 0 9px;

            font-size: 17px;

            font-weight: 750;

            line-height: 1.3;

            color: #071c3a;
        }


        .property-dashboard-card__location {
            display: flex;

            align-items: center;

            gap: 6px;

            font-size: 12px;

            color: #7e899b;

            margin-bottom: 18px;
        }


        .property-dashboard-card__location i {
            color: #d9aa2b;
        }


        .property-dashboard-card__footer {
            display: flex;

            align-items: center;

            justify-content: space-between;

            padding-top: 14px;

            border-top: 1px solid #edf0f5;
        }


        .property-dashboard-card__type {
            font-size: 11px;

            font-weight: 600;

            color: #7e899b;
        }


        .property-dashboard-card__arrow {
            width: 32px;
            height: 32px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #071c3a;

            color: #fff;

            font-size: 12px;

            text-decoration: none;

            transition: .25s ease;
        }


        .property-dashboard-card__arrow:hover {
            background: #d9aa2b;

            color: #fff;
        }


        /* ==========================================================
           EMPTY STATE
        ========================================================== */

        .dashboard-empty {
            background: #fff;

            border: 1px dashed #dce2eb;

            border-radius: 18px;

            padding: 50px 20px;

            text-align: center;

            color: #8993a4;
        }


        .dashboard-empty i {
            display: block;

            margin-bottom: 12px;

            font-size: 30px;

            color: #d9aa2b;
        }


        .dashboard-empty strong {
            display: block;

            margin-bottom: 5px;

            color: #071c3a;
        }


        /* ==========================================================
           QUICK ACTION
        ========================================================== */

        .dashboard-action-card {
            height: 100%;

            padding: 25px;

            border-radius: 20px;

            background:
                radial-gradient(
                    circle at 100% 0,
                    rgba(217,170,43,.14),
                    transparent 35%
                ),
                #071c3a;

            color: #fff;

            box-shadow:
                0 12px 30px rgba(7,28,58,.12);
        }


        .dashboard-action-card__icon {
            width: 44px;
            height: 44px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: rgba(217,170,43,.15);

            color: #e2b63f;

            margin-bottom: 18px;
        }


        .dashboard-action-card h3 {
            margin: 0 0 8px;

            font-size: 19px;

            color: #fff;

            font-weight: 700;
        }


        .dashboard-action-card p {
            margin: 0 0 20px;

            font-size: 12px;

            line-height: 1.7;

            color: rgba(255,255,255,.68);
        }


        .dashboard-action-card a {
            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 10px 15px;

            border-radius: 10px;

            background: #d9aa2b;

            color: #fff;

            text-decoration: none;

            font-size: 12px;

            font-weight: 700;

            transition: .25s ease;
        }


        .dashboard-action-card a:hover {
            background: #e5b93d;

            transform: translateY(-2px);
        }


        /* ==========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 991px) {

            .dashboard-modern {
                padding: 20px;
            }

            .dashboard-welcome {
                padding: 25px;
            }

            .dashboard-welcome h1 {
                font-size: 25px;
            }

            .summary-card__value {
                font-size: 28px;
            }

        }


        @media (max-width: 575px) {

            .dashboard-modern {
                padding: 15px;
            }

            .dashboard-welcome {
                border-radius: 17px;
                padding: 22px;
            }

            .dashboard-welcome h1 {
                font-size: 22px;
            }

            .dashboard-section__title {
                font-size: 19px;
            }

            .property-dashboard-card__image {
                height: 190px;
            }

        }

    </style>


    <!-- ==========================================================
         WELCOME
    =========================================================== -->

    <section class="dashboard-welcome">

        <div class="dashboard-welcome__content">

            <div class="dashboard-welcome__eyebrow">
                SINERGI ASSET DASHBOARD
            </div>

            <h1>
                Welcome back,
                <?= htmlspecialchars(
                    strtoupper(
                        $this->session->userdata('name') ?: 'Administrator'
                    )
                ); ?>
            </h1>

            <p>
                Manage and monitor your commercial & investment
                property portfolio from one place.
            </p>

        </div>

    </section>


    <!-- ==========================================================
         SUMMARY
    =========================================================== -->

    <section class="dashboard-summary">

        <div class="row g-3">


            <!-- TOTAL PROPERTIES -->

            <div class="col-xl-3 col-md-6">

                <div class="summary-card">

                    <div class="summary-card__top">

                        <div class="summary-card__icon">

                            <i class="bi bi-buildings"></i>

                        </div>

                    </div>

                    <div class="summary-card__label">
                        Total Properties
                    </div>

                    <div class="summary-card__value">

                        <?= number_format(
                            $summary['properties'] ?? 0
                        ); ?>

                    </div>

                    <p class="summary-card__description">
                        Properties currently registered
                    </p>

                    <div class="summary-card__accent"></div>

                </div>

            </div>


            <!-- AVAILABLE -->

            <div class="col-xl-3 col-md-6">

                <div class="summary-card">

                    <div class="summary-card__top">

                        <div class="summary-card__icon">

                            <i class="bi bi-check2-circle"></i>

                        </div>

                    </div>

                    <div class="summary-card__label">
                        Available Properties
                    </div>

                    <div class="summary-card__value">

                        <?= number_format(
                            $summary['available'] ?? 0
                        ); ?>

                    </div>

                    <p class="summary-card__description">
                        Properties currently available
                    </p>

                    <div class="summary-card__accent"></div>

                </div>

            </div>


            <!-- FEATURED -->

            <div class="col-xl-3 col-md-6">

                <div class="summary-card">

                    <div class="summary-card__top">

                        <div class="summary-card__icon">

                            <i class="bi bi-star"></i>

                        </div>

                    </div>

                    <div class="summary-card__label">
                        Featured Properties
                    </div>

                    <div class="summary-card__value">

                        <?= number_format(
                            $summary['featured'] ?? 0
                        ); ?>

                    </div>

                    <p class="summary-card__description">
                        Properties highlighted on website
                    </p>

                    <div class="summary-card__accent"></div>

                </div>

            </div>


            <!-- CATEGORIES -->

            <div class="col-xl-3 col-md-6">

                <div class="summary-card">

                    <div class="summary-card__top">

                        <div class="summary-card__icon">

                            <i class="bi bi-grid"></i>

                        </div>

                    </div>

                    <div class="summary-card__label">
                        Property Categories
                    </div>

                    <div class="summary-card__value">

                        <?= number_format(
                            $summary['categories'] ?? 0
                        ); ?>

                    </div>

                    <p class="summary-card__description">
                        Categories available in portfolio
                    </p>

                    <div class="summary-card__accent"></div>

                </div>

            </div>

        </div>

    </section>


    <!-- ==========================================================
         RECENT PROPERTIES
    =========================================================== -->

    <section class="dashboard-section">

        <div class="dashboard-section__header">

            <div>

                <span class="dashboard-section__eyebrow">
                    Property Portfolio
                </span>

                <h2 class="dashboard-section__title">
                    Recent Properties
                </h2>

                <p class="dashboard-section__description">
                    The latest properties added to your portfolio.
                </p>

            </div>

            <a
                href="<?= site_url('property'); ?>"
                class="btn btn-sm btn-outline-secondary">

                View All

                <i class="bi bi-arrow-up-right ms-1"></i>

            </a>

        </div>


        <div class="row g-4">

            <?php if (!empty($recent_properties)): ?>

                <?php foreach ($recent_properties as $property): ?>

                    <div class="col-xl-4 col-lg-4 col-md-6">

                        <div class="property-dashboard-card">


                            <!-- IMAGE -->

                            <div class="property-dashboard-card__image">

                                <?php
                                $thumbnail = !empty($property['thumbnail'])
                                    ? $property['thumbnail']
                                    : 'property/default.jpg';
                                ?>

                                <img
                                    src="<?= base_url(
                                        $thumbnail
                                    ); ?>"
                                    alt="<?= htmlspecialchars(
                                        $property['title']
                                    ); ?>"
                                    loading="lazy"
                                    onerror="this.src='<?= base_url(
                                        'assets/frontend/img/property/default.jpg'
                                    ); ?>';">


                                <?php if (!empty($property['featured'])): ?>

                                    <span class="property-dashboard-card__featured">

                                        <i class="bi bi-star-fill me-1"></i>

                                        Featured

                                    </span>

                                <?php endif; ?>


                                <?php if (!empty($property['status'])): ?>

                                    <span class="property-dashboard-card__status">

                                        <?= htmlspecialchars(
                                            $property['status']
                                        ); ?>

                                    </span>

                                <?php endif; ?>

                            </div>


                            <!-- CONTENT -->

                            <div class="property-dashboard-card__content">


                                <span class="property-dashboard-card__category">

                                    <?= htmlspecialchars(
                                        $property['category_name']
                                        ?? $property['asset_type']
                                        ?? 'Property'
                                    ); ?>

                                </span>


                                <h3 class="property-dashboard-card__title">

                                    <?= htmlspecialchars(
                                        $property['title']
                                    ); ?>

                                </h3>


                                <div class="property-dashboard-card__location">

                                    <i class="bi bi-geo-alt-fill"></i>

                                    <span>

                                        <?= htmlspecialchars(
                                            $property['city']
                                            ?? '-'
                                        ); ?>

                                        <?php if (!empty($property['province'])): ?>

                                            ,
                                            <?= htmlspecialchars(
                                                $property['province']
                                            ); ?>

                                        <?php endif; ?>

                                    </span>

                                </div>


                                <div class="property-dashboard-card__footer">

                                    <span class="property-dashboard-card__type">

                                        <?= htmlspecialchars(
                                            $property['asset_class']
                                            ?? 'Property Asset'
                                        ); ?>

                                    </span>


                                    <a
                                        href="<?= site_url(
                                            'property/detail/' .
                                            $property['slug']
                                        ); ?>"
                                        class="property-dashboard-card__arrow"
                                        title="View Property">

                                        <i class="bi bi-arrow-up-right"></i>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>

                <div class="col-12">

                    <div class="dashboard-empty">

                        <i class="bi bi-buildings"></i>

                        <strong>
                            No Properties Yet
                        </strong>

                        <span>
                            Start adding properties to build your portfolio.
                        </span>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- ==========================================================
         BOTTOM INFORMATION
    =========================================================== -->

    <section class="dashboard-section">

        <div class="row g-4">


            <!-- PORTFOLIO OVERVIEW -->

            <div class="col-lg-8">

                <div class="dashboard-card">

                    <div class="dashboard-section__header">

                        <div>

                            <span class="dashboard-section__eyebrow">
                                Portfolio Overview
                            </span>

                            <h2 class="dashboard-section__title">
                                Property Management
                            </h2>

                            <p class="dashboard-section__description">
                                Manage your property portfolio,
                                categories, locations, images and documents.
                            </p>

                        </div>

                    </div>


                    <div class="row g-3">


                        <div class="col-md-4">

                            <div class="p-3 rounded-3 bg-light">

                                <div class="text-muted small mb-2">
                                    Properties
                                </div>

                                <div class="fw-bold fs-5 text-dark">

                                    <?= number_format(
                                        $summary['properties'] ?? 0
                                    ); ?>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="p-3 rounded-3 bg-light">

                                <div class="text-muted small mb-2">
                                    Categories
                                </div>

                                <div class="fw-bold fs-5 text-dark">

                                    <?= number_format(
                                        $summary['categories'] ?? 0
                                    ); ?>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="p-3 rounded-3 bg-light">

                                <div class="text-muted small mb-2">
                                    Featured
                                </div>

                                <div class="fw-bold fs-5 text-dark">

                                    <?= number_format(
                                        $summary['featured'] ?? 0
                                    ); ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- QUICK ACTION -->

            <div class="col-lg-4">

                <div class="dashboard-action-card">

                    <div class="dashboard-action-card__icon">

                        <i class="bi bi-plus-lg"></i>

                    </div>


                    <h3>
                        Add New Property
                    </h3>


                    <p>
                        Create a new property listing and add
                        its information, images and supporting details.
                    </p>


                    <a
                        href="<?= site_url('properties/create'); ?>">

                        Add Property

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

        </div>

    </section>


</div>