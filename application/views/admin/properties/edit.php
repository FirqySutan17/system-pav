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
   IMAGE
========================================================== */

.property-image-help {
    margin: -10px 0 18px;

    color: #94a3b8;

    font-size: 12px;
    line-height: 1.6;
}


/* IMAGE GRID */

.property-image-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 15px;

    margin-bottom: 18px;
}


/* IMAGE CARD */

.property-image-card {
    position: relative;

    overflow: hidden;

    background: #f8fafc;

    border: 1px solid #e5e7eb;
    border-radius: 12px;

    min-width: 0;
}

.property-image-card.is-cover {
    border: 2px solid #c99a21;
}


/* IMAGE */

.property-image-card__image {
    position: relative;

    aspect-ratio: 4 / 3;

    background: #f1f5f9;

    overflow: hidden;
}

.property-image-card__image img {
    width: 100%;
    height: 100%;

    object-fit: cover;

    display: block;
}


/* COVER BADGE */

.property-image-card__cover {
    position: absolute;

    top: 10px;
    left: 10px;

    display: inline-flex;
    align-items: center;
    gap: 5px;

    padding: 5px 9px;

    background: #c99a21;

    color: #fff;

    border-radius: 999px;

    font-size: 10px;
    font-weight: 700;
}

.property-image-card__cover i {
    font-size: 11px;
}


/* IMAGE BODY */

.property-image-card__body {
    padding: 10px;
}


/* CAPTION */

.property-image-caption {
    width: 100%;

    min-height: 36px;

    border: 1px solid #e2e8f0;
    border-radius: 7px;

    padding: 7px 9px;

    color: #334155;

    font-size: 11px;

    outline: none;
}

.property-image-caption:focus {
    border-color: #d9ae2f;

    box-shadow:
        0 0 0 3px rgba(217,174,47,.10);
}


/* IMAGE ACTIONS */

.property-image-card__actions {
    display: flex;

    align-items: center;

    gap: 6px;

    margin-top: 8px;
}

.btn-image-action {
    flex: 1;

    min-height: 32px;

    border: 1px solid #e2e8f0;
    border-radius: 7px;

    background: #fff;

    color: #64748b;

    font-size: 10px;
    font-weight: 600;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 5px;

    transition: .2s;
}

.btn-image-action:hover {
    border-color: #071b3a;
    color: #071b3a;
}

.btn-image-action--danger:hover {
    border-color: #dc2626;
    color: #dc2626;
}


/* UPLOAD MORE */

.property-image-upload {
    margin-top: 5px;
}

.property-image-upload__box {
    min-height: 115px;

    border: 1px dashed #cbd5e1;
    border-radius: 12px;

    background: #f8fafc;

    display: flex;

    flex-direction: column;

    align-items: center;
    justify-content: center;

    text-align: center;

    cursor: pointer;

    transition: .2s;
}

.property-image-upload__box:hover {
    border-color: #c99a21;

    background: #fffcf2;
}

.property-image-upload__icon {
    width: 38px;
    height: 38px;

    border-radius: 10px;

    background: #fff;

    color: #071b3a;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 7px;

    box-shadow:
        0 4px 12px rgba(15,23,42,.06);
}

.property-image-upload__box strong {
    color: #334155;

    font-size: 12px;
}

.property-image-upload__box span {
    margin-top: 4px;

    color: #94a3b8;

    font-size: 10px;
}


/* NEW IMAGE PREVIEW */

.property-new-images {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 15px;

    margin-top: 15px;
}

.property-new-image {
    position: relative;

    overflow: hidden;

    border-radius: 12px;

    background: #f8fafc;

    border: 1px solid #e2e8f0;
}

.property-new-image.is-cover {
    border: 2px solid #c99a21;
}

.property-new-image img {
    width: 100%;

    aspect-ratio: 4 / 3;

    object-fit: cover;

    display: block;
}

.property-new-image__remove {
    position: absolute;

    top: 8px;
    right: 8px;

    width: 28px;
    height: 28px;

    border: 0;
    border-radius: 50%;

    background: rgba(7,27,58,.85);

    color: #fff;

    display: flex;
    align-items: center;
    justify-content: center;

    cursor: pointer;
}


/* ==========================================================
   DOCUMENT
========================================================== */

.property-document-list {
    display: flex;

    flex-direction: column;

    gap: 10px;

    margin-bottom: 15px;
}

.property-document-item {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 13px 15px;

    border: 1px solid #e5e7eb;

    border-radius: 10px;

    background: #f8fafc;
}

.property-document-item__info {
    min-width: 0;

    display: flex;
    align-items: center;

    gap: 10px;
}

.property-document-item__icon {
    width: 36px;
    height: 36px;

    flex: 0 0 36px;

    border-radius: 8px;

    background: #fff;

    color: #071b3a;

    display: flex;
    align-items: center;
    justify-content: center;
}

.property-document-item__text {
    min-width: 0;
}

.property-document-item__text strong {
    display: block;

    color: #334155;

    font-size: 12px;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}

.property-document-item__text span {
    display: block;

    margin-top: 3px;

    color: #94a3b8;

    font-size: 10px;
}

.property-document-item__actions {
    display: flex;

    gap: 6px;

    flex-shrink: 0;
}


/* NEW DOCUMENT */

.property-new-document {
    margin-top: 15px;
}

.property-document-upload {
    display: flex;

    align-items: center;

    gap: 10px;

    padding: 12px;

    border: 1px dashed #cbd5e1;

    border-radius: 10px;

    background: #f8fafc;
}

.property-document-upload input {
    max-width: 100%;

    font-size: 11px;
}

/* NEW DOCUMENT UPLOAD BOX */

.property-document-upload {
    display: block;
    padding: 0;
    border: 0;
    background: transparent;
}


.property-document-upload__box {
    min-height: 115px;

    border: 1px dashed #cbd5e1;
    border-radius: 12px;

    background: #f8fafc;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    text-align: center;

    cursor: pointer;

    transition: .2s ease;
}


.property-document-upload__box:hover {
    border-color: #c99a21;
    background: #fffcf2;
}


.property-document-upload__icon {
    width: 38px;
    height: 38px;

    border-radius: 10px;

    background: #fff;
    color: #071b3a;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 7px;

    box-shadow:
        0 4px 12px rgba(15,23,42,.06);
}


.property-document-upload__box strong {
    color: #334155;
    font-size: 12px;
}


.property-document-upload__box span {
    margin-top: 4px;

    color: #94a3b8;

    font-size: 10px;
}


/* NEW DOCUMENT LIST */

.property-new-documents {
    display: flex;
    flex-direction: column;

    gap: 10px;

    margin-top: 15px;
}


.property-new-document-item {
    display: flex;
    align-items: center;

    gap: 12px;

    padding: 12px 14px;

    border: 1px solid #e2e8f0;
    border-radius: 10px;

    background: #f8fafc;
}


.property-new-document-item__icon {
    width: 38px;
    height: 38px;

    flex: 0 0 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #fff;

    color: #071b3a;

    font-size: 18px;
}


.property-new-document-item__info {
    min-width: 0;
    flex: 1;
}


.property-new-document-item__filename {
    display: block;

    color: #334155;

    font-size: 11px;
    font-weight: 650;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}


.property-new-document-item__size {
    display: block;

    margin-top: 3px;

    color: #94a3b8;

    font-size: 10px;
}


.property-new-document-item__title {
    width: 300px;
}


.property-new-document-item__remove {
    width: 34px;
    height: 34px;

    flex: 0 0 34px;

    border: 1px solid #fecaca;
    border-radius: 8px;

    background: #fff;

    color: #dc2626;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    cursor: pointer;
}


.property-new-document-item__remove:hover {
    background: #dc2626;
    color: #fff;
}


@media(max-width:768px) {

    .property-new-document-item {
        align-items: stretch;
        flex-wrap: wrap;
    }

    .property-new-document-item__info {
        width: calc(100% - 50px);
        flex: 1 1 calc(100% - 50px);
    }

    .property-new-document-item__title {
        width: 100%;
        flex: 1 1 100%;
    }

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


/* ==========================================================
   RESPONSIVE
========================================================== */

@media(max-width:992px) {

    .property-image-grid,
    .property-new-images {
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
    }

}

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

    .property-image-grid,
    .property-new-images {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .property-form-footer {
        flex-direction: column-reverse;
    }

    .btn-property-cancel,
    .btn-property-save {
        width: 100%;
    }

}

@media(max-width:480px) {

    .property-image-grid,
    .property-new-images {
        grid-template-columns: 1fr;
    }

    .property-document-item {
        align-items: flex-start;
        flex-direction: column;
    }

    .property-document-item__actions {
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

        Edit Property

    </h1>

    <p>

        Update property information, gallery and documents.

    </p>

</div>


<!-- ==========================================================
     FORM
========================================================== -->

<form
    action="<?= site_url('properties/update/' . $property['id']); ?>"
    method="post"
    enctype="multipart/form-data"
    id="propertyEditForm">

    <input
    type="hidden"
    name="cover_image_id"
    id="selectedCoverImage"
    value="">

    <input
    type="hidden"
    name="new_cover_index"
    id="selectedNewCoverImage"
    value="">


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
                value="<?= htmlspecialchars(
                    $property['title']
                ); ?>"
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

                <option
                    value="available"
                    <?= $property['status'] === 'available'
                        ? 'selected'
                        : ''; ?>>

                    Available

                </option>

                <option
                    value="reserved"
                    <?= $property['status'] === 'reserved'
                        ? 'selected'
                        : ''; ?>>

                    Reserved

                </option>

                <option
                    value="sold"
                    <?= $property['status'] === 'sold'
                        ? 'selected'
                        : ''; ?>>

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
                            value="<?= $category['id']; ?>"
                            <?= (int)$property['category_id'] ===
                                (int)$category['id']
                                    ? 'selected'
                                    : ''; ?>>

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
                value="<?= htmlspecialchars(
                    $property['asset_class'] ?? ''
                ); ?>"
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
                value="<?= htmlspecialchars(
                    $property['asset_type'] ?? ''
                ); ?>"
                placeholder="e.g. Office">

        </div>

        <div class="row g-4">
            <!-- LAND AREA -->
            <div class="col-lg-3 col-md-6">

                <label class="property-form-label">
                    Land Area
                </label>

                <div class="input-group flex-nowrap">

                    <input
                        type="text"
                        name="land_area"
                        class="property-form-control"
                        value="<?= htmlspecialchars(
                            $property['land_area'] ?? ''
                        ); ?>"
                        placeholder="e.g. 4,800 m²">

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

                <div class="input-group flex-nowrap">

                    <input
                        type="text"
                        name="building_area"
                        class="property-form-control"
                        value="<?= htmlspecialchars(
                            $property['building_area'] ?? ''
                        ); ?>"
                        placeholder="e.g. 12,500 m²">

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
                    value="<?= htmlspecialchars(
                        $property['year_built'] ?? ''
                    ); ?>"
                    placeholder="e.g. 2024"
                    min="1900"
                    max="2100">

            </div>


            <!-- PRICE -->
            <div class="col-lg-3 col-md-6">

                <label class="property-form-label">
                    Price
                </label>

                <div class="input-group flex-nowrap">

                    <span class="input-group-text">
                        Rp
                    </span>

                    <input
                        type="text"
                        name="price"
                        id="propertyPrice"
                        class="property-form-control"
                        value="<?= !empty($property['price'])
                            ? number_format(
                                (float)$property['price'],
                                0,
                                ',',
                                '.'
                            )
                            : ''; ?>"
                        placeholder="e.g. 25.000.000.000"
                        inputmode="numeric"
                        autocomplete="off">

                </div>

            </div>
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
                    class="form-check-input"
                    <?= !empty($property['featured'])
                        ? 'checked'
                        : ''; ?>>

                <span
                    style="
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
                            value="<?= $location['id']; ?>"
                            <?= (int)$property['location_id'] ===
                                (int)$location['id']
                                    ? 'selected'
                                    : ''; ?>>

                            <?= htmlspecialchars(
                                $location['city']
                            ); ?>

                            <?php if (!empty($location['province'])): ?>

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
                value="<?= htmlspecialchars(
                    $property['address'] ?? ''
                ); ?>"
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


    <div class="row g-4">

        <div class="col-12">

            <label class="property-form-label">

                Short Description

            </label>

            <textarea
                name="short_description"
                class="property-form-control"
                style="min-height:90px;"
                placeholder="Short property summary..."><?= htmlspecialchars(
                    $property['short_description'] ?? ''
                ); ?></textarea>

        </div>


        <div class="col-12">

            <label class="property-form-label">

                Full Description

            </label>

            <textarea
                name="description"
                class="property-form-control"
                placeholder="Describe the property, facilities, investment potential, and other relevant information..."><?= htmlspecialchars(
                    $property['description'] ?? ''
                ); ?></textarea>

        </div>

    </div>

</div>


<!-- ==========================================================
     PROPERTY IMAGES
========================================================== -->

<div class="property-form-section">

    <div class="property-form-section__label">

        Property Gallery

    </div>

    <h2>

        Property Images

    </h2>

    <p class="property-image-help">

        Existing images are shown below.
        You can change the cover image, remove images,
        or upload additional images.

    </p>

    <!-- ==========================================================
        EXISTING IMAGES
    ========================================================== -->

    <div class="property-image-grid">

        <?php if (!empty($property['images'])): ?>

            <?php foreach ($property['images'] as $image): ?>

                <div
                    class="property-image-card <?= strtolower(trim((string)$image['is_cover'])) === 'yes' ? 'is-cover' : ''; ?>"
                    data-image-id="<?= $image['id']; ?>">

                    <!-- IMAGE -->

                    <div class="property-image-card__image">

                        <img
                            src="<?= base_url($image['image']); ?>"
                            alt="<?= htmlspecialchars(
                                $image['caption']
                                    ?: $property['title']
                            ); ?>"
                            loading="lazy">


                        <?php if (strtolower(trim((string)$image['is_cover'])) === 'yes'): ?>

                            <div class="property-image-card__cover">

                                <i class="ti ti-star-filled"></i>

                                Cover

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- BODY -->

                    <div class="property-image-card__body">

                        <!-- CAPTION -->

                        <input
                            type="text"
                            name="existing_image_captions[<?= $image['id']; ?>]"
                            value="<?= htmlspecialchars(
                                $image['caption'] ?? ''
                            ); ?>"
                            class="property-image-caption"
                            placeholder="Image caption">


                        <!-- ACTIONS -->

                        <div class="property-image-card__actions">

                            <?php if (strtolower(trim((string)$image['is_cover'])) === 'yes'): ?>

                                <button
                                    type="button"
                                    class="btn-image-action btn-set-cover"
                                    data-image-id="<?= $image['id']; ?>"
                                    disabled>

                                    <i class="ti ti-star-filled"></i>

                                    Cover

                                </button>

                            <?php else: ?>

                                <button
                                    type="button"
                                    class="btn-image-action btn-set-cover"
                                    data-image-id="<?= $image['id']; ?>">

                                    <i class="ti ti-star"></i>

                                    Set Cover

                                </button>

                            <?php endif; ?>


                            <button
                                type="button"
                                class="btn-image-action btn-image-action--danger btn-delete-existing-image"
                                data-image-id="<?= $image['id']; ?>">

                                <i class="ti ti-trash"></i>

                                Delete

                            </button>

                        </div>

                    </div>


                    <!-- DELETE FLAG -->

                    <input
                        type="hidden"
                        name="delete_images[]"
                        value="<?= $image['id']; ?>"
                        disabled
                        class="delete-image-input">

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div
                style="
                    grid-column:1/-1;
                    padding:20px;
                    border:1px dashed #cbd5e1;
                    border-radius:12px;
                    color:#94a3b8;
                    font-size:12px;
                    text-align:center;
                ">

                No property images uploaded.

            </div>

        <?php endif; ?>

    </div> 

    <!-- UPLOAD NEW -->

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

                Upload Additional Images

            </strong>

            <span>

                JPG, PNG or WEBP · Multiple images allowed

            </span>

        </label>

    </div>


    <div
        class="property-new-images"
        id="propertyNewImages">

        <!-- JS preview -->

    </div>

</div>


<!-- ==========================================================
     DOCUMENTS
========================================================== -->

<div class="property-form-section">

    <div class="property-form-section__label">

        Property Documents

    </div>

    <h2>

        Documents

    </h2>


    <p class="property-image-help">

        Manage brochures, legal documents, certificates,
        or other property-related files.

    </p>


    <!-- ==========================================================
        EXISTING DOCUMENTS
    ========================================================== -->

    <div class="property-document-list">

        <?php if (!empty($property['documents'])): ?>

            <?php foreach ($property['documents'] as $document): ?>

                <div
                    class="property-document-item"
                    data-document-id="<?= $document['id']; ?>">

                    <div class="property-document-item__info">

                        <div class="property-document-item__icon">

                            <i class="ti ti-file-description"></i>

                        </div>


                        <div class="property-document-item__text">

                            <input
                                type="text"
                                name="existing_document_titles[<?= $document['id']; ?>]"
                                value="<?= htmlspecialchars(
                                    $document['title']
                                ); ?>"
                                class="property-form-control"
                                placeholder="Document title">


                            <a
                                href="<?= base_url($document['file']); ?>"
                                target="_blank"
                                class="property-document-link">

                                <i class="ti ti-external-link"></i>

                                View Document

                            </a>

                        </div>

                    </div>


                    <div class="property-document-item__actions">

                        <button
                            type="button"
                            class="btn btn-outline-danger btn-delete-existing-document"
                            data-document-id="<?= $document['id']; ?>">

                            <i class="ti ti-trash"></i>

                            Delete

                        </button>

                    </div>


                    <!-- DELETE FLAG -->

                    <input
                        type="hidden"
                        name="delete_documents[]"
                        value="<?= $document['id']; ?>"
                        disabled
                        class="delete-document-input">

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div
                style="
                    padding:15px;
                    border:1px dashed #cbd5e1;
                    border-radius:10px;
                    color:#94a3b8;
                    font-size:12px;
                    text-align:center;
                ">

                No documents uploaded.

            </div>

        <?php endif; ?>

    </div>


    <!-- ==========================================================
        ADD DOCUMENT
    ========================================================== -->

    <div class="property-new-document">

        <div class="property-document-upload">

            <input
                type="file"
                name="documents[]"
                id="propertyDocuments"
                accept=".pdf,.doc,.docx,.xls,.xlsx"
                multiple
                hidden>

            <label
                for="propertyDocuments"
                class="property-document-upload__box">

                <div class="property-document-upload__icon">

                    <i class="ti ti-cloud-upload"></i>

                </div>

                <strong>
                    Upload New Documents
                </strong>

                <span>
                    PDF, DOC, DOCX, XLS or XLSX · Multiple documents allowed
                </span>

            </label>

        </div>


        <!-- NEW DOCUMENT PREVIEW -->

        <div
            id="propertyNewDocuments"
            class="property-new-documents">

            <!-- JS preview -->

        </div>

    </div>

</div>


<!-- ==========================================================
     FOOTER
========================================================== -->

<div class="property-form-footer">

    <a
        href="<?= site_url('properties'); ?>"
        class="btn-property-cancel">

        Cancel

    </a>


    <button
        type="submit"
        class="btn-property-save">

        <i class="ti ti-device-floppy"></i>

        Save Changes

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

            this.value =
                formatPropertyPrice(
                    this.value
                );

        }
    );

    const existingCover =
        $('.property-image-card.is-cover')
            .data('image-id');


    if (existingCover) {

        $('#selectedCoverImage')
            .val(existingCover);

    }

    /* ==========================================================
    FILE SIZE
    ========================================================== */

    function formatFileSize(bytes)
    {
        if (bytes === 0) {
            return '0 Bytes';
        }


        const units = [
            'Bytes',
            'KB',
            'MB',
            'GB'
        ];


        const i =
            Math.floor(
                Math.log(bytes) /
                Math.log(1024)
            );


        return (
            parseFloat(
                (
                    bytes /
                    Math.pow(1024, i)
                ).toFixed(2)
            ) +
            ' ' +
            units[i]
        );
    }


    /* ==========================================================
    ESCAPE HTML
    ========================================================== */

    function escapeHtml(value)
    {
        return $('<div>')
            .text(value ?? '')
            .html();
    }

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
    NEW IMAGE SELECTION
    ========================================================== */

    let selectedImageFiles = [];


    /* ==========================================================
    SELECT IMAGES
    ========================================================== */

    $('#propertyImages').on(
        'change',
        function () {

            const files =
                Array.from(this.files);


            files.forEach(function (file) {

                if (
                    !file.type.startsWith('image/')
                ) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Avoid duplicate file selection
                |--------------------------------------------------------------------------
                */

                const duplicate =
                    selectedImageFiles.some(function (existingFile) {

                        return (
                            existingFile.name === file.name &&
                            existingFile.size === file.size &&
                            existingFile.lastModified === file.lastModified
                        );

                    });


                if (!duplicate) {

                    selectedImageFiles.push(file);

                }

            });


            renderNewImages();


            /*
            |--------------------------------------------------------------------------
            | Reset input
            |--------------------------------------------------------------------------
            |
            | Important supaya user bisa memilih file yang sama
            | lagi setelah sebelumnya dihapus.
            |
            */

            this.value = '';

        }
    );


    /* ==========================================================
    RENDER NEW IMAGES
    ========================================================== */

    function renderNewImages()
    {
        const preview =
            $('#propertyNewImages');

        preview.empty();

        const selectedNewCover =
            $('#selectedNewCoverImage').val();

        selectedImageFiles.forEach(
            function (file, index) {

                const reader =
                    new FileReader();

                reader.onload =
                    function (e) {

                        const isCover =
                            String(selectedNewCover) === String(index);

                        const item =
                            $('<div>')
                                .addClass('property-new-image')
                                .attr(
                                    'data-file-index',
                                    index
                                );

                        if (isCover) {
                            item.addClass('is-cover');
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | IMAGE
                        |--------------------------------------------------------------------------
                        */

                        const image =
                            $('<img>')
                                .attr(
                                    'src',
                                    e.target.result
                                )
                                .attr(
                                    'alt',
                                    file.name
                                );


                        /*
                        |--------------------------------------------------------------------------
                        | COVER BADGE
                        |--------------------------------------------------------------------------
                        */

                        if (isCover) {

                            item.append(
                                $('<div>')
                                    .addClass(
                                        'property-image-card__cover'
                                    )
                                    .html(
                                        '<i class="ti ti-star-filled"></i> Cover'
                                    )
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | REMOVE BUTTON
                        |--------------------------------------------------------------------------
                        */

                        const remove =
                            $('<button>')
                                .attr(
                                    'type',
                                    'button'
                                )
                                .addClass(
                                    'property-new-image__remove'
                                )
                                .attr(
                                    'title',
                                    'Remove image'
                                )
                                .html(
                                    '<i class="ti ti-x"></i>'
                                );


                        remove.on(
                            'click',
                            function () {

                                removeNewImage(index);

                            }
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | SET COVER BUTTON
                        |--------------------------------------------------------------------------
                        */

                        const setCover =
                            $('<button>')
                                .attr(
                                    'type',
                                    'button'
                                )
                                .addClass(
                                    'btn-image-action btn-set-new-cover'
                                )
                                .attr(
                                    'data-file-index',
                                    index
                                );


                        if (isCover) {

                            setCover
                                .prop('disabled', true)
                                .html(
                                    '<i class="ti ti-star-filled"></i> Cover'
                                );

                        } else {

                            setCover
                                .html(
                                    '<i class="ti ti-star"></i> Set Cover'
                                );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | BODY
                        |--------------------------------------------------------------------------
                        */

                        const body =
                            $('<div>')
                                .css({
                                    padding: '10px'
                                });


                        body.append(
                            setCover
                        );


                        item.append(image);
                        item.append(remove);
                        item.append(body);

                        preview.append(item);

                    };


                reader.readAsDataURL(file);

            }
        );
    }


    /* ==========================================================
    REMOVE NEW IMAGE
    ========================================================== */

    function removeNewImage(index)
    {
        const currentCover =
            $('#selectedNewCoverImage').val();


        /*
        |--------------------------------------------------------------------------
        | Remove selected file
        |--------------------------------------------------------------------------
        */

        selectedImageFiles.splice(
            index,
            1
        );


        /*
        |--------------------------------------------------------------------------
        | Adjust cover index
        |--------------------------------------------------------------------------
        */

        if (currentCover !== '') {

            const coverIndex =
                parseInt(
                    currentCover,
                    10
                );


            if (coverIndex === index) {

                /*
                | Cover image was removed
                */

                $('#selectedNewCoverImage')
                    .val('');

            } else if (coverIndex > index) {

                /*
                | Index shifted because an earlier image was removed
                */

                $('#selectedNewCoverImage')
                    .val(
                        coverIndex - 1
                    );

            }

        }


        renderNewImages();
    }

    /* ==========================================================
    SYNC FILE INPUT BEFORE SUBMIT
    ========================================================== */

    $('#propertyEditForm').on(
        'submit',
        function () {

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

            const input =
                document.getElementById(
                    'propertyImages'
                );


            const dataTransfer =
                new DataTransfer();


            selectedImageFiles.forEach(
                function (file) {

                    dataTransfer.items.add(
                        file
                    );

                }
            );


            input.files =
                dataTransfer.files;

            /* ==========================================================
            SYNC DOCUMENT FILES
            ========================================================== */

            const documentInput =
                document.getElementById(
                    'propertyDocuments'
                );


            const documentTransfer =
                new DataTransfer();


            selectedDocumentFiles.forEach(
                function (item) {

                    documentTransfer.items.add(
                        item.file
                    );

                }
            );


            documentInput.files =
                documentTransfer.files;


            /* ==========================================================
            ADD DOCUMENT TITLES
            ========================================================== */

            $('.new-document-title-hidden')
                .remove();


            selectedDocumentFiles.forEach(
                function (item) {

                    $('<input>')
                        .attr({
                            type: 'hidden',
                            name: 'document_titles[]',
                            class: 'new-document-title-hidden',
                            value: item.title
                        })
                        .appendTo('#propertyEditForm');

                }
            );

        }
    );

    /* ==========================================================
    EXISTING IMAGE - SET COVER
    ========================================================== */

    $(document).on(
        'click',
        '.btn-set-cover',
        function () {

            const button =
                $(this);

            const imageId =
                button.data('image-id');


            Swal.fire({

                icon: 'question',

                title: 'Set as Cover?',

                text:
                    'This image will become the property thumbnail.',

                showCancelButton: true,

                confirmButtonColor: '#071b3a',

                confirmButtonText: 'Yes, Set Cover',

                cancelButtonText: 'Cancel'

            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Remove old cover UI
                |--------------------------------------------------------------------------
                */

                $('.property-image-card')
                    .removeClass('is-cover');


                $('.property-image-card__cover')
                    .remove();


                $('.btn-set-cover')
                    .prop('disabled', false)
                    .html(
                        '<i class="ti ti-star"></i> Set Cover'
                    );


                /*
                |--------------------------------------------------------------------------
                | Set new cover UI
                |--------------------------------------------------------------------------
                */

                const card =
                    $('.property-image-card[data-image-id="' +
                        imageId +
                    '"]');


                card.addClass('is-cover');


                card.find(
                    '.property-image-card__image'
                ).prepend(

                    '<div class="property-image-card__cover">' +
                        '<i class="ti ti-star-filled"></i> Cover' +
                    '</div>'

                );


                button
                    .prop('disabled', true)
                    .html(
                        '<i class="ti ti-star-filled"></i> Cover'
                    );


                /*
                |--------------------------------------------------------------------------
                | Store selected cover
                |--------------------------------------------------------------------------
                */

                let coverInput =
                    $('#selectedCoverImage');


                if (!coverInput.length) {

                    coverInput =
                        $('<input>')
                            .attr({
                                type: 'hidden',
                                id: 'selectedCoverImage',
                                name: 'cover_image_id'
                            });

                    $('#propertyEditForm')
                        .append(coverInput);
                }


                coverInput.val(imageId);

            });

        }
    );

    /* ==========================================================
   NEW IMAGE - SET COVER
   ========================================================== */

    $(document).on(
        'click',
        '.btn-set-new-cover',
        function () {

            const button =
                $(this);

            const index =
                parseInt(
                    button.attr('data-file-index'),
                    10
                );


            if (
                isNaN(index) ||
                !selectedImageFiles[index]
            ) {
                return;
            }


            Swal.fire({

                icon: 'question',

                title: 'Set as Cover?',

                text:
                    'This new image will become the property thumbnail.',

                showCancelButton: true,

                confirmButtonColor: '#071b3a',

                confirmButtonText: 'Yes, Set Cover',

                cancelButtonText: 'Cancel'

            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Clear existing cover selection
                |--------------------------------------------------------------------------
                */

                $('#selectedCoverImage')
                    .val('');


                /*
                |--------------------------------------------------------------------------
                | Set new cover index
                |--------------------------------------------------------------------------
                */

                $('#selectedNewCoverImage')
                    .val(index);


                /*
                |--------------------------------------------------------------------------
                | Refresh existing image UI
                |--------------------------------------------------------------------------
                */

                $('.property-image-card')
                    .removeClass('is-cover');

                $('.property-image-card__cover')
                    .remove();

                $('.btn-set-cover')
                    .prop('disabled', false)
                    .html(
                        '<i class="ti ti-star"></i> Set Cover'
                    );


                /*
                |--------------------------------------------------------------------------
                | Refresh new image UI
                |--------------------------------------------------------------------------
                */

                renderNewImages();

            });

        }
    );

    /* ==========================================================
    NEW DOCUMENT SELECTION
    ========================================================== */

    let selectedDocumentFiles = [];


    /* ==========================================================
    SELECT DOCUMENTS
    ========================================================== */

    $('#propertyDocuments').on(
        'change',
        function () {

            const files =
                Array.from(this.files);


            files.forEach(function (file) {

                /*
                |--------------------------------------------------------------------------
                | Avoid duplicate file
                |--------------------------------------------------------------------------
                */

                const duplicate =
                    selectedDocumentFiles.some(
                        function (existingFile) {

                            return (
                                existingFile.name === file.name &&
                                existingFile.size === file.size &&
                                existingFile.lastModified === file.lastModified
                            );

                        }
                    );


                if (!duplicate) {

                    selectedDocumentFiles.push({

                        file: file,

                        title: ''

                    });

                }

            });


            renderNewDocuments();


            /*
            |--------------------------------------------------------------------------
            | Reset input
            |--------------------------------------------------------------------------
            */

            this.value = '';

        }
    );

    /* ==========================================================
    RENDER NEW DOCUMENTS
    ========================================================== */

    function renderNewDocuments()
    {
        const preview =
            $('#propertyNewDocuments');


        preview.empty();


        selectedDocumentFiles.forEach(
            function (item, index) {

                const file =
                    item.file;


                const html = `

                    <div
                        class="property-new-document-item"
                        data-file-index="${index}">

                        <div
                            class="property-new-document-item__icon">

                            <i class="ti ti-file-description"></i>

                        </div>


                        <div
                            class="property-new-document-item__info">

                            <span
                                class="property-new-document-item__filename"
                                title="${escapeHtml(file.name)}">

                                ${escapeHtml(file.name)}

                            </span>

                            <span
                                class="property-new-document-item__size">

                                ${formatFileSize(file.size)}

                            </span>

                        </div>


                        <input
                            type="text"
                            class="property-form-control property-new-document-item__title"
                            placeholder="Document title"
                            data-index="${index}"
                            value="${escapeHtml(item.title)}"
                            required>


                        <button
                            type="button"
                            class="property-new-document-item__remove"
                            data-index="${index}"
                            title="Remove document">

                            <i class="ti ti-x"></i>

                        </button>

                    </div>

                `;


                preview.append(html);

            }
        );
    }

    /* ==========================================================
    NEW DOCUMENT TITLE
    ========================================================== */

    $(document).on(
        'input',
        '.property-new-document-item__title',
        function () {

            const index =
                parseInt(
                    $(this).data('index'),
                    10
                );


            if (
                typeof selectedDocumentFiles[index] !==
                'undefined'
            ) {

                selectedDocumentFiles[index].title =
                    $(this).val();

            }

        }
    );

    /* ==========================================================
    REMOVE NEW DOCUMENT
    ========================================================== */

    $(document).on(
        'click',
        '.property-new-document-item__remove',
        function () {

            const index =
                parseInt(
                    $(this).data('index'),
                    10
                );


            if (
                typeof selectedDocumentFiles[index] ===
                'undefined'
            ) {

                return;
            }


            selectedDocumentFiles.splice(
                index,
                1
            );


            renderNewDocuments();

        }
    );

    /* ==========================================================
    EXISTING IMAGE - DELETE
    ========================================================== */

    $(document).on(
        'click',
        '.btn-delete-existing-image',
        function () {

            const button =
                $(this);

            const imageId =
                button.data('image-id');

            const card =
                $('.property-image-card[data-image-id="' +
                    imageId +
                '"]');


            Swal.fire({

                icon: 'warning',

                title: 'Delete Image?',

                text:
                    'This image will be removed when you save the property.',

                showCancelButton: true,

                confirmButtonColor: '#dc2626',

                confirmButtonText: 'Yes, Delete',

                cancelButtonText: 'Cancel'

            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Mark for deletion
                |--------------------------------------------------------------------------
                */

                card
                    .find('.delete-image-input')
                    .prop('disabled', false);


                /*
                |--------------------------------------------------------------------------
                | Hide from UI
                |--------------------------------------------------------------------------
                */

                card
                    .find('.delete-image-input')
                    .prop('disabled', false);

                card.fadeOut(200);

            });

        }
    );

    /* ==========================================================
    EXISTING DOCUMENT - DELETE
    ========================================================== */

    $(document).on(
        'click',
        '.btn-delete-existing-document',
        function () {

            const button =
                $(this);

            const documentId =
                button.data('document-id');

            const item =
                $('.property-document-item[data-document-id="' +
                    documentId +
                '"]');


            Swal.fire({

                icon: 'warning',

                title: 'Delete Document?',

                text:
                    'This document will be removed when you save the property.',

                showCancelButton: true,

                confirmButtonColor: '#dc2626',

                confirmButtonText: 'Yes, Delete',

                cancelButtonText: 'Cancel'

            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Mark for deletion
                |--------------------------------------------------------------------------
                */

                item
                    .find('.delete-document-input')
                    .prop('disabled', false);


                /*
                |--------------------------------------------------------------------------
                | Hide from UI
                |--------------------------------------------------------------------------
                */

                item
                    .find('.delete-document-input')
                    .prop('disabled', false);

                item.fadeOut(200);

            });

        }
    );

    /* ==========================================================
       ADD CATEGORY
    ========================================================== */

    $('#saveCategory').on(
        'click',
        function () {

            const button =
                $(this);


            const name =
                $('#newCategoryName')
                    .val()
                    .trim();


            if (!name) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Category Required',

                    text:
                        'Please enter category name.',

                    confirmButtonColor:
                        '#071b3a'

                });

                return;
            }


            button
                .prop(
                    'disabled',
                    true
                )
                .text(
                    'Saving...'
                );


            $.ajax({

                url:
                    '<?= site_url(
                        'properties/quick-add-category'
                    ); ?>',

                type:
                    'POST',

                data: {
                    name: name
                },

                dataType:
                    'json',

                success:
                    function (response) {

                        if (
                            response.status
                        ) {

                            const option =
                                new Option(
                                    response.data.name,
                                    response.data.id,
                                    true,
                                    true
                                );


                            $('#category_id')
                                .append(option)
                                .val(
                                    response.data.id
                                )
                                .trigger(
                                    'change'
                                );


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

                                icon:
                                    'success',

                                title:
                                    'Category Added',

                                text:
                                    'Category successfully added.',

                                timer:
                                    1500,

                                showConfirmButton:
                                    false

                            });

                        } else {

                            Swal.fire({

                                icon:
                                    'error',

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

                            icon:
                                'error',

                            title:
                                'Server Error',

                            text:
                                'Unable to add category.',

                            confirmButtonColor:
                                '#071b3a'

                        });

                    },

                complete:
                    function () {

                        button
                            .prop(
                                'disabled',
                                false
                            )
                            .text(
                                'Add Category'
                            );

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

            const button =
                $(this);


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

                    icon:
                        'warning',

                    title:
                        'City Required',

                    text:
                        'Please enter city.',

                    confirmButtonColor:
                        '#071b3a'

                });

                return;
            }


            button
                .prop(
                    'disabled',
                    true
                )
                .text(
                    'Saving...'
                );


            $.ajax({

                url:
                    '<?= site_url(
                        'properties/quick-add-location'
                    ); ?>',

                type:
                    'POST',

                data: {
                    city:
                        city,

                    province:
                        province
                },

                dataType:
                    'json',

                success:
                    function (response) {

                        if (
                            response.status
                        ) {

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
                                .val(
                                    response.data.id
                                )
                                .trigger(
                                    'change'
                                );


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

                                icon:
                                    'success',

                                title:
                                    'Location Added',

                                text:
                                    'Location successfully added.',

                                timer:
                                    1500,

                                showConfirmButton:
                                    false

                            });

                        } else {

                            Swal.fire({

                                icon:
                                    'error',

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

                            icon:
                                'error',

                            title:
                                'Server Error',

                            text:
                                'Unable to add location.',

                            confirmButtonColor:
                                '#071b3a'

                        });

                    },

                complete:
                    function () {

                        button
                            .prop(
                                'disabled',
                                false
                            )
                            .text(
                                'Add Location'
                            );

                    }

            });

        }
    );

});

</script>

</div>