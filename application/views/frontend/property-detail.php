<style>
    /* ==========================================================
PROPERTY DOCUMENTS
========================================================== */

.property-documents-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.property-document-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 18px;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    background: #fff;
    transition: all 0.25s ease;
}

.property-document-item:hover {
    border-color: #c9a45c;
    transform: translateY(-1px);
}

.property-document-item__icon {
    width: 44px;
    height: 44px;
    min-width: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f8f5ef;
    font-size: 21px;
}

.property-document-item__info {
    flex: 1;
    min-width: 0;
}

.property-document-item__info span {
    display: block;
    margin-bottom: 3px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #999;
}

.property-document-item__info strong {
    display: block;
    font-size: 15px;
    font-weight: 600;
    color: #222;
}

.property-document-item__action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    color: #222;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
}

.property-document-item__action:hover {
    color: #b08a3c;
}

.property-document-item__action i {
    font-size: 14px;
}

@media (max-width: 767.98px) {

    .property-document-item {
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .property-document-item__info {
        flex: 1;
    }

    .property-document-item__action {
        width: 100%;
        padding-left: 60px;
    }

}
</style>

<!-- ==========================================================
PROPERTY DETAIL HEADER
========================================================== -->

<section class="property-detail-header">

    <div class="container">

        <!-- Breadcrumb -->

        <nav class="property-breadcrumb">

            <a href="<?= site_url(); ?>">

                Home

            </a>

            <i class="bi bi-chevron-right"></i>

            <a href="<?= site_url('property'); ?>">

                Properties

            </a>

            <i class="bi bi-chevron-right"></i>

            <span>

                <?= htmlspecialchars(
    $property['title'],
    ENT_QUOTES,
    'UTF-8'
); ?>

            </span>

        </nav>


        <!-- Property Header -->

        <div class="property-detail-header__content">

            <div class="property-detail-header__main">

                <span class="property-detail-header__category">

                    <?= htmlspecialchars(
                        $property['category_name'] ?? 'Property',
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>

                </span>

                <h1>

                    <?= htmlspecialchars(
                        $property['title'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>

                </h1>

                <div class="property-detail-header__meta">

                    <span>

                        <i class="bi bi-geo-alt-fill"></i>

                        <?= htmlspecialchars(
                            $property['city'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                        <?php if (!empty($property['province'])): ?>

                            ,
                            <?= htmlspecialchars(
                                $property['province'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        <?php endif; ?>

                    </span>


                    <span class="property-detail-header__dot"></span>


                    <span>

                        <?= htmlspecialchars(
                            $property['asset_class']
                                ?: (
                                    $property['asset_type']
                                    ?: 'Property Asset'
                                ),
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </span>

                </div>

                <?php if (!empty($property['price'])): ?>

                    <div class="property-detail-header__price">

                        <!-- <span class="property-detail-header__price-label">
                            Asking Price
                        </span> -->

                        <strong>
                            Rp <?= number_format(
                                (float) $property['price'],
                                0,
                                ',',
                                '.'
                            ); ?>
                        </strong>

                    </div>

                <?php endif; ?>

            </div>


            <!-- Header Action -->

            <div class="property-detail-header__actions">

                <button
                    type="button"
                    class="property-action-button"
                    aria-label="Share property"
                    id="sharePropertyButton">

                    <i class="bi bi-share"></i>

                </button>

                <a
                    href="https://wa.me/6281316874613?text=<?= rawurlencode(
                        'Hello, I am interested in the property "' .
                        $property['title'] .
                        '" and would like to get more information.'
                    ); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="property-detail-header__contact">

                    Contact About Property

                    <i class="bi bi-whatsapp"></i>

                </a>

            </div>

        </div>

    </div>

</section>

<!-- ==========================================================
PROPERTY GALLERY
========================================================== -->

<section class="property-gallery">

    <div class="container">

        <div class="property-gallery__grid">


            <!-- ==================================================
            MAIN IMAGE
            ================================================== -->

            <div
                class="property-gallery__item property-gallery__main"
                data-open-gallery="0">

                <img
                    src="<?= base_url(
                        $property['images'][0]['image']
                    ); ?>"
                    alt="<?= htmlspecialchars(
                        $property['title'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>"
                    data-gallery-index="0">

                <div class="property-gallery__overlay"></div>

                <button
                    type="button"
                    class="property-gallery__expand"
                    data-open-gallery="0"
                    aria-label="Open Gallery">

                    <i class="bi bi-arrows-fullscreen"></i>

                </button>

            </div>


            <!-- ==================================================
            SIDE IMAGES
            ================================================== -->

            <div class="property-gallery__side">

                <!-- IMAGE 2 -->

                <?php if (!empty($property['images'][1])): ?>

                    <div
                        class="property-gallery__item"
                        data-open-gallery="1">

                        <img
                            src="<?= base_url(
                                $property['images'][1]['image']
                            ); ?>"
                            alt="<?= htmlspecialchars(
                                $property['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            data-gallery-index="1">

                        <div class="property-gallery__overlay"></div>

                    </div>

                <?php else: ?>

                    <div
                        class="property-gallery__item"
                        data-open-gallery="0">

                        <img
                            src="<?= base_url(
                                $property['images'][0]['image']
                            ); ?>"
                            alt="<?= htmlspecialchars(
                                $property['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>">

                        <div class="property-gallery__overlay"></div>

                    </div>

                <?php endif; ?>


                <!-- IMAGE 3 -->

                <?php if (!empty($property['images'][2])): ?>

                    <div
                        class="property-gallery__item"
                        data-open-gallery="2">

                        <img
                            src="<?= base_url(
                                $property['images'][2]['image']
                            ); ?>"
                            alt="<?= htmlspecialchars(
                                $property['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            data-gallery-index="2">

                        <div class="property-gallery__overlay"></div>


                        <!-- VIEW ALL -->

                        <button
                            type="button"
                            class="property-gallery__view-all"
                            data-open-gallery="0">

                            <i class="bi bi-images"></i>

                            <div>

                                <strong>
                                    View Gallery
                                </strong>

                                <small>
                                    <?= count($property['images']); ?> Photos
                                </small>

                            </div>

                        </button>

                    </div>

                <?php elseif (count($property['images']) > 1): ?>

                    <div
                        class="property-gallery__item"
                        data-open-gallery="1">

                        <img
                            src="<?= base_url(
                                $property['images'][1]['image']
                            ); ?>"
                            alt="<?= htmlspecialchars(
                                $property['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>">

                        <div class="property-gallery__overlay"></div>


                        <button
                            type="button"
                            class="property-gallery__view-all"
                            data-open-gallery="0">

                            <i class="bi bi-images"></i>

                            <div>

                                <strong>
                                    View Gallery
                                </strong>

                                <small>
                                    <?= count($property['images']); ?> Photos
                                </small>

                            </div>

                        </button>

                    </div>

                <?php else: ?>

                    <div
                        class="property-gallery__item"
                        data-open-gallery="0">

                        <img
                            src="<?= base_url(
                                $property['images'][0]['image']
                            ); ?>"
                            alt="<?= htmlspecialchars(
                                $property['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>">

                        <div class="property-gallery__overlay"></div>

                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- ==================================================
        MOBILE BAR
        ================================================== -->

        <div class="property-gallery__mobile-bar">

            <span>

                <i class="bi bi-images"></i>

                <?= count($property['images']); ?> Photos

            </span>

            <button
                type="button"
                data-open-gallery="0">

                View All Photos

                <i class="bi bi-arrow-up-right"></i>

            </button>

        </div>

    </div>

</section>

<!-- ==========================================================
PROPERTY DETAIL CONTENT
========================================================== -->

<section class="property-detail-content">

    <div class="container">

        <div class="property-detail-layout">


            <!-- ==================================================
            MAIN CONTENT
            ================================================== -->

            <div class="property-detail-main">


                <!-- Property Overview -->

                <div class="property-detail-block">

                    <div class="property-section-label">

                        PROPERTY OVERVIEW

                    </div>

                    <h2>

                        About This Property

                    </h2>

                    <div class="property-description">

                        <?= nl2br(
                            htmlspecialchars(
                                trim($property['description'] ?? ''),
                                ENT_QUOTES,
                                'UTF-8'
                            )
                        ); ?>

                    </div>

                </div>


                <!-- ==================================================
                PROPERTY INFORMATION
                ================================================== -->

                <div class="property-detail-block">

                    <div class="property-block-heading">

                        <div>

                            <div class="property-section-label">

                                PROPERTY DETAILS

                            </div>

                            <h2>

                                Property Information

                            </h2>

                        </div>

                    </div>


                    <div class="property-info-grid">


                        <!-- Property Type -->

                        <div class="property-info-item">

                            <div class="property-info-item__icon">

                                <i class="bi bi-building"></i>

                            </div>

                            <div>

                                <span>

                                    Property Type

                                </span>

                                <strong>

                                    <?= htmlspecialchars(
                                        $property['category_name'] ?? 'Property',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </strong>

                            </div>

                        </div>


                        <!-- Building Class -->

                        <div class="property-info-item">

                            <div class="property-info-item__icon">

                                <i class="bi bi-award"></i>

                            </div>

                            <div>

                                <span>

                                    Building Class

                                </span>

                                <strong>

                                    <?= htmlspecialchars(
                                        $property['asset_class'] ?? '—',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </strong>

                            </div>

                        </div>


                        <!-- Status -->

                        <div class="property-info-item">

                            <div class="property-info-item__icon">

                                <i class="bi bi-check-circle"></i>

                            </div>

                            <div>

                                <span>

                                    Status

                                </span>

                                <strong>

                                    <?= htmlspecialchars(
                                        ucfirst(
                                            $property['status'] ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </strong>

                            </div>

                        </div>


                        <!-- Building Area -->

                        <div class="property-info-item">

                            <div class="property-info-item__icon">

                                <i class="bi bi-bounding-box"></i>

                            </div>

                            <div>

                                <span>

                                    Building Area

                                </span>

                                <strong>

                                    <?= !empty($property['building_area'])
                                    ? htmlspecialchars(
                                        $property['building_area'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) . ' m²'
                                    : '—'; ?>

                                </strong>

                            </div>

                        </div>


                        <!-- Land Area -->

                        <div class="property-info-item">

                            <div class="property-info-item__icon">

                                <i class="bi bi-aspect-ratio"></i>

                            </div>

                            <div>

                                <span>

                                    Land Area

                                </span>

                                <strong>

                                    <?= !empty($property['land_area'])
                                    ? htmlspecialchars(
                                        $property['land_area'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) . ' m²'
                                    : '—'; ?>

                                </strong>

                            </div>

                        </div>


                        <!-- Year Built -->

                        <div class="property-info-item">

                            <div class="property-info-item__icon">

                                <i class="bi bi-calendar3"></i>

                            </div>

                            <div>

                                <span>
                                    Year Built
                                </span>

                                <strong>

                                    <?= !empty($property['year_built'])
                                        ? htmlspecialchars(
                                            $property['year_built'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        )
                                        : '—'; ?>

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ==================================================
                PROPERTY DOCUMENTS
                ================================================== -->

                <?php if (!empty($property['documents'])): ?>

                    <div class="property-detail-block">

                        <div class="property-block-heading">

                            <div>

                                <div class="property-section-label">

                                    PROPERTY DOCUMENTS

                                </div>

                                <h2>

                                    Property Documents

                                </h2>

                            </div>

                        </div>


                        <div class="property-documents-list">

                            <?php foreach (
                                $property['documents']
                                as $document
                            ): ?>

                                <div class="property-document-item">

                                    <div class="property-document-item__icon">

                                        <?php
                                        $extension = strtolower(
                                            pathinfo(
                                                $document['file'],
                                                PATHINFO_EXTENSION
                                            )
                                        );
                                        ?>

                                        <?php if ($extension === 'pdf'): ?>

                                            <i class="bi bi-file-earmark-pdf"></i>

                                        <?php elseif (
                                            in_array(
                                                $extension,
                                                ['doc', 'docx'],
                                                true
                                            )
                                        ): ?>

                                            <i class="bi bi-file-earmark-word"></i>

                                        <?php elseif (
                                            in_array(
                                                $extension,
                                                ['xls', 'xlsx'],
                                                true
                                            )
                                        ): ?>

                                            <i class="bi bi-file-earmark-excel"></i>

                                        <?php else: ?>

                                            <i class="bi bi-file-earmark-text"></i>

                                        <?php endif; ?>

                                    </div>


                                    <div class="property-document-item__info">

                                        <span>
                                            Property Document
                                        </span>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $document['title'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>

                                        </strong>

                                    </div>


                                    <a
                                        href="<?= base_url(
                                            $document['file']
                                        ); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="property-document-item__action">

                                        <span>
                                            View Document
                                        </span>

                                        <i class="bi bi-arrow-up-right"></i>

                                    </a>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                <?php endif; ?>

                <!-- ==================================================
                LOCATION
                ================================================== -->

                <div class="property-detail-block">

                    <div class="property-section-label">

                        LOCATION

                    </div>

                    <h2>

                        Strategic Location

                    </h2>

                    <div class="property-location-box">

                        <div class="property-location-box__icon">

                            <i class="bi bi-geo-alt-fill"></i>

                        </div>

                        <div>

                            <span>

                                Property Location

                            </span>

                            <h3>

                                <?= htmlspecialchars(
                                    $property['city'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                                <?php if (!empty($property['province'])): ?>

                                    ,
                                    <?= htmlspecialchars(
                                        $property['province'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                <?php endif; ?>

                            </h3>

                            <p>

                                Strategically located with convenient
                                access to major business districts,
                                transportation networks, and supporting
                                commercial facilities.

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ==================================================
            SIDEBAR
            ================================================== -->

            <aside
                class="property-detail-sidebar"
                id="property-inquiry">

                <div class="property-inquiry-card">


                    <div class="property-inquiry-card__icon">

                        <i class="bi bi-chat-dots"></i>

                    </div>


                    <span class="property-inquiry-card__label">

                        PROPERTY INQUIRY

                    </span>


                    <h3>

                        Interested in This Property?

                    </h3>


                    <p>

                        Connect with our professional team
                        for detailed property information,
                        availability, or consultation.

                    </p>


                    <div class="property-inquiry-card__property">

                        <span>

                            You're inquiring about

                        </span>

                        <strong>

                            <?= htmlspecialchars(
                                $property['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        </strong>

                        <small>

                            <i class="bi bi-geo-alt-fill"></i>

                            <?= htmlspecialchars(
                                $property['city'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                            <?php if (!empty($property['province'])): ?>

                                ,
                                <?= htmlspecialchars(
                                    $property['province'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            <?php endif; ?>

                        </small>

                    </div>


                    <!-- WhatsApp -->

                    <a
                        href="https://wa.me/6281316874613?text=<?= rawurlencode(
                            'Hello, I am interested in the property "' .
                            $property['title'] .
                            '" and would like to get more information.'
                        ); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="property-inquiry-whatsapp">

                        <i class="bi bi-whatsapp"></i>

                        <span>

                            Contact via WhatsApp

                        </span>

                        <i class="bi bi-arrow-up-right"></i>

                    </a>

                    <!-- General Contact -->

                    <a
                        href="https://wa.me/6281316874613?text=<?= rawurlencode(
                            'Hello, I would like to get a property consultation.'
                        ); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="property-inquiry-contact">

                        Contact Our Team

                        <i class="bi bi-arrow-right"></i>

                    </a>

                    <div class="property-inquiry-card__note">

                        <i class="bi bi-shield-check"></i>

                        <span>

                            Professional consultation
                            with our property team.

                        </span>

                    </div>

                </div>

            </aside>

        </div>

    </div>

</section>

<!-- ==========================================================
CTA PROPERTY
========================================================== -->

<section class="property-cta">
    <div class="container">
        <div class="property-cta-box">

            <span>

                PROPERTY CONSULTATION

            </span>

            <h2>

                Need Professional
                Property Consultation?

            </h2>

            <p>

                Whether you're looking for commercial,
                industrial, or investment properties,
                our team is ready to assist you.

            </p>

            <a
                href="https://wa.me/6281316874613?text=<?= rawurlencode(
                            'Hello, I would like to get a property consultation.'
                        ); ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-gold">

                <i class="bi bi-whatsapp"></i>

                Contact via WhatsApp

            </a>

        </div>
    </div>
</section>

<!-- ==========================================================
PROPERTY GALLERY LIGHTBOX
========================================================== -->

<div
    class="property-lightbox"
    id="propertyLightbox"
    aria-hidden="true">

    <div class="property-lightbox__backdrop"></div>


    <!-- Header -->

    <div class="property-lightbox__header">

        <div class="property-lightbox__counter">

            <span id="lightboxCurrent">
                1
            </span>

            <span>/</span>

            <span>
                <?= count($property['images']); ?>
            </span>

        </div>


        <button
            type="button"
            class="property-lightbox__close"
            id="lightboxClose"
            aria-label="Close gallery">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>


    <!-- Main Image -->

    <div class="property-lightbox__stage">

        <button
            type="button"
            class="property-lightbox__nav property-lightbox__nav--prev"
            id="lightboxPrev"
            aria-label="Previous image">

            <i class="bi bi-chevron-left"></i>

        </button>


        <div class="property-lightbox__image">

            <img
                src=""
                id="lightboxImage"
                alt="<?= htmlspecialchars($property['title']); ?>">

        </div>


        <button
            type="button"
            class="property-lightbox__nav property-lightbox__nav--next"
            id="lightboxNext"
            aria-label="Next image">

            <i class="bi bi-chevron-right"></i>

        </button>

    </div>


    <!-- Thumbnails -->

    <div class="property-lightbox__thumbnails">

        <?php foreach (
            $property['images']
            as $index => $image
        ): ?>

            <button
                type="button"
                class="property-lightbox__thumbnail <?= $index === 0 ? 'active' : ''; ?>"
                data-index="<?= $index; ?>">

                <img
                    src="<?= base_url(
                        $image['image']
                    ); ?>"
                    alt="<?= htmlspecialchars(
                        $image['caption']
                            ?: $property['title'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>">

            </button>

        <?php endforeach; ?>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const shareButton =
            document.getElementById('sharePropertyButton');

        if (!shareButton) {
            return;
        }

        shareButton.addEventListener('click', async function () {

            const shareData = {
                title: <?= json_encode($property['title']); ?>,
                text: <?= json_encode(
                    'Check out this property: ' .
                    $property['title']
                ); ?>,
                url: window.location.href
            };

            /*
            |--------------------------------------------------------------------------
            | NATIVE SHARE
            |--------------------------------------------------------------------------
            */

            if (navigator.share) {

                try {

                    await navigator.share(shareData);

                } catch (error) {

                    /*
                    | User cancelled share dialog.
                    | No action needed.
                    */

                    if (error.name !== 'AbortError') {
                        console.error(
                            'Share failed:',
                            error
                        );
                    }

                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | FALLBACK - COPY LINK
            |--------------------------------------------------------------------------
            */

            try {

                await navigator.clipboard.writeText(
                    window.location.href
                );

                alert(
                    'Property link copied to clipboard.'
                );

            } catch (error) {

                /*
                |--------------------------------------------------------------------------
                | FINAL FALLBACK
                |--------------------------------------------------------------------------
                */

                window.prompt(
                    'Copy this property link:',
                    window.location.href
                );

            }

        });

    });
</script>