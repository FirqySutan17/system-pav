<style>
    .property-pagination {
        position: relative;
        z-index: 9999;
    }

    .property-pagination__button {
        position: relative;
        z-index: 10000;
        pointer-events: auto !important;
        cursor: pointer !important;
    }
</style>

<section class="page-hero">

    <div class="container">

        <div class="page-hero__content">

            <span class="page-hero__subtitle">

                PROPERTY PORTFOLIO

            </span>

            <h1>

                Discover Commercial &
                Investment Properties

            </h1>

            <nav class="breadcrumb">

                <a href="<?= site_url();?>">

                    Home

                </a>

                <span>/</span>

                <span>

                    Properties

                </span>

            </nav>

        </div>

    </div>

</section>

<!-- ==========================================================
PROPERTY FILTER
========================================================== -->

<section class="property-filter">

    <div class="container">

        <form
            action="<?= site_url('property'); ?>"
            method="get"
            class="property-filter__wrapper">

            <div class="row g-3 align-items-end">

                <!-- KEYWORD -->

                <div class="col-lg-5">

                    <label>
                        Keyword
                    </label>

                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Search property..."
                        value="<?= htmlspecialchars(
                            $filters['keyword'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>">

                </div>


                <!-- CATEGORY -->

                <div class="col-lg-3">

                    <label>
                        Category
                    </label>

                    <select
                        name="category"
                        class="form-select">

                        <option value="">
                            All Categories
                        </option>

                        <?php if (!empty($filter_categories)): ?>

                            <?php foreach (
                                $filter_categories
                                as $category
                            ): ?>

                                <option
                                    value="<?= (int) $category['id']; ?>"
                                    <?= (
                                        isset(
                                            $filters['category']
                                        ) &&
                                        (string)
                                            $filters['category']
                                        ===
                                        (string)
                                            $category['id']
                                    )
                                        ? 'selected'
                                        : ''; ?>>

                                    <?= htmlspecialchars(
                                        $category['name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </option>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </select>

                </div>


                <!-- LOCATION -->

                <div class="col-lg-2">

                    <label>
                        Location
                    </label>

                    <select
                        name="location"
                        class="form-select">

                        <option value="">
                            All Location
                        </option>

                        <?php if (!empty($filter_locations)): ?>

                            <?php foreach (
                                $filter_locations
                                as $location
                            ): ?>

                                <option
                                    value="<?= (int) $location['id']; ?>"
                                    <?= (
                                        isset(
                                            $filters['location']
                                        ) &&
                                        (string)
                                            $filters['location']
                                        ===
                                        (string)
                                            $location['id']
                                    )
                                        ? 'selected'
                                        : ''; ?>>

                                    <?= htmlspecialchars(
                                        $location['city'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                    <?php if (
                                        !empty(
                                            $location['province']
                                        )
                                    ): ?>

                                        -
                                        <?= htmlspecialchars(
                                            $location['province'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    <?php endif; ?>

                                </option>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </select>

                </div>


                <!-- SEARCH -->

                <div class="col-lg-2">

                    <button
                        type="submit"
                        class="btn btn-gold w-100">

                        <i class="bi bi-search me-2"></i>

                        Search

                    </button>

                </div>

            </div>

        </form>

    </div>

</section>

<!-- ==========================================================
PROPERTY RESULT
========================================================== -->

<section class="property-result-section">

    <div class="container">

        <div class="property-result-header">

            <div class="property-result-left">

                <span class="section-subtitle">
                    OUR PORTFOLIO
                </span>

                <h2 class="section-title">
                    Explore Our Properties
                </h2>

                <p class="section-description">

                    Showing
                    <strong>
                        <?= (int) (
                            $pagination['total_properties'] ?? 0
                        ); ?>
                    </strong>
                    available properties.

                </p>

            </div>

            <div class="property-result-right">

                <form
                    action="<?= site_url('property'); ?>"
                    method="get">

                    <?php if (!empty($filters['keyword'])): ?>

                        <input
                            type="hidden"
                            name="keyword"
                            value="<?= htmlspecialchars(
                                $filters['keyword'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>">

                    <?php endif; ?>


                    <?php if (!empty($filters['category'])): ?>

                        <input
                            type="hidden"
                            name="category"
                            value="<?= (int) $filters['category']; ?>">

                    <?php endif; ?>


                    <?php if (!empty($filters['location'])): ?>

                        <input
                            type="hidden"
                            name="location"
                            value="<?= (int) $filters['location']; ?>">

                    <?php endif; ?>


                    <label>
                        Sort by
                    </label>

                    <select
                        name="sort"
                        class="form-select"
                        onchange="this.form.submit();">

                        <option
                            value="latest"
                            <?= ($filters['sort'] ?? 'latest') === 'latest'
                                ? 'selected'
                                : ''; ?>>

                            Latest

                        </option>

                        <option
                            value="name_asc"
                            <?= ($filters['sort'] ?? '') === 'name_asc'
                                ? 'selected'
                                : ''; ?>>

                            Name A-Z

                        </option>

                        <option
                            value="name_desc"
                            <?= ($filters['sort'] ?? '') === 'name_desc'
                                ? 'selected'
                                : ''; ?>>

                            Name Z-A

                        </option>

                    </select>

                </form>

            </div>

        </div>

    </div>

</section>

<!-- ==========================================================
FEATURED PROPERTY BANNER
========================================================== -->

<?php if (!empty($featured_property)): ?>

<section class="property-featured-banner">

    <div class="container">

        <a
            href="<?= site_url(
                'property/' .
                $featured_property['slug']
            ); ?>"
            class="property-featured-banner__wrapper">


            <!-- CONTENT -->

            <div class="property-featured-banner__content">

                <div class="property-featured-banner__label">

                    <i class="bi bi-star-fill"></i>

                    <span>
                        FEATURED PROPERTY
                    </span>

                </div>


                <div class="property-featured-banner__info">

                    <h3>

                        <?= htmlspecialchars(
                            $featured_property['title'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </h3>


                    <div class="property-featured-banner__meta">

                        <span>

                            <i class="bi bi-geo-alt"></i>

                            <?= htmlspecialchars(
                                $featured_property['city']
                                    ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                            <?php if (
                                !empty(
                                    $featured_property['province']
                                )
                            ): ?>

                                ,
                                <?= htmlspecialchars(
                                    $featured_property['province'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            <?php endif; ?>

                        </span>


                        <span
                            class="property-featured-banner__dot">
                        </span>


                        <span>

                            <?= htmlspecialchars(
                                $featured_property['asset_class']
                                    ?: (
                                        $featured_property['asset_type']
                                        ?: 'Property Asset'
                                    ),
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        </span>

                    </div>

                </div>

            </div>


            <!-- ACTION -->

            <div class="property-featured-banner__action">

                <span>
                    Explore Property
                </span>

                <div
                    class="property-featured-banner__arrow">

                    <i class="bi bi-arrow-up-right"></i>

                </div>

            </div>


        </a>

    </div>

</section>

<?php endif; ?>

<!-- ==========================================================
PROPERTY GRID
========================================================== -->

<section class="property-grid">

    <div class="container">

        <div class="row gx-4 gy-5">

            <?php if (!empty($properties)): ?>

                <?php foreach ($properties as $property): ?>

                    <div class="col-lg-4 col-md-6">

                        <a
                            href="<?= site_url(
                                'property/' .
                                $property['slug']
                            ); ?>"
                            class="property-card">

                            <!-- IMAGE -->

                            <div class="property-card__image">

                                <img
                                    src="<?= !empty($property['thumbnail'])
                                        ? base_url(
                                            $property['thumbnail']
                                        )
                                        : base_url(
                                            'assets/frontend/img/property/default.jpg'
                                        ); ?>"
                                    alt="<?= htmlspecialchars(
                                        $property['title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                    loading="lazy"
                                    onerror="this.src='<?= base_url(
                                        'assets/frontend/img/property/default.jpg'
                                    ); ?>';">

                                <div class="property-card__overlay"></div>

                            </div>


                            <!-- CATEGORY -->

                            <div class="property-card__category">

                                <?= htmlspecialchars(
                                    $property['category_name']
                                        ?? 'Property',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </div>


                            <!-- CONTENT -->

                            <div class="property-card__content">


                                <!-- LABEL -->

                                <span class="property-card__label">

                                    <?= htmlspecialchars(
                                        $property['asset_class']
                                            ?: 'Property Asset',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </span>


                                <!-- TITLE -->

                                <h3 class="property-card__title">

                                    <?= htmlspecialchars(
                                        $property['title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </h3>


                                <!-- LOCATION -->

                                <div class="property-card__location">

                                    <i class="bi bi-geo-alt-fill"></i>

                                    <span>

                                        <?= htmlspecialchars(
                                            $property['city'] ?? '',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                        <?php if (
                                            !empty(
                                                $property['province']
                                            )
                                        ): ?>

                                            ,
                                            <?= htmlspecialchars(
                                                $property['province'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>

                                        <?php endif; ?>

                                    </span>

                                </div>

                                 <!-- PRICE -->
                                <?php if (
                                    isset($property['price']) &&
                                    $property['price'] !== '' &&
                                    $property['price'] !== null
                                ): ?>

                                    <div class="property-card__price">

                                        Rp <?= number_format(
                                            (float) $property['price'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>

                                    </div>

                                <?php endif; ?>

                                <!-- FOOTER -->

                                <div class="property-card__footer">

                                    <span>
                                        View Property
                                    </span>

                                    <div
                                        class="property-card__arrow">

                                        <i
                                            class="bi bi-arrow-up-right">
                                        </i>

                                    </div>

                                </div>


                            </div>

                        </a>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="col-12">

                    <div class="text-center py-5">

                        <h4>
                            No Properties Available
                        </h4>

                        <p>
                            There are currently no properties
                            available in our portfolio.
                        </p>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<!-- ==========================================================
PROPERTY PAGINATION
========================================================== -->

<?php

$current_page =
    (int) ($pagination['current_page'] ?? 1);

$total_pages =
    (int) ($pagination['total_pages'] ?? 1);


/*
|--------------------------------------------------------------------------
| BUILD PAGINATION URL
|--------------------------------------------------------------------------
*/

$build_page_url = function ($page) {

    $params = $_GET;

    $params['page'] = $page;

    return site_url('property')
        . '?'
        . http_build_query($params);
};

?>


<?php if ($total_pages > 1): ?>

<nav
    class="property-pagination"
    aria-label="Property pagination">


    <!-- PREVIOUS -->

    <?php if ($current_page > 1): ?>

        <a
            href="<?= htmlspecialchars(
                $build_page_url(
                    $current_page - 1
                ),
                ENT_QUOTES,
                'UTF-8'
            ); ?>"
            class="property-pagination__button property-pagination__arrow"
            aria-label="Previous page">

            <i class="bi bi-chevron-left"></i>

        </a>

    <?php else: ?>

        <span
            class="property-pagination__button property-pagination__arrow disabled"
            aria-disabled="true">

            <i class="bi bi-chevron-left"></i>

        </span>

    <?php endif; ?>


    <!-- PAGE NUMBERS -->

    <?php for (
        $page = 1;
        $page <= $total_pages;
        $page++
    ): ?>

        <?php if ($page === $current_page): ?>

            <span
                class="property-pagination__button active"
                aria-current="page">

                <?= $page; ?>

            </span>

        <?php else: ?>

            <a
                href="<?= htmlspecialchars(
                    $build_page_url($page),
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                class="property-pagination__button">

                <?= $page; ?>

            </a>

        <?php endif; ?>

    <?php endfor; ?>


    <!-- NEXT -->

    <?php if ($current_page < $total_pages): ?>

        <a
            href="<?= htmlspecialchars(
                $build_page_url(
                    $current_page + 1
                ),
                ENT_QUOTES,
                'UTF-8'
            ); ?>"
            class="property-pagination__button property-pagination__arrow"
            aria-label="Next page">

            <i class="bi bi-chevron-right"></i>

        </a>

    <?php else: ?>

        <span
            class="property-pagination__button property-pagination__arrow disabled"
            aria-disabled="true">

            <i class="bi bi-chevron-right"></i>

        </span>

    <?php endif; ?>


</nav>

<?php endif; ?>

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const sort =
                document.getElementById(
                    'propertySort'
                );


            if (!sort) {
                return;
            }


            sort.addEventListener(
                'change',
                function () {

                    const url =
                        new URL(
                            window.location.href
                        );


                    if (
                        this.value === 'latest'
                    ) {

                        url.searchParams.delete(
                            'sort'
                        );

                    } else {

                        url.searchParams.set(
                            'sort',
                            this.value
                        );

                    }


                    window.location.href =
                        url.toString();

                }
            );

        }
    );
</script>


