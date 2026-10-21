<div class="container-fluid property-form-page">

<style>

/* ==========================================================
   PAGE
========================================================== */

.property-form-page {
    padding: 28px;
    background: transparent;
    min-height: calc(100vh - 80px);
}


/* ==========================================================
   HEADER
========================================================== */

.property-form-header {
    margin-bottom: 25px;
}

.property-form-header__back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
    color: #94a3b8;
    font-size: 12px;
    text-decoration: none;
}

.property-form-header__back:hover {
    color: #071b3a;
}

.property-form-header__eyebrow {
    display: block;
    margin-bottom: 6px;
    color: #c99a21;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.8px;
    text-transform: uppercase;
}

.property-form-header h1 {
    margin: 0;
    color: #071b3a;
    font-size: 27px;
    font-weight: 750;
}

.property-form-header p {
    margin: 6px 0 0;
    color: #8993a4;
    font-size: 13px;
}


/* ==========================================================
   FORM CARD
========================================================== */

.property-form-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 18px;
    padding: 25px;

    box-shadow:
        0 8px 25px rgba(15,23,42,.05);

    margin-bottom: 20px;
}

.property-form-section {
    padding-bottom: 28px;
    margin-bottom: 28px;
    border-bottom: 1px solid #eef1f5;
}

.property-form-section:last-child {
    border-bottom: 0;
    margin-bottom: 0;
    padding-bottom: 0;
}

.property-form-section__label {
    color: #c99a21;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.property-form-section h2 {
    margin: 0 0 20px;
    color: #071b3a;
    font-size: 17px;
    font-weight: 700;
}


/* ==========================================================
   FORM
========================================================== */

.property-form-label {
    display: block;
    margin-bottom: 7px;
    color: #334155;
    font-size: 12px;
    font-weight: 650;
}

.property-form-control,
.property-form-select {
    width: 100%;
    min-height: 44px;

    border: 1px solid #e2e8f0;
    border-radius: 9px;

    padding: 9px 12px;

    background: #fff;

    color: #334155;
    font-size: 12px;

    outline: none;
    transition: .2s ease;
}

.property-form-control:focus,
.property-form-select:focus {
    border-color: #d9ae2f;

    box-shadow:
        0 0 0 3px rgba(217,174,47,.12);
}

textarea.property-form-control {
    min-height: 140px;
    resize: vertical;
}


/* ==========================================================
   INLINE FIELD
========================================================== */

.property-inline-field {
    display: flex;
    align-items: center;
    gap: 8px;
}

.property-inline-field .select2-container {
    flex: 1;
    width: auto !important;
}


/* ==========================================================
   SELECT2
========================================================== */

.property-inline-field
.select2-container--bootstrap-5
.select2-selection {

    min-height: 44px;

    border: 1px solid #e2e8f0;
    border-radius: 9px;

    font-size: 12px;

    box-shadow: none;

    display: flex;
    align-items: center;
}

.property-inline-field
.select2-container--bootstrap-5
.select2-selection--single {

    height: 44px;
}

.property-inline-field
.select2-container--bootstrap-5
.select2-selection--single
.select2-selection__rendered {

    color: #334155;
    padding-left: 12px;
    padding-right: 35px;
    line-height: 42px;
}

.property-inline-field
.select2-container--bootstrap-5
.select2-selection--single
.select2-selection__placeholder {

    color: #94a3b8;
}

.property-inline-field
.select2-container--bootstrap-5
.select2-selection--single
.select2-selection__arrow {

    height: 42px;
    right: 8px;
}

.property-inline-field
.select2-container--bootstrap-5.select2-container--focus
.select2-selection,
.property-inline-field
.select2-container--bootstrap-5.select2-container--open
.select2-selection {

    border-color: #d9ae2f;

    box-shadow:
        0 0 0 3px rgba(217,174,47,.12);
}

.select2-container--bootstrap-5
.select2-dropdown {

    border: 1px solid #e2e8f0;
    border-radius: 10px;

    overflow: hidden;

    box-shadow:
        0 12px 30px rgba(15,23,42,.10);
}

.select2-container--bootstrap-5
.select2-search {
    padding: 8px;
}

.select2-container--bootstrap-5
.select2-search__field {

    border-radius: 7px;
    border: 1px solid #e2e8f0;

    font-size: 12px;

    padding: 8px 10px;
}

.select2-container--bootstrap-5
.select2-results__option {

    font-size: 12px;
    padding: 9px 12px;

    color: #334155;
}

.select2-container--bootstrap-5
.select2-results__option--highlighted {

    background-color: #071b3a;
    color: #fff;
}


/* ==========================================================
   ADD MASTER BUTTON
========================================================== */

.btn-add-master {

    flex: 0 0 44px;

    width: 44px;
    height: 44px;

    border: 1px solid #e2e8f0;
    border-radius: 9px;

    background: #f8fafc;

    color: #071b3a;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    font-size: 17px;

    transition: .2s ease;
}

.btn-add-master:hover {

    background: #071b3a;
    border-color: #071b3a;
    color: #fff;
}


/* ==========================================================
   SWITCH
========================================================== */

.property-switch {

    display: flex;
    align-items: center;
    gap: 10px;

    min-height: 44px;
}

.property-switch input {

    width: 40px;
    height: 21px;
}


/* ==========================================================
   UPLOAD AREA
========================================================== */

.property-upload-area {

    border: 1.5px dashed #d9dee8;
    border-radius: 14px;

    padding: 30px 20px;

    text-align: center;

    background: #fafbfc;

    cursor: pointer;

    transition: .2s ease;
}

.property-upload-area:hover {

    border-color: #d9ae2f;
    background: #fffdf7;
}

.property-upload-icon {

    width: 48px;
    height: 48px;

    margin: 0 auto 12px;

    border-radius: 12px;

    background: #f8f1dc;

    color: #c99a21;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 22px;
}

.property-upload-title {

    color: #071b3a;
    font-size: 13px;
    font-weight: 700;

    margin-bottom: 5px;
}

.property-upload-info {

    color: #94a3b8;
    font-size: 11px;
}


/* ==========================================================
   IMAGE PREVIEW
========================================================== */

.property-image-grid {

    display: grid;

    grid-template-columns:
        repeat(auto-fill, minmax(180px, 1fr));

    gap: 15px;

    margin-top: 18px;
}

.property-image-item {

    position: relative;

    border: 1px solid #e8edf3;

    border-radius: 12px;

    background: #fff;

    overflow: hidden;

    box-shadow:
        0 5px 15px rgba(15,23,42,.04);
}

.property-image-preview {

    position: relative;

    height: 150px;

    background: #f1f5f9;

    overflow: hidden;
}

.property-image-preview img {

    width: 100%;
    height: 100%;

    object-fit: cover;
}

.property-image-cover {

    position: absolute;

    top: 8px;
    left: 8px;

    padding: 4px 8px;

    border-radius: 999px;

    background: #c99a21;

    color: #fff;

    font-size: 9px;
    font-weight: 700;

    text-transform: uppercase;

    display: none;
}

.property-image-item.is-cover
.property-image-cover {

    display: block;
}

.property-image-body {

    padding: 10px;
}

.property-image-name {

    color: #475569;

    font-size: 10px;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;

    margin-bottom: 8px;
}

.property-image-caption {

    min-height: 36px !important;

    font-size: 11px !important;

    padding: 7px 9px !important;
}

.property-image-actions {

    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 8px;

    margin-top: 8px;
}

.property-image-cover-select {

    display: flex;
    align-items: center;
    gap: 5px;

    color: #64748b;

    font-size: 10px;
}

.property-image-remove {

    border: 0;

    background: transparent;

    color: #ef4444;

    font-size: 15px;

    cursor: pointer;

    padding: 3px;
}

.property-image-remove:hover {
    color: #b91c1c;
}


/* ==========================================================
   DOCUMENT
========================================================== */

.property-document-list {

    display: flex;
    flex-direction: column;
    gap: 10px;

    margin-top: 15px;
}

.property-document-row {

    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr)
        42px;

    gap: 10px;

    padding: 12px;

    border: 1px solid #edf0f5;

    border-radius: 10px;

    background: #fafbfc;
}

.btn-remove-document {

    width: 42px;
    height: 42px;

    border: 1px solid #fecaca;
    border-radius: 9px;

    background: #fff;

    color: #ef4444;
}

.btn-remove-document:hover {

    background: #fef2f2;
}


/* ==========================================================
   FOOTER
========================================================== */

.property-form-footer {

    display: flex;
    align-items: center;
    justify-content: flex-end;

    gap: 10px;

    padding-top: 5px;
}

.btn-property-cancel {

    min-height: 42px;

    padding: 0 18px;

    border: 1px solid #e2e8f0;
    border-radius: 9px;

    background: #fff;

    color: #64748b;

    font-size: 12px;
    font-weight: 600;

    text-decoration: none;

    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-property-save {

    min-height: 42px;

    padding: 0 20px;

    border: 0;
    border-radius: 9px;

    background: #071b3a;

    color: #fff;

    font-size: 12px;
    font-weight: 700;

    display: inline-flex;
    align-items: center;
    gap: 7px;
}

.btn-property-save:hover {

    background: #0d2b55;
    color: #fff;
}


/* ==========================================================
   MODAL
========================================================== */

.property-modal .modal-content {

    border: 0;
    border-radius: 16px;

    box-shadow:
        0 20px 50px rgba(7,27,58,.15);
}

.property-modal .modal-header {

    padding: 20px 22px;

    border-bottom: 1px solid #eef1f5;
}

.property-modal .modal-title {

    color: #071b3a;

    font-size: 16px;
    font-weight: 700;
}

.property-modal .modal-body {
    padding: 22px;
}

.property-modal .modal-footer {

    padding: 15px 22px;

    border-top: 1px solid #eef1f5;
}

.input-group {
    flex-wrap: nowrap !important;
}

/* ==========================================================
   PROPERTY IMAGE UPLOAD
========================================================== */

.property-image-help {
    margin: -10px 0 18px;

    color: #94a3b8;
    font-size: 12px;
}


/* UPLOAD BOX */

.property-image-upload__box {

    min-height: 170px;

    border: 1.5px dashed #d9dee8;
    border-radius: 14px;

    background: #fafbfc;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    cursor: pointer;

    transition: .2s ease;

    text-align: center;
}

.property-image-upload__box:hover {

    border-color: #c99a21;

    background: #fffcf4;
}


.property-image-upload__icon {

    width: 48px;
    height: 48px;

    border-radius: 12px;

    background: #f1f5f9;

    color: #071b3a;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 24px;

    margin-bottom: 12px;
}


.property-image-upload__box strong {

    color: #071b3a;

    font-size: 13px;

    font-weight: 700;
}


.property-image-upload__box span {

    margin-top: 5px;

    color: #94a3b8;

    font-size: 11px;
}


/* ==========================================================
   IMAGE PREVIEW
========================================================== */

.property-image-preview {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 14px;

    margin-top: 18px;
}


.property-image-item {

    position: relative;

    overflow: hidden;

    border-radius: 12px;

    border: 1px solid #e2e8f0;

    background: #f8fafc;

    aspect-ratio: 4 / 3;
}


.property-image-item img {

    width: 100%;
    height: 100%;

    object-fit: cover;

    display: block;
}


/* OVERLAY */

.property-image-item__overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            to top,
            rgba(7,27,58,.65),
            transparent 50%
        );

    pointer-events: none;
}


/* REMOVE */

.property-image-remove {

    position: absolute;

    top: 8px;
    right: 8px;

    width: 30px;
    height: 30px;

    border: 0;

    border-radius: 8px;

    background: rgba(255,255,255,.95);

    color: #dc2626;

    display: flex;
    align-items: center;
    justify-content: center;

    cursor: pointer;

    z-index: 5;

    transition: .2s ease;
}

.property-image-remove:hover {

    background: #dc2626;

    color: #fff;
}


/* COVER RADIO */

.property-image-cover {

    position: absolute;

    left: 10px;
    bottom: 10px;

    z-index: 5;

    display: flex;
    align-items: center;

    gap: 7px;

    padding: 6px 9px;

    border-radius: 7px;

    background: rgba(7,27,58,.85);

    color: #fff;

    font-size: 10px;

    font-weight: 600;

    cursor: pointer;

    backdrop-filter: blur(5px);
}


.property-image-cover input {

    margin: 0;

    accent-color: #c99a21;
}


/* COVER BADGE */

.property-image-cover-badge {

    position: absolute;

    top: 10px;
    left: 10px;

    z-index: 5;

    padding: 5px 8px;

    border-radius: 6px;

    background: #c99a21;

    color: #fff;

    font-size: 9px;

    font-weight: 700;

    letter-spacing: .5px;

    text-transform: uppercase;
}


/* INFO */

.property-image-cover-info {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-top: 12px;

    padding: 10px 12px;

    border-radius: 8px;

    background: #fffbeb;

    color: #92400e;

    font-size: 11px;
}


/* EMPTY */

.property-image-empty {

    grid-column: 1 / -1;

    padding: 25px;

    border-radius: 10px;

    background: #f8fafc;

    color: #94a3b8;

    text-align: center;

    font-size: 12px;
}


/* ==========================================================
   MOBILE
========================================================== */

@media(max-width:768px) {

    .property-form-page {
        padding: 18px 15px;
    }

    .property-form-card {
        padding: 18px;
    }

    .property-form-header h1 {
        font-size: 23px;
    }

    .property-form-footer {

        flex-direction: column-reverse;
    }

    .btn-property-cancel,
    .btn-property-save {

        width: 100%;
        justify-content: center;
        text-align: center;
    }

    .property-image-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 10px;
    }

    .property-image-preview {
        height: 120px;
    }

    .property-document-row {

        grid-template-columns: 1fr;

    }

    .property-image-preview {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 10px;
    }

    .property-image-upload__box {

        min-height: 145px;
    }

    .btn-remove-document {

        width: 100%;
    }

}

</style>


<!-- ==========================================================
     HEADER
========================================================== -->

<div class="property-form-header">

    <a
        href="<?= site_url('properties'); ?>"
        class="property-form-header__back">

        <i class="ti ti-arrow-left"></i>

        Back to Properties

    </a>

    <span class="property-form-header__eyebrow">

        Property Management

    </span>

    <h1>
        Add Property
    </h1>

    <p>
        Create a new property for your portfolio.
    </p>

</div>


<!-- ==========================================================
     FORM
========================================================== -->

<form
    action="<?= site_url('properties/store'); ?>"
    method="post"
    enctype="multipart/form-data"
    id="propertyForm">


<div class="property-form-card">


<!-- ==========================================================
     BASIC INFORMATION
========================================================== -->

<div class="property-form-section">

    <div class="property-form-section__label">
        Property Information
    </div>

    <h2>
        Basic Information
    </h2>


    <div class="row g-4">


        <!-- TITLE -->

        <div class="col-lg-8">

            <label class="property-form-label">

                Property Title
                <span class="text-danger">*</span>

            </label>

            <input
                type="text"
                name="title"
                class="property-form-control"
                placeholder="e.g. Premium Office Tower"
                required>

        </div>


        <!-- STATUS -->

        <div class="col-lg-4">

            <label class="property-form-label">

                Status
                <span class="text-danger">*</span>

            </label>

            <select
                name="status"
                class="property-form-select"
                required>

                <option value="available">
                    Available
                </option>

                <option value="reserved">
                    Reserved
                </option>

                <option value="sold">
                    Sold
                </option>

            </select>

        </div>


        <!-- CATEGORY -->

        <div class="col-lg-6">

            <label class="property-form-label">

                Category
                <span class="text-danger">*</span>

            </label>

            <div class="property-inline-field">

                <select
                    name="category_id"
                    id="category_id"
                    class="property-form-select"
                    required>

                    <option value=""></option>

                    <?php foreach ($categories as $category): ?>

                        <option
                            value="<?= $category['id']; ?>">

                            <?= htmlspecialchars(
                                $category['name']
                            ); ?>

                        </option>

                    <?php endforeach; ?>

                </select>


                <button
                    type="button"
                    class="btn-add-master"
                    data-bs-toggle="modal"
                    data-bs-target="#addCategoryModal"
                    title="Add Category">

                    <i class="ti ti-plus"></i>

                </button>

            </div>

        </div>


        <!-- ASSET CLASS -->

        <div class="col-lg-3">

            <label class="property-form-label">
                Asset Class
            </label>

            <input
                type="text"
                name="asset_class"
                class="property-form-control"
                placeholder="e.g. Grade A">

        </div>


        <!-- ASSET TYPE -->

        <div class="col-lg-3">

            <label class="property-form-label">
                Asset Type
            </label>

            <input
                type="text"
                name="asset_type"
                class="property-form-control"
                placeholder="e.g. Office">

        </div>


        <!-- FEATURED -->

        <div class="col-12">

            <label class="property-form-label">
                Featured Property
            </label>

            <div class="property-switch">

                <input
                    type="checkbox"
                    name="featured"
                    value="1"
                    class="form-check-input">

                <span style="
                    font-size:12px;
                    color:#64748b;
                ">

                    Show this property as a featured property.

                </span>

            </div>

        </div>

    </div>

</div>


<!-- ==========================================================
     PROPERTY DETAILS
========================================================== -->

<div class="property-form-section">

    <div class="property-form-section__label">
        Property Details
    </div>

    <h2>
        Property Information
    </h2>


    <div class="row g-4">

        <!-- LAND AREA -->
        <div class="col-lg-3 col-md-6">

            <label class="property-form-label">
                Land Area
            </label>

            <div class="input-group">

                <input
                    type="number"
                    name="land_area"
                    class="property-form-control"
                    placeholder="e.g. 4800"
                    min="0"
                    step="0.01">

                <span class="input-group-text">
                    m²
                </span>

            </div>

        </div>


        <!-- BUILDING AREA -->
        <div class="col-lg-3 col-md-6">

            <label class="property-form-label">
                Building Area
            </label>

            <div class="input-group">

                <input
                    type="number"
                    name="building_area"
                    class="property-form-control"
                    placeholder="e.g. 12500"
                    min="0"
                    step="0.01">

                <span class="input-group-text">
                    m²
                </span>

            </div>

        </div>


        <!-- YEAR BUILT -->
        <div class="col-lg-3 col-md-6">

            <label class="property-form-label">
                Year Built
            </label>

            <input
                type="number"
                name="year_built"
                class="property-form-control"
                placeholder="e.g. 2024"
                min="1900"
                max="2100">

        </div>


        <!-- PRICE -->
        <div class="col-lg-3 col-md-6">

            <label class="property-form-label">
                Price
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    Rp
                </span>

                <input
                    type="text"
                    name="price"
                    id="propertyPrice"
                    class="property-form-control"
                    placeholder="e.g. 25.000.000.000"
                    inputmode="numeric"
                    autocomplete="off">

            </div>

        </div>

    </div>

</div>


<!-- ==========================================================
     LOCATION
========================================================== -->

<div class="property-form-section">

    <div class="property-form-section__label">
        Location
    </div>

    <h2>
        Property Location
    </h2>


    <div class="row g-4">


        <!-- LOCATION -->

        <div class="col-lg-6">

            <label class="property-form-label">

                Location
                <span class="text-danger">*</span>

            </label>

            <div class="property-inline-field">

                <select
                    name="location_id"
                    id="location_id"
                    class="property-form-select"
                    required>

                    <option value=""></option>

                    <?php foreach ($locations as $location): ?>

                        <option
                            value="<?= $location['id']; ?>">

                            <?= htmlspecialchars(
                                $location['city']
                            ); ?>

                            <?php if (
                                !empty($location['province'])
                            ): ?>

                                -
                                <?= htmlspecialchars(
                                    $location['province']
                                ); ?>

                            <?php endif; ?>

                        </option>

                    <?php endforeach; ?>

                </select>


                <button
                    type="button"
                    class="btn-add-master"
                    data-bs-toggle="modal"
                    data-bs-target="#addLocationModal"
                    title="Add Location">

                    <i class="ti ti-plus"></i>

                </button>

            </div>

        </div>


        <!-- ADDRESS -->

        <div class="col-lg-6">

            <label class="property-form-label">
                Address
            </label>

            <input
                type="text"
                name="address"
                class="property-form-control"
                placeholder="Full property address">

        </div>

    </div>

</div>


<!-- ==========================================================
     DESCRIPTION
========================================================== -->

<div class="property-form-section">

    <div class="property-form-section__label">
        Property Overview
    </div>

    <h2>
        Description
    </h2>


    <!-- SHORT DESCRIPTION -->

    <div class="mb-4">

        <label class="property-form-label">
            Short Description
        </label>

        <textarea
            name="short_description"
            class="property-form-control"
            style="min-height:90px"
            placeholder="Short description used for property cards and previews..."></textarea>

    </div>


    <!-- DESCRIPTION -->

    <div>

        <label class="property-form-label">
            Property Description
        </label>

        <textarea
            name="description"
            class="property-form-control"
            placeholder="Describe the property, facilities, investment potential, and other relevant information..."></textarea>

    </div>

</div>


<!-- ==================================================
     PROPERTY IMAGES
================================================== -->

<div class="property-form-section">

    <div class="property-form-section__label">

        Property Gallery

    </div>

    <h2>

        Property Images

    </h2>

    <p class="property-image-help">

        Upload property images. At least one image is required.
        You can add captions and select one image as the cover.

    </p>


    <!-- ==================================================
         IMAGE UPLOAD
    ================================================== -->

    <div class="property-image-upload">

        <input
            type="file"
            name="images[]"
            id="propertyImages"
            accept="image/jpeg,image/png,image/webp"
            multiple
            hidden>


        <label
            for="propertyImages"
            class="property-image-upload__box">

            <div class="property-image-upload__icon">

                <i class="ti ti-cloud-upload"></i>

            </div>

            <strong>

                Upload Property Images

            </strong>

            <span>

                JPG, PNG or WEBP · Multiple images allowed

            </span>

        </label>

    </div>


    <!-- ==================================================
         IMAGE GRID
    ================================================== -->

    <div
        class="property-image-grid"
        id="propertyImageGrid">

        <div class="property-image-empty">

            No images selected yet.

        </div>

    </div>


    <!-- ==================================================
         COVER INDEX
    ================================================== -->

    <input
        type="hidden"
        name="cover_index"
        id="coverIndex"
        value="0">

</div>


<!-- ==========================================================
     DOCUMENTS
========================================================== -->

<div class="property-form-section">

    <div class="property-form-section__label">
        Documents
    </div>

    <h2>
        Property Documents
    </h2>


    <div
        class="property-upload-area"
        id="addDocumentButton">

        <div class="property-upload-icon">

            <i class="ti ti-file-plus"></i>

        </div>

        <div class="property-upload-title">

            Add Property Document

        </div>

        <div class="property-upload-info">

            PDF, DOC, DOCX, XLS, XLSX

        </div>

    </div>


    <div
        id="propertyDocumentList"
        class="property-document-list">

    </div>

</div>


<!-- ==========================================================
     FORM FOOTER
========================================================== -->

<div class="property-form-footer">

    <a
        href="<?= site_url('properties'); ?>"
        class="btn-property-cancel">

        Cancel

    </a>


    <button
        type="submit"
        class="btn-property-save"
        id="savePropertyButton">

        <i class="ti ti-device-floppy"></i>

        Save Property

    </button>

</div>


</div>

</form>


<!-- ==========================================================
     ADD CATEGORY MODAL
========================================================== -->

<div
    class="modal fade property-modal"
    id="addCategoryModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Category
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <label class="property-form-label">

                    Category Name
                    <span class="text-danger">*</span>

                </label>

                <input
                    type="text"
                    id="newCategoryName"
                    class="property-form-control"
                    placeholder="e.g. Commercial Office">

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Cancel

                </button>


                <button
                    type="button"
                    class="btn btn-dark"
                    id="saveCategory">

                    Add Category

                </button>

            </div>

        </div>

    </div>

</div>


<!-- ==========================================================
     ADD LOCATION MODAL
========================================================== -->

<div
    class="modal fade property-modal"
    id="addLocationModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Location
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <div class="mb-3">

                    <label class="property-form-label">

                        City
                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        id="newLocationCity"
                        class="property-form-control"
                        placeholder="e.g. Jakarta Selatan">

                </div>


                <div>

                    <label class="property-form-label">

                        Province

                    </label>

                    <input
                        type="text"
                        id="newLocationProvince"
                        class="property-form-control"
                        placeholder="e.g. DKI Jakarta">

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Cancel

                </button>


                <button
                    type="button"
                    class="btn btn-dark"
                    id="saveLocation">

                    Add Location

                </button>

            </div>

        </div>

    </div>

</div>


<script>

    $(document).ready(function () {


    /* ==========================================================
    SELECT2
    ========================================================== */

    $('#category_id').select2({

        theme: 'bootstrap-5',

        width: '100%',

        placeholder: 'Select Category',

        allowClear: true

    });


    $('#location_id').select2({

        theme: 'bootstrap-5',

        width: '100%',

        placeholder: 'Select Location',

        allowClear: true

    });


    /* ==========================================================
    IMAGE UPLOAD
    ========================================================== */

    let selectedImages = [];


    $('#propertyImages').on('change', function (event) {

        const files = Array.from(event.target.files);

        files.forEach(function (file) {

            if (!file.type.match('image.*')) {
                return;
            }

            selectedImages.push({
                file: file,
                caption: '',
                isCover: selectedImages.length === 0
            });

        });


        renderImages();

        /*
        * Reset input supaya file yang sama
        * bisa dipilih kembali.
        */

        $(this).val('');

    });

    function formatPropertyPrice(value)
    {
        let digits =
            String(value || '')
                .replace(/\D/g, '');

        if (!digits) {
            return '';
        }

        return digits.replace(
            /\B(?=(\d{3})+(?!\d))/g,
            '.'
        );
    }


    $('#propertyPrice').on(
        'input',
        function () {

            const cursorPosition =
                this.selectionStart;

            const oldValue =
                this.value;

            const formatted =
                formatPropertyPrice(
                    oldValue
                );

            this.value =
                formatted;

        }
    );


    function renderImages() {

        const grid = $('#propertyImageGrid');

        grid.empty();


        selectedImages.forEach(function (item, index) {

            const objectUrl =
                URL.createObjectURL(item.file);


            const coverChecked =
                item.isCover ? 'checked' : '';


            const coverClass =
                item.isCover ? 'is-cover' : '';


            const html = `

                <div
                    class="property-image-item ${coverClass}"
                    data-index="${index}">

                    <div class="property-image-preview">

                        <img
                            src="${objectUrl}"
                            alt="Property Image">

                        <span class="property-image-cover">

                            Cover Image

                        </span>

                    </div>


                    <div class="property-image-body">

                        <div class="property-image-name"
                            title="${escapeHtml(item.file.name)}">

                            ${escapeHtml(item.file.name)}

                        </div>


                        <input
                            type="text"
                            class="form-control property-image-caption"
                            placeholder="Image caption"
                            value="${escapeHtml(item.caption)}"
                            data-caption-index="${index}">


                        <div class="property-image-actions">

                            <label class="property-image-cover-select">

                                <input
                                    type="radio"
                                    name="cover_selector"
                                    value="${index}"
                                    ${coverChecked}>

                                Cover

                            </label>


                            <button
                                type="button"
                                class="property-image-remove"
                                data-remove-index="${index}"
                                title="Remove image">

                                <i class="ti ti-trash"></i>

                            </button>

                        </div>

                    </div>

                </div>

            `;


            grid.append(html);

        });


        $('#coverIndex').val(
            getCoverIndex()
        );

    }


    function getCoverIndex() {

        const index =
            selectedImages.findIndex(
                image => image.isCover
            );

        return index >= 0 ? index : 0;

    }


    /* ==========================================================
    SET COVER
    ========================================================== */

    $(document).on(
        'change',
        'input[name="cover_selector"]',
        function () {

            const index =
                parseInt($(this).val());


            selectedImages.forEach(
                function (image, i) {

                    image.isCover =
                        i === index;

                }
            );


            $('#coverIndex').val(index);


            renderImages();

        }
    );


    /* ==========================================================
    IMAGE CAPTION
    ========================================================== */

    $(document).on(
        'input',
        '.property-image-caption',
        function () {

            const index =
                parseInt(
                    $(this).data('caption-index')
                );


            if (
                selectedImages[index]
            ) {

                selectedImages[index].caption =
                    $(this).val();

            }

        }
    );


    /* ==========================================================
    REMOVE IMAGE
    ========================================================== */

    $(document).on(
        'click',
        '.property-image-remove',
        function () {

            const index =
                parseInt(
                    $(this).data('remove-index')
                );


            selectedImages.splice(
                index,
                1
            );


            /*
            * Kalau cover dihapus,
            * otomatis image pertama menjadi cover.
            */

            if (
                selectedImages.length > 0 &&
                !selectedImages.some(
                    image => image.isCover
                )
            ) {

                selectedImages[0].isCover = true;

            }


            renderImages();

        }
    );


    /* ==========================================================
    ADD DOCUMENT
    ========================================================== */

    let documentIndex = 0;


    $('#addDocumentButton').on(
        'click',
        function () {

            const index =
                documentIndex++;


            const html = `

                <div
                    class="property-document-row"
                    data-document-index="${index}">

                    <div>

                        <label class="property-form-label">

                            Document Title

                        </label>

                        <input
                            type="text"
                            name="document_titles[]"
                            class="property-form-control"
                            placeholder="e.g. Property Brochure">

                    </div>


                    <div>

                        <label class="property-form-label">

                            File

                        </label>

                        <input
                            type="file"
                            name="documents[]"
                            class="property-form-control"
                            accept=".pdf,.doc,.docx,.xls,.xlsx"
                            required>

                    </div>


                    <div style="
                        display:flex;
                        align-items:end;
                    ">

                        <button
                            type="button"
                            class="btn-remove-document"
                            data-remove-document="${index}"
                            title="Remove document">

                            <i class="ti ti-trash"></i>

                        </button>

                    </div>

                </div>

            `;


            $('#propertyDocumentList')
                .append(html);

        }
    );


    /* ==========================================================
    REMOVE DOCUMENT
    ========================================================== */

    $(document).on(
        'click',
        '.btn-remove-document',
        function () {

            $(this)
                .closest('.property-document-row')
                .remove();

        }
    );


    /* ==========================================================
    ADD CATEGORY
    ========================================================== */

    $('#saveCategory').on(
        'click',
        function () {

            const button = $(this);

            const name =
                $('#newCategoryName')
                    .val()
                    .trim();


            if (!name) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Category Required',

                    text: 'Please enter category name.',

                    confirmButtonColor: '#071b3a'

                });

                return;

            }


            button
                .prop('disabled', true)
                .text('Saving...');


            $.ajax({

                url:
                    '<?= site_url(
                        'properties/quick-add-category'
                    ); ?>',

                type: 'POST',

                data: {
                    name: name
                },

                dataType: 'json',

                success:
                    function (response) {

                        if (response.status) {

                            const option =
                                new Option(
                                    response.data.name,
                                    response.data.id,
                                    true,
                                    true
                                );


                            $('#category_id')
                                .append(option)
                                .val(response.data.id)
                                .trigger('change');


                            bootstrap.Modal
                                .getInstance(
                                    document.getElementById(
                                        'addCategoryModal'
                                    )
                                )
                                .hide();


                            $('#newCategoryName')
                                .val('');


                            Swal.fire({

                                icon: 'success',

                                title: 'Category Added',

                                text:
                                    'Category successfully added.',

                                timer: 1500,

                                showConfirmButton: false

                            });

                        } else {

                            Swal.fire({

                                icon: 'error',

                                title:
                                    'Unable to Add Category',

                                text:
                                    response.message,

                                confirmButtonColor:
                                    '#071b3a'

                            });

                        }

                    },

                error:
                    function () {

                        Swal.fire({

                            icon: 'error',

                            title: 'Server Error',

                            text:
                                'Unable to add category.',

                            confirmButtonColor:
                                '#071b3a'

                        });

                    },

                complete:
                    function () {

                        button
                            .prop('disabled', false)
                            .text('Add Category');

                    }

            });

        }
    );


    /* ==========================================================
    ADD LOCATION
    ========================================================== */

    $('#saveLocation').on(
        'click',
        function () {

            const button = $(this);

            const city =
                $('#newLocationCity')
                    .val()
                    .trim();

            const province =
                $('#newLocationProvince')
                    .val()
                    .trim();


            if (!city) {

                Swal.fire({

                    icon: 'warning',

                    title: 'City Required',

                    text:
                        'Please enter city.',

                    confirmButtonColor:
                        '#071b3a'

                });

                return;

            }


            button
                .prop('disabled', true)
                .text('Saving...');


            $.ajax({

                url:
                    '<?= site_url(
                        'properties/quick-add-location'
                    ); ?>',

                type: 'POST',

                data: {

                    city: city,

                    province: province

                },

                dataType: 'json',

                success:
                    function (response) {

                        if (response.status) {

                            const label =
                                response.data.city +
                                (
                                    response.data.province
                                    ? ' - ' +
                                    response.data.province
                                    : ''
                                );


                            const option =
                                new Option(
                                    label,
                                    response.data.id,
                                    true,
                                    true
                                );


                            $('#location_id')
                                .append(option)
                                .val(response.data.id)
                                .trigger('change');


                            bootstrap.Modal
                                .getInstance(
                                    document.getElementById(
                                        'addLocationModal'
                                    )
                                )
                                .hide();


                            $('#newLocationCity')
                                .val('');

                            $('#newLocationProvince')
                                .val('');


                            Swal.fire({

                                icon: 'success',

                                title: 'Location Added',

                                text:
                                    'Location successfully added.',

                                timer: 1500,

                                showConfirmButton: false

                            });

                        } else {

                            Swal.fire({

                                icon: 'error',

                                title:
                                    'Unable to Add Location',

                                text:
                                    response.message,

                                confirmButtonColor:
                                    '#071b3a'

                            });

                        }

                    },

                error:
                    function () {

                        Swal.fire({

                            icon: 'error',

                            title: 'Server Error',

                            text:
                                'Unable to add location.',

                            confirmButtonColor:
                                '#071b3a'

                        });

                    },

                complete:
                    function () {

                        button
                            .prop('disabled', false)
                            .text('Add Location');

                    }

            });

        }
    );


    /* ==========================================================
    FORM SUBMIT
    ========================================================== */

    $('#propertyForm').on(
        'submit',
        function (event) {

            event.preventDefault();


            /* ==================================================
            VALIDATE IMAGE
            ================================================== */

            if (selectedImages.length === 0) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Property Image Required',

                    text: 'Please upload at least one property image.',

                    confirmButtonColor: '#071b3a'

                });

                return;

            }


            /* ==================================================
            VALIDATE COVER
            ================================================== */

            if (
                !selectedImages.some(
                    image => image.isCover
                )
            ) {

                selectedImages[0].isCover = true;

            }


            const form =
                this;


            const submitButton =
                $('#savePropertyButton');


            submitButton
                .prop('disabled', true)
                .html(
                    '<i class="ti ti-loader-2"></i> Saving...'
                );


            /* ==================================================
            PREPARE IMAGE FILES
            ================================================== */

            const imageInput =
                document.getElementById(
                    'propertyImages'
                );


            const dataTransfer =
                new DataTransfer();


            selectedImages.forEach(
                function (item) {

                    dataTransfer.items.add(
                        item.file
                    );

                }
            );


            imageInput.files =
                dataTransfer.files;


            /* ==================================================
            IMAGE CAPTION
            ================================================== */

            $('.dynamic-image-caption')
                .remove();


            selectedImages.forEach(
                function (item) {

                    $('<input>')
                        .attr({

                            type: 'hidden',

                            name: 'image_captions[]'

                        })
                        .addClass(
                            'dynamic-image-caption'
                        )
                        .val(item.caption)
                        .appendTo(form);

                }
            );


            /* ==================================================
            COVER INDEX
            ================================================== */

            $('#coverIndex').val(
                getCoverIndex()
            );

            /* ==================================================
            PROPERTY PRICE
            ================================================== */

            const priceInput =
                document.getElementById(
                    'propertyPrice'
                );

            if (priceInput) {

                priceInput.value =
                    priceInput.value
                        .replace(/\./g, '')
                        .replace(/\D/g, '');

            }


            /* ==================================================
            SUBMIT
            ================================================== */

            form.submit();

        }
    );


    /* ==========================================================
    ESCAPE HTML
    ========================================================== */

    function escapeHtml(value) {

        return $('<div>')
            .text(value || '')
            .html();

    }

    });

</script>

</div>