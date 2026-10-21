<!-- ======================================================
Hero Section
======================================================= -->

<section class="hero" id="hero">

    <!-- Background -->
    <div class="hero__background">
        <img
            src="<?= base_url('assets/frontend/img/hero-building.jpg'); ?>"
            alt="PT Sinergi Aset Jagat Integrasi">
    </div>

    <!-- Overlay -->
    <div class="hero__overlay"></div>

    <div class="container">

        <div class="hero__wrapper">

            <div class="hero__content">

                <span
                    class="hero__subtitle"
                    data-aos="fade-up">
                    PT SINERGI ASET JAGAT INTEGRASI
                </span>

                <h1
                    class="hero__title"
                    data-aos="fade-up"
                    data-aos-delay="100">
                    Integrated Asset
                    Management &
                    Property Solutions
                </h1>

                <p
                    class="hero__description"
                    data-aos="fade-up"
                    data-aos-delay="200">
                    Integrated solutions for asset management,
                    property, business, and advisory services
                    with professionalism and integrity.
                </p>

                <div
                    class="hero__button"
                    data-aos="fade-up"
                    data-aos-delay="300">

                    <a
                        href="<?= site_url('property'); ?>"
                        class="btn-primary">
                        Explore Properties
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                    <a
                        href="https://wa.me/6281316874613?text=<?= rawurlencode(
                            'Hello, I would like to get a property consultation.'
                        ); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn-outline">
                        Contact Us
                    </a>

                </div>

            </div>

        </div>

    </div>

    <!-- Scroll Indicator -->
    <!-- <div class="hero__scroll">
        <span>Scroll Down</span>
        <div class="hero__scroll-line"></div>
    </div> -->

</section>

<div class="section-divider">
    <span></span>
</div>

<!-- ======================================================
About Section
======================================================= -->

<section class="about section" id="about">

    <div class="container">

        <!-- Section Heading -->

        <div class="section-heading text-center"
             data-aos="fade-up">

            <span class="section-subtitle">

                ABOUT US

            </span>

            <h2 class="section-title">

                Building Trust Through <br>

                Professional Asset & Property Solutions

            </h2>

        </div>

        <div class="about__wrapper">

            <!-- Image -->

            <div
                class="about__image"
                data-aos="fade-right">

                <img
                    src="<?= base_url('assets/frontend/img/about-office.png');?>"
                    alt="About">

                <div class="about__experience">

                    <h3>

                        15+

                    </h3>

                    <p>

                        Years Experience

                    </p>

                </div>

            </div>

            <!-- Content -->

            <div
                class="about__content"
                data-aos="fade-left">

                <h3>

                    PT Sinergi Aset Jagat Integrasi

                </h3>

                <p>

                    PT Sinergi Aset Jagat Integrasi provides
                    integrated consulting services in asset
                    management, collateral management,
                    property consulting, business advisory,
                    legal advisory, and real estate solutions
                    with professionalism and integrity.

                </p>

                <div class="about__features">

                    <div class="about__feature">

                        <i class="bi bi-check-circle-fill"></i>

                        Integrity & Transparency

                    </div>

                    <div class="about__feature">

                        <i class="bi bi-check-circle-fill"></i>

                        Professional Team

                    </div>

                    <div class="about__feature">

                        <i class="bi bi-check-circle-fill"></i>

                        Trusted Business Partner

                    </div>

                    <div class="about__feature">

                        <i class="bi bi-check-circle-fill"></i>

                        Nationwide Services

                    </div>

                </div>

                <a
                    href="#contact"
                    class="btn-primary mt-4">

                    Learn More

                </a>

            </div>

        </div>

    </div>

</section>

<!-- ======================================================
Why Choose Us
======================================================= -->

<section class="why section" id="why">

    <div class="container">

        <div class="section-heading text-center"
             data-aos="fade-up">

            <span class="section-subtitle">

                WHY CHOOSE US

            </span>

            <h2 class="section-title">

                Why Businesses Trust
                Our Expertise

            </h2>

        </div>

        <div class="why__wrapper">

            <div class="why-card"
                 data-aos="fade-up">

                <div class="why-card__icon">

                    <i class="bi bi-people"></i>

                </div>

                <h3>

                    Professional Team

                </h3>

                <p>

                    Experienced professionals committed
                    to delivering high-quality consulting
                    services.

                </p>

            </div>

            <div class="why-card"
                 data-aos="fade-up"
                 data-aos-delay="100">

                <div class="why-card__icon">

                    <i class="bi bi-patch-check"></i>

                </div>

                <h3>

                    Trusted Partnership

                </h3>

                <p>

                    Building long-term relationships
                    through integrity, transparency,
                    and reliability.

                </p>

            </div>

            <div class="why-card"
                 data-aos="fade-up"
                 data-aos-delay="200">

                <div class="why-card__icon">

                    <i class="bi bi-globe2"></i>

                </div>

                <h3>

                    Nationwide Services

                </h3>

                <p>

                    Supporting businesses across
                    Indonesia with integrated
                    consulting solutions.

                </p>

            </div>

            <div class="why-card"
                 data-aos="fade-up"
                 data-aos-delay="300">

                <div class="why-card__icon">

                    <i class="bi bi-lightning-charge"></i>

                </div>

                <h3>

                    Fast Response

                </h3>

                <p>

                    Responsive communication and
                    efficient project execution
                    for every client.

                </p>

            </div>

        </div>

    </div>

</section>

<div class="section-divider" style="background: #fff">
    <span></span>
</div>

<!-- ======================================================
Featured Properties
======================================================= -->

<section class="featured-property section">

    <div class="container">

        <div class="section-heading text-center">

            <span class="section-subtitle">

                FEATURED PROPERTIES

            </span>

            <h2 class="section-title">

                Selected Investment &
                Commercial Properties

            </h2>

        </div>


        <?php if (!empty($featured_properties)): ?>

            <?php
            $featured_first = $featured_properties[0];
            $featured_side = array_slice(
                $featured_properties,
                1,
                2
            );
            ?>


            <div class="featured-grid">

                <!-- ==================================================
                FEATURED LARGE
                ================================================== -->

                <a
                    href="<?= site_url(
                        'property/' .
                        $featured_first['slug']
                    ); ?>"
                    class="featured-card featured-card--large">

                    <div class="featured-card__image">

                        <img
                            src="<?= !empty(
                                $featured_first['thumbnail']
                            )
                                ? base_url(
                                    $featured_first['thumbnail']
                                )
                                : base_url(
                                    'assets/frontend/img/hero-building.jpg'
                                ); ?>"
                            alt="<?= htmlspecialchars(
                                $featured_first['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>">

                    </div>

                    <div class="featured-card__overlay"></div>

                    <div class="featured-card__content">

                        <span class="featured-card__badge">

                            <?= htmlspecialchars(
                                $featured_first['category_name']
                                    ?? 'Property',
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        </span>


                        <h3>

                            <?= htmlspecialchars(
                                $featured_first['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        </h3>


                        <p class="featured-card__location">

                            <?= htmlspecialchars(
                                $featured_first['city']
                                    ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        </p>


                        <?php if (
                            isset($featured_first['price']) &&
                            $featured_first['price'] !== '' &&
                            $featured_first['price'] !== null
                        ): ?>

                            <div class="featured-card__price">

                                Rp <?= number_format(
                                    (float) $featured_first['price'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>

                            </div>

                        <?php endif; ?>

                        <div class="featured-card__footer">

                            <span>

                                View Property

                            </span>

                            <i class="bi bi-arrow-up-right"></i>

                        </div>

                    </div>

                </a>


                <!-- ==================================================
                FEATURED RIGHT
                ================================================== -->

                <div class="featured-right">

                    <?php foreach (
                        $featured_side
                        as $featured
                    ): ?>

                        <a
                            href="<?= site_url(
                                'property/' .
                                $featured['slug']
                            ); ?>"
                            class="featured-card">

                            <div class="featured-card__image">

                                <img
                                    src="<?= !empty(
                                        $featured['thumbnail']
                                    )
                                        ? base_url(
                                            $featured['thumbnail']
                                        )
                                        : base_url(
                                            'assets/frontend/img/hero-building.jpg'
                                        ); ?>"
                                    alt="<?= htmlspecialchars(
                                        $featured['title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>">

                            </div>

                            <div class="featured-card__overlay"></div>

                            <div class="featured-card__content">

                                <span class="featured-card__badge">

                                    <?= htmlspecialchars(
                                        $featured['category_name']
                                            ?? 'Property',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </span>


                                <h3>

                                    <?= htmlspecialchars(
                                        $featured['title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </h3>


                                <p class="featured-card__location">

                                    <?= htmlspecialchars(
                                        $featured['city']
                                            ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </p>

                                <?php if (
                                    isset($featured['price']) &&
                                    $featured['price'] !== '' &&
                                    $featured['price'] !== null
                                ): ?>

                                    <div class="featured-card__price">

                                        Rp <?= number_format(
                                            (float) $featured['price'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>

                                    </div>

                                <?php endif; ?>


                                <div class="featured-card__footer">

                                    <span>

                                        View Property

                                    </span>

                                    <i class="bi bi-arrow-up-right"></i>

                                </div>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

            </div>


        <?php else: ?>

            <!-- ==================================================
            NO FEATURED PROPERTY
            ================================================== -->

            <div class="text-center py-5">

                <p>

                    No featured properties available.

                </p>

            </div>

        <?php endif; ?>


        <!-- ==================================================
        BOTTOM CTA
        ================================================== -->

        <div class="featured-bottom">

            <p>

                Explore our complete portfolio of commercial properties,
                managed assets, and investment opportunities.

            </p>

            <a
                href="<?= site_url('property'); ?>"
                class="btn-primary">

                View All Properties

            </a>

        </div>

    </div>

</section>

<div class="section-divider" style="background: #fff">
    <span></span>
</div>

<!-- ======================================================
Our Process
======================================================= -->

<section class="process section" id="process">

    <div class="container">

        <div class="section-heading text-center"
             data-aos="fade-up">

            <span class="section-subtitle">

                HOW WE WORK

            </span>

            <h2 class="section-title">

                Our Professional
                Working Process

            </h2>

            <p class="section-description">

                We follow a structured and strategic approach to deliver
                measurable, sustainable, and value-driven business solutions.

            </p>

        </div>

        <div class="process__wrapper">

            <!-- STEP 1 -->

            <div class="process-item"
                 data-aos="fade-up">

                <div class="process-item__number">

                    01

                </div>

                <h3>

                    Initial Consultation

                </h3>

                <p>

                    Understanding client objectives,
                    business challenges,
                    and project requirements.

                </p>

            </div>

            <!-- STEP 2 -->

            <div class="process-item"
                 data-aos="fade-up"
                 data-aos-delay="100">

                <div class="process-item__number">

                    02

                </div>

                <h3>

                    Assessment & Analysis

                </h3>

                <p>

                    Conducting comprehensive
                    asset, legal,
                    and business assessments.

                </p>

            </div>

            <!-- STEP 3 -->

            <div class="process-item"
                 data-aos="fade-up"
                 data-aos-delay="200">

                <div class="process-item__number">

                    03

                </div>

                <h3>

                    Strategy & Planning

                </h3>

                <p>

                    Developing customized
                    strategic solutions
                    aligned with business goals.

                </p>

            </div>

            <!-- STEP 4 -->

            <div class="process-item"
                 data-aos="fade-up"
                 data-aos-delay="300">

                <div class="process-item__number">

                    04

                </div>

                <h3>

                    Execution

                </h3>

                <p>

                    Implementing agreed
                    strategies with professional
                    project management.

                </p>

            </div>

            <!-- STEP 5 -->

            <div class="process-item"
                 data-aos="fade-up"
                 data-aos-delay="400">

                <div class="process-item__number">

                    05

                </div>

                <h3>

                    Monitoring &
                    Optimization

                </h3>

                <p>

                    Monitoring progress,
                    evaluating performance,
                    and optimizing outcomes.

                </p>

            </div>

        </div>

    </div>

</section>

<!-- ==========================================================
     CTA BANNER
========================================================== -->

<section id="contact" class="cta-banner">

    <div class="container">

        <div class="cta-banner__wrapper" data-aos="zoom-in">

            <div class="cta-banner__content">

                <span class="cta-banner__subtitle">

                    READY TO START?

                </span>

                <h2>

                    Let's Build Sustainable
                    Asset Value Together

                </h2>

                <p>

                    We help businesses, financial institutions,
                    and investors maximize asset value through
                    strategic consulting, legal advisory,
                    property management, and recovery solutions.

                </p>

            </div>

            <div class="cta-banner__highlights">

                <span><i class="bi bi-check-circle-fill"></i> Professional Team</span>

                <span><i class="bi bi-check-circle-fill"></i> Trusted Solutions</span>

                <span><i class="bi bi-check-circle-fill"></i> Nationwide Services</span>

            </div>

            <div class="cta-banner__action">

                <a
                    href="https://wa.me/6281316874613?text=Hello%2C%20I%20would%20like%20to%20get%20a%20property%20consultation."
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-gold">

                    Get Consultation

                </a>

                <a href="<?= site_url('property');?>"
                   class="btn btn-outline-light">

                    View Properties

                </a>

            </div>

        </div>

    </div>

</section>

