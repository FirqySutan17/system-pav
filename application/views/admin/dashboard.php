<div class="container-fluid dashboard-modern">

    <?php
    /*
     * STATIC DEMO DASHBOARD
     * Seluruh data di bawah ini adalah dummy.
     * Tidak mengambil data dari database maupun session.
     */
    $summary = [
        'properties' => 120,
        'available'  => 85,
        'featured'   => 12,
        'categories' => 8
    ];

    $recent_properties = [
        [
            'title'         => 'Commercial Office Building',
            'category_name' => 'Commercial',
            'city'          => 'Jakarta',
            'province'      => 'DKI Jakarta',
            'asset_class'   => 'Office Building',
            'status'        => 'Available',
            'featured'      => true,
            'thumbnail'     => 'assets/frontend/img/property/default.jpg',
            'slug'          => '#'
        ],
        [
            'title'         => 'Premium Warehouse',
            'category_name' => 'Industrial',
            'city'          => 'Bekasi',
            'province'      => 'West Java',
            'asset_class'   => 'Warehouse',
            'status'        => 'Available',
            'featured'      => false,
            'thumbnail'     => 'assets/frontend/img/property/default.jpg',
            'slug'          => '#'
        ],
        [
            'title'         => 'Strategic Land Property',
            'category_name' => 'Land',
            'city'          => 'Tangerang',
            'province'      => 'Banten',
            'asset_class'   => 'Land Asset',
            'status'        => 'Occupied',
            'featured'      => true,
            'thumbnail'     => 'assets/frontend/img/property/default.jpg',
            'slug'          => '#'
        ]
    ];
    ?>

    <style>
        .dashboard-modern {
            padding: 28px;
            min-height: calc(100vh - 70px);
        }

        .dashboard-welcome {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 90% 20%, rgba(245,154,35,.18), transparent 30%),
                linear-gradient(135deg, #111111 0%, #292017 100%);
            border: 1px solid rgba(245,154,35,.16);
            border-radius: 22px;
            padding: 30px 34px;
            margin-bottom: 24px;
            color: #fff;
            box-shadow: 0 15px 40px rgba(17,17,17,.12);
        }

        .dashboard-welcome::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border: 1px solid rgba(255,198,92,.18);
            border-radius: 50%;
            right: -70px;
            top: -80px;
            box-shadow: 0 0 0 45px rgba(255,198,92,.025), 0 0 0 90px rgba(255,198,92,.015);
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
            color: #ffc65c;
            margin-bottom: 10px;
        }

        .dashboard-welcome__eyebrow::before {
            content: "";
            width: 24px;
            height: 2px;
            background: #f59a23;
            display: inline-block;
        }

        .dashboard-welcome h1 {
            margin: 0 0 8px;
            font-size: 30px;
            font-weight: 800;
            color: #fff;
        }

        .dashboard-welcome h1 span {
            color: #ffc65c;
        }

        .dashboard-welcome p {
            margin: 0;
            font-size: 14px;
            color: rgba(255,255,255,.72);
        }

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
            box-shadow: 0 8px 25px rgba(15,23,42,.05);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .summary-card:hover,
        .property-dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(15,23,42,.09);
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
            background: #fff5e5;
            color: #d98212;
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
            color: #191714;
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
            background: rgba(245,154,35,.07);
        }

        .dashboard-section {
            margin-bottom: 26px;
        }

        .dashboard-section__header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 16px;
        }

        .dashboard-section__eyebrow {
            display: block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.8px;
            color: #d98212;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .dashboard-section__title {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            color: #191714;
        }

        .dashboard-section__description {
            margin: 4px 0 0;
            font-size: 13px;
            color: #8993a4;
        }

        .property-dashboard-card {
            height: 100%;
            background: #fff;
            border: 1px solid #edf0f5;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(15,23,42,.05);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .property-dashboard-card__image {
            position: relative;
            height: 180px;
            overflow: hidden;
            background: #f1eee9;
        }

        .property-dashboard-card__image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .property-dashboard-card:hover .property-dashboard-card__image img {
            transform: scale(1.04);
        }

        .property-dashboard-card__featured,
        .property-dashboard-card__status {
            position: absolute;
            top: 14px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .property-dashboard-card__featured {
            left: 14px;
            background: #f59a23;
            color: #20160b;
        }

        .property-dashboard-card__status {
            right: 14px;
            background: rgba(255,255,255,.94);
            color: #176b45;
        }

        .property-dashboard-card__status.is-occupied {
            color: #9a5800;
        }

        .property-dashboard-card__content {
            padding: 20px;
        }

        .property-dashboard-card__category {
            display: block;
            margin-bottom: 7px;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #d98212;
        }

        .property-dashboard-card__title {
            margin: 0 0 9px;
            font-size: 17px;
            font-weight: 800;
            line-height: 1.4;
            color: #191714;
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
            color: #d98212;
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
            background: #191714;
            color: #ffc65c;
            font-size: 12px;
            text-decoration: none;
        }

        .dashboard-info-card {
            height: 100%;
            padding: 25px;
            border: 1px solid #edf0f5;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 8px 25px rgba(15,23,42,.04);
        }

        .dashboard-info-card__number {
            color: #191714;
            font-size: 26px;
            font-weight: 800;
        }

        .dashboard-action-card {
            height: 100%;
            padding: 25px;
            border-radius: 20px;
            background:
                radial-gradient(circle at 100% 0, rgba(245,154,35,.18), transparent 35%),
                #191714;
            color: #fff;
            box-shadow: 0 12px 30px rgba(17,17,17,.12);
        }

        .dashboard-action-card__icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(245,154,35,.15);
            color: #ffc65c;
            margin-bottom: 18px;
        }

        .dashboard-action-card h3 {
            margin: 0 0 8px;
            font-size: 19px;
            color: #fff;
            font-weight: 800;
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
            background: linear-gradient(110deg, #e88a16, #ffc65c);
            color: #20160b;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
        }

        .demo-label {
            display: inline-block;
            margin-left: 8px;
            padding: 4px 8px;
            border-radius: 6px;
            background: #fff1d9;
            color: #985800;
            font-size: 9px;
            font-weight: 800;
            vertical-align: middle;
        }

        @media (max-width: 991px) {
            .dashboard-modern { padding: 20px; }
            .dashboard-welcome { padding: 25px; }
            .dashboard-welcome h1 { font-size: 25px; }
            .summary-card__value { font-size: 28px; }
        }

        @media (max-width: 575px) {
            .dashboard-modern { padding: 15px; }
            .dashboard-welcome { border-radius: 17px; padding: 22px; }
            .dashboard-welcome h1 { font-size: 22px; }
            .dashboard-section__title { font-size: 19px; }
            .property-dashboard-card__image { height: 190px; }
            .dashboard-section__header { align-items: flex-start; }
        }
    </style>

    <!-- WELCOME -->
    <section class="dashboard-welcome">
        <div class="dashboard-welcome__content">
            <div class="dashboard-welcome__eyebrow">
                PAV DASHBOARD <span class="demo-label">DEMO</span>
            </div>

            <h1>
                Welcome back,<br>
                <span>Administrator</span>
            </h1>

            <p>
                Welcome to PT Pitek Balap Perkasa management dashboard.
                This page is a static preview using sample data.
            </p>
        </div>
    </section>

    <!-- SUMMARY CARDS -->
    <!-- <section class="dashboard-summary">
        <div class="row g-3">

            <div class="col-xl-3 col-md-6">
                <div class="summary-card">
                    <div class="summary-card__top">
                        <div class="summary-card__icon">
                            <i class="bi bi-buildings"></i>
                        </div>
                    </div>
                    <div class="summary-card__label">Total Properties</div>
                    <div class="summary-card__value"><?= number_format($summary['properties']); ?></div>
                    <p class="summary-card__description">Sample registered properties</p>
                    <div class="summary-card__accent"></div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="summary-card">
                    <div class="summary-card__top">
                        <div class="summary-card__icon">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>
                    <div class="summary-card__label">Available Properties</div>
                    <div class="summary-card__value"><?= number_format($summary['available']); ?></div>
                    <p class="summary-card__description">Sample available properties</p>
                    <div class="summary-card__accent"></div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="summary-card">
                    <div class="summary-card__top">
                        <div class="summary-card__icon">
                            <i class="bi bi-star"></i>
                        </div>
                    </div>
                    <div class="summary-card__label">Featured Properties</div>
                    <div class="summary-card__value"><?= number_format($summary['featured']); ?></div>
                    <p class="summary-card__description">Sample highlighted properties</p>
                    <div class="summary-card__accent"></div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="summary-card">
                    <div class="summary-card__top">
                        <div class="summary-card__icon">
                            <i class="bi bi-grid"></i>
                        </div>
                    </div>
                    <div class="summary-card__label">Property Categories</div>
                    <div class="summary-card__value"><?= number_format($summary['categories']); ?></div>
                    <p class="summary-card__description">Sample property categories</p>
                    <div class="summary-card__accent"></div>
                </div>
            </div>

        </div>
    </section> -->

    <!-- RECENT PROPERTIES -->
    <!-- <section class="dashboard-section">
        <div class="dashboard-section__header">
            <div>
                <span class="dashboard-section__eyebrow">Property Portfolio</span>
                <h2 class="dashboard-section__title">
                    Recent Properties <span class="demo-label">SAMPLE DATA</span>
                </h2>
                <p class="dashboard-section__description">
                    Example property cards for preview purposes only.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($recent_properties as $property): ?>
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="property-dashboard-card">

                        <div class="property-dashboard-card__image">
                            <img
                                src="<?= base_url($property['thumbnail']); ?>"
                                alt="<?= html_escape($property['title']); ?>"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='https://placehold.co/800x500/292017/ffc65c?text=PAV+Property';">

                            <?php if ($property['featured']): ?>
                                <span class="property-dashboard-card__featured">
                                    <i class="bi bi-star-fill me-1"></i> Featured
                                </span>
                            <?php endif; ?>

                            <span class="property-dashboard-card__status <?= $property['status'] === 'Occupied' ? 'is-occupied' : ''; ?>">
                                <?= html_escape($property['status']); ?>
                            </span>
                        </div>

                        <div class="property-dashboard-card__content">
                            <span class="property-dashboard-card__category">
                                <?= html_escape($property['category_name']); ?>
                            </span>

                            <h3 class="property-dashboard-card__title">
                                <?= html_escape($property['title']); ?>
                            </h3>

                            <div class="property-dashboard-card__location">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>
                                    <?= html_escape($property['city']); ?>,
                                    <?= html_escape($property['province']); ?>
                                </span>
                            </div>

                            <div class="property-dashboard-card__footer">
                                <span class="property-dashboard-card__type">
                                    <?= html_escape($property['asset_class']); ?>
                                </span>

                                <span class="property-dashboard-card__arrow" aria-label="Demo only">
                                    <i class="bi bi-arrow-up-right"></i>
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section> -->

    <!-- BOTTOM INFORMATION -->
    <section class="dashboard-section">
        <div class="row g-4">

            <div class="col-lg-8">
                <div class="dashboard-info-card">
                    <div class="dashboard-section__header">
                        <div>
                            <span class="dashboard-section__eyebrow">Dashboard Overview</span>
                            <h2 class="dashboard-section__title">PAV Management Preview</h2>
                            <p class="dashboard-section__description">
                                This area is a static layout preview. The figures below are illustrative only.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-2">Properties</div>
                                <div class="dashboard-info-card__number">
                                    <?= number_format($summary['properties']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-2">Categories</div>
                                <div class="dashboard-info-card__number">
                                    <?= number_format($summary['categories']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-2">Featured</div>
                                <div class="dashboard-info-card__number">
                                    <?= number_format($summary['featured']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="dashboard-action-card">
                    <div class="dashboard-action-card__icon">
                        <i class="bi bi-layout-text-window-reverse"></i>
                    </div>

                    <h3>Static Dashboard</h3>

                    <p>
                        This dashboard currently displays sample information only.
                        Database integration can be added later when required.
                    </p>

                    <span class="d-inline-flex align-items-center gap-2 small fw-bold"
                          style="color:#ffc65c;">
                        <i class="bi bi-check-circle-fill"></i>
                        Preview Mode Active
                    </span>
                </div>
            </div>

        </div>
    </section>

</div>
