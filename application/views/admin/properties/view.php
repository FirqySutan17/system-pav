<div class="container-fluid property-detail-page">

<style>

/* ==========================================================
   PAGE
========================================================== */

.property-detail-page {
    padding: 28px;
    min-height: calc(100vh - 80px);
}


/* ==========================================================
   HEADER
========================================================== */

.property-detail-header {
    margin-bottom: 25px;
}

.property-detail-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    margin-bottom: 14px;

    color: #94a3b8;
    font-size: 12px;
    text-decoration: none;
}

.property-detail-back:hover {
    color: #071b3a;
}

.property-detail-eyebrow {
    display: block;

    margin-bottom: 6px;

    color: #c99a21;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.8px;
    text-transform: uppercase;
}

.property-detail-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.property-detail-title {
    margin: 0;

    color: #071b3a;
    font-size: 27px;
    font-weight: 750;
}

.property-detail-subtitle {
    margin: 7px 0 0;

    color: #8993a4;
    font-size: 13px;
}


/* ==========================================================
   ACTION
========================================================== */

.property-detail-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.btn-property-edit,
.btn-property-delete {
    min-height: 40px;

    padding: 0 16px;

    border-radius: 9px;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;

    font-size: 12px;
    font-weight: 700;

    text-decoration: none;

    transition: .2s ease;
}

.btn-property-edit {
    background: #071b3a;
    color: #fff;
}

.btn-property-edit:hover {
    background: #0d2b55;
    color: #fff;
}

.btn-property-delete {
    background: #fff;
    border: 1px solid #fecaca;
    color: #dc2626;
}

.btn-property-delete:hover {
    background: #fef2f2;
    color: #b91c1c;
}


/* ==========================================================
   HERO
========================================================== */

.property-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.8fr) minmax(280px, 1fr);

    gap: 20px;

    margin-bottom: 20px;
}


/* ==========================================================
   COVER
========================================================== */

.property-cover-card {
    position: relative;

    overflow: hidden;

    min-height: 430px;

    background: #f1f5f9;

    border: 1px solid #edf0f5;
    border-radius: 18px;

    box-shadow:
        0 8px 25px rgba(15,23,42,.05);
}

.property-cover-card img {
    width: 100%;
    height: 430px;

    object-fit: cover;

    display: block;
}

.property-cover-empty {
    height: 430px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    color: #94a3b8;
}

.property-cover-empty i {
    font-size: 45px;
    margin-bottom: 10px;
}


/* COVER BADGE */

.property-cover-badge {
    position: absolute;

    top: 16px;
    left: 16px;

    padding: 7px 11px;

    border-radius: 7px;

    background: rgba(7,27,58,.88);

    color: #fff;

    font-size: 10px;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
}


/* ==========================================================
   SUMMARY CARD
========================================================== */

.property-summary-card {
    background: #fff;

    border: 1px solid #edf0f5;
    border-radius: 18px;

    padding: 25px;

    box-shadow:
        0 8px 25px rgba(15,23,42,.05);
}

.property-summary-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 25px;
}

.property-summary-label {
    color: #c99a21;

    font-size: 10px;
    font-weight: 700;

    letter-spacing: 1.5px;
    text-transform: uppercase;

    margin-bottom: 6px;
}

.property-summary-title {
    margin: 0;

    color: #071b3a;

    font-size: 20px;
    font-weight: 750;

    line-height: 1.35;
}


/* STATUS */

.property-status {
    display: inline-flex;
    align-items: center;

    padding: 6px 10px;

    border-radius: 20px;

    font-size: 10px;
    font-weight: 700;

    text-transform: uppercase;

    white-space: nowrap;
}

.property-status.available {
    background: #ecfdf5;
    color: #059669;
}

.property-status.reserved {
    background: #fffbeb;
    color: #d97706;
}

.property-status.sold {
    background: #fef2f2;
    color: #dc2626;
}


/* FEATURED */

.property-featured {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    margin-top: 10px;

    color: #c99a21;

    font-size: 11px;
    font-weight: 700;
}


/* ==========================================================
   QUICK INFO
========================================================== */

.property-quick-info {
    display: grid;

    grid-template-columns: 1fr 1fr;

    border-top: 1px solid #eef1f5;
}

.property-quick-item {
    padding: 15px 0;

    border-bottom: 1px solid #eef1f5;
}

.property-quick-item:nth-child(odd) {
    padding-right: 15px;
}

.property-quick-item:nth-child(even) {
    padding-left: 15px;

    border-left: 1px solid #eef1f5;
}

.property-quick-label {
    color: #94a3b8;

    font-size: 10px;
    font-weight: 700;

    letter-spacing: .8px;
    text-transform: uppercase;

    margin-bottom: 5px;
}

.property-quick-value {
    color: #334155;

    font-size: 12px;
    font-weight: 600;

    line-height: 1.5;
}


/* ==========================================================
   CONTENT CARD
========================================================== */

.property-content-card {
    background: #fff;

    border: 1px solid #edf0f5;
    border-radius: 18px;

    padding: 25px;

    box-shadow:
        0 8px 25px rgba(15,23,42,.05);

    margin-bottom: 20px;
}

.property-section-label {
    color: #c99a21;

    font-size: 10px;
    font-weight: 700;

    letter-spacing: 1.5px;
    text-transform: uppercase;

    margin-bottom: 5px;
}

.property-section-title {
    margin: 0 0 20px;

    color: #071b3a;

    font-size: 17px;
    font-weight: 700;
}


/* ==========================================================
   PROPERTY DATA
========================================================== */

.property-data-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 1px;

    background: #eef1f5;

    border: 1px solid #eef1f5;
    border-radius: 12px;

    overflow: hidden;
}

.property-data-item {
    background: #fff;

    padding: 15px;
}

.property-data-label {
    color: #94a3b8;

    font-size: 10px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .7px;

    margin-bottom: 6px;
}

.property-data-value {
    color: #334155;

    font-size: 12px;
    font-weight: 600;

    line-height: 1.5;
}


/* ==========================================================
   LOCATION
========================================================== */

.property-location-box {
    display: flex;
    align-items: flex-start;

    gap: 12px;

    padding: 16px;

    background: #f8fafc;

    border: 1px solid #eef1f5;
    border-radius: 12px;
}

.property-location-icon {
    width: 38px;
    height: 38px;

    flex: 0 0 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #071b3a;

    color: #fff;

    font-size: 18px;
}

.property-location-city {
    color: #071b3a;

    font-size: 13px;
    font-weight: 700;

    margin-bottom: 3px;
}

.property-location-address {
    color: #64748b;

    font-size: 12px;

    line-height: 1.6;
}


/* ==========================================================
   DESCRIPTION
========================================================== */

.property-description {
    color: #475569;

    font-size: 13px;

    line-height: 1.8;

    white-space: pre-line;
}

.property-short-description {
    color: #64748b;

    font-size: 13px;

    line-height: 1.7;

    margin-bottom: 20px;
}


/* ==========================================================
   GALLERY
========================================================== */

.property-gallery {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 12px;
}

.property-gallery-item {
    position: relative;

    overflow: hidden;

    aspect-ratio: 4 / 3;

    border-radius: 12px;

    background: #f1f5f9;

    cursor: pointer;
}

.property-gallery-item img {
    width: 100%;
    height: 100%;

    object-fit: cover;

    display: block;

    transition: transform .3s ease;
}

.property-gallery-item:hover img {
    transform: scale(1.04);
}

.property-gallery-cover {
    position: absolute;

    top: 8px;
    left: 8px;

    padding: 5px 8px;

    border-radius: 6px;

    background: rgba(7,27,58,.88);

    color: #fff;

    font-size: 9px;
    font-weight: 700;

    text-transform: uppercase;
}


/* ==========================================================
   DOCUMENTS
========================================================== */

.property-document-list {
    display: flex;
    flex-direction: column;

    gap: 8px;
}

.property-document-item {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 13px 15px;

    border: 1px solid #eef1f5;
    border-radius: 10px;

    transition: .2s ease;
}

.property-document-item:hover {
    border-color: #dbe3ec;
    background: #f8fafc;
}

.property-document-info {
    display: flex;
    align-items: center;

    gap: 10px;

    min-width: 0;
}

.property-document-icon {
    width: 35px;
    height: 35px;

    flex: 0 0 35px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    background: #fef2f2;

    color: #dc2626;
}

.property-document-title {
    color: #334155;

    font-size: 12px;
    font-weight: 650;

    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.property-document-download {
    width: 34px;
    height: 34px;

    flex: 0 0 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    color: #071b3a;

    text-decoration: none;

    background: #f8fafc;
}

.property-document-download:hover {
    background: #071b3a;
    color: #fff;
}


/* ==========================================================
   LIGHTBOX
========================================================== */

.property-lightbox {
    position: fixed;

    inset: 0;

    z-index: 9999;

    display: none;

    align-items: center;
    justify-content: center;

    padding: 30px;

    background: rgba(7,27,58,.92);
}

.property-lightbox.show {
    display: flex;
}

.property-lightbox img {
    max-width: 90vw;
    max-height: 85vh;

    object-fit: contain;

    border-radius: 10px;

    box-shadow:
        0 20px 60px rgba(0,0,0,.3);
}

.property-lightbox-close {
    position: absolute;

    top: 20px;
    right: 25px;

    width: 42px;
    height: 42px;

    border: 0;
    border-radius: 50%;

    background: rgba(255,255,255,.12);

    color: #fff;

    font-size: 22px;

    cursor: pointer;
}


/* ==========================================================
   MOBILE
========================================================== */

@media(max-width:991px) {

    .property-hero {
        grid-template-columns: 1fr;
    }

    .property-data-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .property-gallery {
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
    }

}


@media(max-width:768px) {

    .property-detail-page {
        padding: 18px 15px;
    }

    .property-detail-title-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .property-detail-actions {
        width: 100%;
    }

    .btn-property-edit,
    .btn-property-delete {
        flex: 1;
    }

    .property-cover-card,
    .property-cover-card img,
    .property-cover-empty {
        min-height: 280px;
        height: 280px;
    }

    .property-summary-card,
    .property-content-card {
        padding: 18px;
    }

    .property-data-grid {
        grid-template-columns: 1fr;
    }

    .property-gallery {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}

</style>


<?php

/* ==========================================================
   BASIC DATA
========================================================== */

$propertyId = (int) $property['id'];

$status = strtolower(
    $property['status'] ?? 'available'
);

$statusLabel = ucfirst(
    $status
);


/* ==========================================================
   IMAGE PATH
========================================================== */

$imageBaseUrl =
    base_url('assets/frontend/img/property/');


$thumbnail =
    !empty($property['thumbnail'])
        ? base_url($property['thumbnail'])
        : null;


/* ==========================================================
   LOCATION
========================================================== */

$location = trim(
    ($property['city'] ?? '') .
    (
        !empty($property['province'])
            ? ', ' . $property['province']
            : ''
    )
);


/* ==========================================================
   IMAGES
========================================================== */

$images =
    !empty($property['images'])
        ? $property['images']
        : [];


/* ==========================================================
   DOCUMENTS
========================================================== */

$documents =
    !empty($property['documents'])
        ? $property['documents']
        : [];

?>


<!-- ==========================================================
     HEADER
========================================================== -->

<div class="property-detail-header">

    <a
        href="<?= site_url('properties'); ?>"
        class="property-detail-back">

        <i class="ti ti-arrow-left"></i>

        Back to Properties

    </a>


    <span class="property-detail-eyebrow">

        Property Management

    </span>


    <div class="property-detail-title-row">

        <div>

            <h1 class="property-detail-title">

                <?= htmlspecialchars(
                    $property['title']
                ); ?>

            </h1>

            <p class="property-detail-subtitle">

                <?= htmlspecialchars(
                    $property['category_name']
                    ?? 'Uncategorized'
                ); ?>

                <?php if ($location): ?>

                    · <?= htmlspecialchars($location); ?>

                <?php endif; ?>

            </p>

        </div>


        <div class="property-detail-actions">

            <a
                href="<?= site_url(
                    'properties/edit/' . $propertyId
                ); ?>"
                class="btn-property-edit">

                <i class="ti ti-edit"></i>

                Edit

            </a>


            <a
                href="javascript:void(0)"
                class="btn-property-delete"
                id="deletePropertyBtn"
                data-id="<?= $propertyId; ?>"
                data-title="<?= htmlspecialchars(
                    $property['title'],
                    ENT_QUOTES
                ); ?>">

                <i class="ti ti-trash"></i>

                Delete

            </a>

        </div>

    </div>

</div>


<!-- ==========================================================
     HERO
========================================================== -->

<div class="property-hero">


    <!-- COVER -->

    <div class="property-cover-card">

        <?php if ($thumbnail): ?>

            <img
                src="<?= htmlspecialchars($thumbnail); ?>"
                alt="<?= htmlspecialchars(
                    $property['title']
                ); ?>"
                onerror="this.style.display='none';">

            <div class="property-cover-badge">

                <i class="ti ti-photo"></i>

                Cover Image

            </div>

        <?php else: ?>

            <div class="property-cover-empty">

                <i class="ti ti-photo-off"></i>

                <span>
                    No cover image available
                </span>

            </div>

        <?php endif; ?>

    </div>


    <!-- SUMMARY -->

    <div class="property-summary-card">

        <div class="property-summary-top">

            <div>

                <div class="property-summary-label">

                    Property

                </div>

                <h2 class="property-summary-title">

                    <?= htmlspecialchars(
                        $property['title']
                    ); ?>

                </h2>


                <?php if (
                    !empty($property['featured']) &&
                    $property['featured'] == 1
                ): ?>

                    <div class="property-featured">

                        <i class="ti ti-star-filled"></i>

                        Featured Property

                    </div>

                <?php endif; ?>

            </div>


            <span
                class="property-status <?= htmlspecialchars(
                    $status
                ); ?>">

                <?= htmlspecialchars(
                    $statusLabel
                ); ?>

            </span>

        </div>


        <div class="property-quick-info">


            <div class="property-quick-item">

                <div class="property-quick-label">

                    Category

                </div>

                <div class="property-quick-value">

                    <?= htmlspecialchars(
                        $property['category_name']
                        ?? '-'
                    ); ?>

                </div>

            </div>


            <div class="property-quick-item">

                <div class="property-quick-label">

                    Asset Type

                </div>

                <div class="property-quick-value">

                    <?= htmlspecialchars(
                        $property['asset_type']
                        ?? '-'
                    ); ?>

                </div>

            </div>


            <div class="property-quick-item">

                <div class="property-quick-label">

                    Asset Class

                </div>

                <div class="property-quick-value">

                    <?= htmlspecialchars(
                        $property['asset_class']
                        ?? '-'
                    ); ?>

                </div>

            </div>


            <div class="property-quick-item">

                <div class="property-quick-label">

                    Year Built

                </div>

                <div class="property-quick-value">

                    <?= !empty(
                        $property['year_built']
                    )
                        ? htmlspecialchars(
                            $property['year_built']
                        )
                        : '-'; ?>

                </div>

            </div>


        </div>

    </div>

</div>


<!-- ==========================================================
     PROPERTY INFORMATION
========================================================== -->

<div class="property-content-card">

    <div class="property-section-label">

        Property Information

    </div>

    <h2 class="property-section-title">

        Property Details

    </h2>


    <div class="property-data-grid">


        <div class="property-data-item">

            <div class="property-data-label">
                Asset Class
            </div>

            <div class="property-data-value">

                <?= htmlspecialchars(
                    $property['asset_class']
                    ?? '-'
                ); ?>

            </div>

        </div>


        <div class="property-data-item">

            <div class="property-data-label">
                Asset Type
            </div>

            <div class="property-data-value">

                <?= htmlspecialchars(
                    $property['asset_type']
                    ?? '-'
                ); ?>

            </div>

        </div>


        <div class="property-data-item">

            <div class="property-data-label">
                Land Area
            </div>

            <div class="property-data-value">

                <?= !empty(
                    $property['land_area']
                )
                    ? number_format(
                        (float) $property['land_area'],
                        2,
                        ',',
                        '.'
                    )
                    : '-'; ?>

            </div>

        </div>


        <div class="property-data-item">

            <div class="property-data-label">
                Building Area
            </div>

            <div class="property-data-value">

                <?= !empty(
                    $property['building_area']
                )
                    ? number_format(
                        (float) $property['building_area'],
                        2,
                        ',',
                        '.'
                    )
                    : '-'; ?>

            </div>

        </div>


        <div class="property-data-item">

            <div class="property-data-label">
                Year Built
            </div>

            <div class="property-data-value">

                <?= !empty(
                    $property['year_built']
                )
                    ? htmlspecialchars(
                        $property['year_built']
                    )
                    : '-'; ?>

            </div>

        </div>


        <div class="property-data-item">

            <div class="property-data-label">
                Status
            </div>

            <div class="property-data-value">

                <?= htmlspecialchars(
                    $statusLabel
                ); ?>

            </div>

        </div>


        <div class="property-data-item">

            <div class="property-data-label">
                Featured
            </div>

            <div class="property-data-value">

                <?= !empty(
                    $property['featured']
                ) && $property['featured'] == 1
                    ? 'Yes'
                    : 'No'; ?>

            </div>

        </div>


        <div class="property-data-item">

            <div class="property-data-label">
                Created
            </div>

            <div class="property-data-value">

                <?= !empty(
                    $property['created_at']
                )
                    ? date(
                        'd M Y',
                        strtotime(
                            $property['created_at']
                        )
                    )
                    : '-'; ?>

            </div>

        </div>


    </div>

</div>


<!-- ==========================================================
     LOCATION
========================================================== -->

<div class="property-content-card">

    <div class="property-section-label">

        Location

    </div>

    <h2 class="property-section-title">

        Property Location

    </h2>


    <div class="property-location-box">

        <div class="property-location-icon">

            <i class="ti ti-map-pin"></i>

        </div>


        <div>

            <div class="property-location-city">

                <?= htmlspecialchars(
                    $location ?: '-'
                ); ?>

            </div>


            <div class="property-location-address">

                <?= !empty(
                    $property['address']
                )
                    ? nl2br(
                        htmlspecialchars(
                            $property['address']
                        )
                    )
                    : 'No address provided.'; ?>

            </div>

        </div>

    </div>

</div>


<!-- ==========================================================
     DESCRIPTION
========================================================== -->

<?php if (
    !empty($property['short_description']) ||
    !empty($property['description'])
): ?>

<div class="property-content-card">

    <div class="property-section-label">

        Property Overview

    </div>

    <h2 class="property-section-title">

        Description

    </h2>


    <?php if (
        !empty($property['short_description'])
    ): ?>

        <div class="property-short-description">

            <?= nl2br(
                htmlspecialchars(
                    $property['short_description']
                )
            ); ?>

        </div>

    <?php endif; ?>


    <?php if (
        !empty($property['description'])
    ): ?>

        <div class="property-description">

            <?= nl2br(
                htmlspecialchars(
                    $property['description']
                )
            ); ?>

        </div>

    <?php endif; ?>

</div>

<?php endif; ?>


<!-- ==========================================================
     PROPERTY GALLERY
========================================================== -->

<div class="property-content-card">

    <div class="property-section-label">

        Property Gallery

    </div>

    <h2 class="property-section-title">

        Images

    </h2>


    <?php if (!empty($images)): ?>

        <div class="property-gallery">

            <?php foreach (
                $images as $image
            ): ?>

                <?php

                $imageUrl = base_url($image['image']);

                ?>

                <div
                    class="property-gallery-item"
                    data-image="<?= htmlspecialchars(
                        $imageUrl,
                        ENT_QUOTES
                    ); ?>">

                    <img
                        src="<?= htmlspecialchars(
                            $imageUrl
                        ); ?>"
                        alt="<?= htmlspecialchars(
                            $image['caption']
                            ?? $property['title']
                        ); ?>"
                        loading="lazy">


                    <?php if (
                        !empty($image['is_cover']) &&
                        $image['is_cover'] == 1
                    ): ?>

                        <span class="property-gallery-cover">

                            <i class="ti ti-star-filled"></i>

                            Cover

                        </span>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="property-cover-empty">

            <i class="ti ti-photo-off"></i>

            <span>
                No property images available.
            </span>

        </div>

    <?php endif; ?>

</div>


<!-- ==========================================================
     DOCUMENTS
========================================================== -->

<?php if (!empty($documents)): ?>

<div class="property-content-card">

    <div class="property-section-label">

        Property Documents

    </div>

    <h2 class="property-section-title">

        Documents

    </h2>


    <div class="property-document-list">

        <?php foreach (
            $documents as $document
        ): ?>

            <?php

            $documentUrl = base_url($document['file']);

            ?>

            <div class="property-document-item">


                <div class="property-document-info">

                    <div class="property-document-icon">

                        <i class="ti ti-file-text"></i>

                    </div>


                    <div class="property-document-title">

                        <?= htmlspecialchars(
                            $document['title']
                            ?: $document['file']
                        ); ?>

                    </div>

                </div>


                <a
                    href="<?= htmlspecialchars(
                        $documentUrl
                    ); ?>"
                    target="_blank"
                    class="property-document-download"
                    title="Open Document">

                    <i class="ti ti-external-link"></i>

                </a>


            </div>

        <?php endforeach; ?>

    </div>

</div>

<?php endif; ?>


<!-- ==========================================================
     LIGHTBOX
========================================================== -->

<div
    class="property-lightbox"
    id="propertyLightbox">

    <button
        type="button"
        class="property-lightbox-close"
        id="propertyLightboxClose">

        <i class="ti ti-x"></i>

    </button>


    <img
        src=""
        alt="Property Image"
        id="propertyLightboxImage">

</div>


<script>

$(document).ready(function () {


    /* ==========================================================
       IMAGE LIGHTBOX
    ========================================================== */

    $('.property-gallery-item').on(
        'click',
        function () {

            const image =
                $(this).data('image');


            $('#propertyLightboxImage')
                .attr('src', image);


            $('#propertyLightbox')
                .addClass('show');

        }
    );


    $('#propertyLightboxClose').on(
        'click',
        function () {

            $('#propertyLightbox')
                .removeClass('show');

            $('#propertyLightboxImage')
                .attr('src', '');

        }
    );


    $('#propertyLightbox').on(
        'click',
        function (e) {

            if (
                e.target === this
            ) {

                $(this)
                    .removeClass('show');

                $('#propertyLightboxImage')
                    .attr('src', '');

            }

        }
    );


    /* ==========================================================
       ESC CLOSE LIGHTBOX
    ========================================================== */

    $(document).on(
        'keydown',
        function (e) {

            if (
                e.key === 'Escape'
            ) {

                $('#propertyLightbox')
                    .removeClass('show');

            }

        }
    );


    /* ==========================================================
       DELETE PROPERTY
    ========================================================== */

    $('#deletePropertyBtn').on(
        'click',
        function () {

            const id =
                $(this).data('id');

            const title =
                $(this).data('title');


            Swal.fire({

                icon: 'warning',

                title: 'Delete Property?',

                html:
                    'Are you sure you want to delete<br>' +
                    '<strong>' +
                    $('<div>')
                        .text(title)
                        .html() +
                    '</strong>?',

                showCancelButton: true,

                confirmButtonText:
                    'Yes, Delete',

                cancelButtonText:
                    'Cancel',

                confirmButtonColor:
                    '#dc2626',

                cancelButtonColor:
                    '#64748b'

            }).then(function (result) {

                if (
                    result.isConfirmed
                ) {

                    window.location.href =
                        '<?= site_url(
                            'properties/delete/'
                        ); ?>' +
                        id;

                }

            });

        }
    );


});

</script>

</div>