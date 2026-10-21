<div class="container-fluid property-admin">

    <style>

        /* ==========================================================
           PROPERTY ADMIN
           ========================================================== */

        .property-admin {
            padding: 28px;
            background: transparent;
            min-height: calc(100vh - 80px);
        }


        /* ==========================================================
           PAGE HEADER
           ========================================================== */

        .property-page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;

            margin-bottom: 24px;
        }


        .property-page-header__eyebrow {
            display: block;

            margin-bottom: 6px;

            color: #c99a21;

            font-size: 10px;
            font-weight: 700;

            letter-spacing: 1.8px;
            text-transform: uppercase;
        }


        .property-page-header h1 {
            margin: 0;

            color: #071b3a;

            font-size: 27px;
            font-weight: 750;
        }


        .property-page-header p {
            margin: 6px 0 0;

            color: #8993a4;

            font-size: 13px;
        }


        /* ==========================================================
           ADD BUTTON
           ========================================================== */

        .btn-property-primary {
            display: inline-flex;

            align-items: center;
            gap: 8px;

            padding: 11px 17px;

            border: 0;
            border-radius: 10px;

            background: #071b3a;

            color: #fff;

            font-size: 12px;
            font-weight: 700;

            text-decoration: none;

            transition: .25s ease;
        }


        .btn-property-primary:hover {
            background: #0d2b55;

            color: #fff;

            transform: translateY(-2px);

            box-shadow:
                0 8px 20px rgba(7,27,58,.15);
        }


        /* ==========================================================
           FILTER / TOOLBAR
           ========================================================== */

        .property-toolbar {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 15px;

            padding: 17px 20px;

            margin-bottom: 18px;

            background: #fff;

            border: 1px solid #edf0f5;

            border-radius: 16px;

            box-shadow:
                0 6px 20px rgba(15,23,42,.04);
        }


        .property-search {
            position: relative;

            width: 320px;
        }


        .property-search i {
            position: absolute;

            left: 14px;
            top: 50%;

            transform: translateY(-50%);

            color: #94a3b8;
        }


        .property-search input {
            width: 100%;

            height: 42px;

            padding:
                0 14px 0 40px;

            border: 1px solid #e2e8f0;

            border-radius: 9px;

            font-size: 12px;

            outline: none;

            transition: .2s ease;
        }


        .property-search input:focus {
            border-color: #d9ae2f;

            box-shadow:
                0 0 0 3px rgba(217,174,47,.12);
        }


        .property-result-count {
            color: #8993a4;

            font-size: 12px;
        }


        .property-result-count strong {
            color: #071b3a;
        }


        /* ==========================================================
           TABLE CARD
           ========================================================== */

        .property-table-card {
            background: #fff;

            border: 1px solid #edf0f5;

            border-radius: 18px;

            overflow: hidden;

            box-shadow:
                0 8px 25px rgba(15,23,42,.05);
        }


        .property-table-wrapper {
            overflow-x: auto;
        }


        .property-table {
            width: 100%;

            margin: 0;

            border-collapse: collapse;
        }


        .property-table thead th {
            padding: 15px 18px;

            background: #f8fafc;

            border-bottom: 1px solid #edf0f5;

            color: #7b8798;

            font-size: 10px;
            font-weight: 700;

            letter-spacing: .8px;

            text-transform: uppercase;

            white-space: nowrap;
        }


        .property-table tbody td {
            padding: 15px 18px;

            border-bottom: 1px solid #f1f3f6;

            vertical-align: middle;

            color: #475569;

            font-size: 12px;
        }


        .property-table tbody tr:last-child td {
            border-bottom: 0;
        }


        .property-table tbody tr {
            transition: background .2s ease;
        }


        .property-table tbody tr:hover {
            background: #fafbfc;
        }


        /* ==========================================================
           PROPERTY INFO
           ========================================================== */

        .property-list-info {
            display: flex;

            align-items: center;

            gap: 12px;

            min-width: 250px;
        }


        .property-list-thumbnail {
            width: 54px;
            height: 48px;

            flex: 0 0 54px;

            border-radius: 8px;

            overflow: hidden;

            background: #eef2f7;
        }


        .property-list-thumbnail img {
            width: 100%;
            height: 100%;

            object-fit: cover;
        }


        .property-list-title {
            margin: 0 0 4px;

            color: #071b3a;

            font-size: 13px;
            font-weight: 700;
        }


        .property-list-slug {
            color: #94a3b8;

            font-size: 10px;
        }


        /* ==========================================================
           CATEGORY
           ========================================================== */

        .property-category {
            display: inline-flex;

            padding: 5px 9px;

            border-radius: 6px;

            background: #f8fafc;

            color: #64748b;

            font-size: 10px;

            font-weight: 600;
        }


        /* ==========================================================
           LOCATION
           ========================================================== */

        .property-location {
            display: flex;

            align-items: center;

            gap: 5px;

            white-space: nowrap;
        }


        .property-location i {
            color: #d9ae2f;
        }


        /* ==========================================================
           STATUS
           ========================================================== */

        .property-status {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 5px 9px;

            border-radius: 999px;

            font-size: 9px;

            font-weight: 700;

            text-transform: uppercase;
        }


        .property-status::before {
            content: "";

            width: 5px;
            height: 5px;

            border-radius: 50%;

            background: currentColor;
        }


        .property-status--available {
            background: #ecfdf3;

            color: #16835a;
        }


        .property-status--sold {
            background: #fef2f2;

            color: #dc2626;
        }


        .property-status--reserved {
            background: #fff7ed;

            color: #c2410c;
        }


        .property-status--default {
            background: #f1f5f9;

            color: #64748b;
        }


        /* ==========================================================
           FEATURED
           ========================================================== */

        .property-featured {
            color: #c99a21;

            font-size: 15px;
        }


        .property-not-featured {
            color: #cbd5e1;

            font-size: 15px;
        }


        /* ==========================================================
           ACTION
           ========================================================== */

        .property-actions {
            display: flex;

            align-items: center;

            justify-content: flex-end;

            gap: 5px;
        }


        .property-action {
            width: 32px;
            height: 32px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            border-radius: 8px;

            border: 1px solid #e5e7eb;

            background: #fff;

            color: #64748b;

            text-decoration: none;

            font-size: 14px;

            transition: .2s ease;
        }


        .property-action:hover {
            background: #071b3a;

            border-color: #071b3a;

            color: #fff;
        }


        .property-action--delete:hover {
            background: #dc2626;

            border-color: #dc2626;
        }


        /* ==========================================================
           EMPTY
           ========================================================== */

        .property-empty {
            padding: 70px 20px;

            text-align: center;
        }


        .property-empty i {
            display: block;

            margin-bottom: 12px;

            color: #d9ae2f;

            font-size: 34px;
        }


        .property-empty strong {
            display: block;

            margin-bottom: 5px;

            color: #071b3a;

            font-size: 15px;
        }


        .property-empty span {
            color: #94a3b8;

            font-size: 12px;
        }


        /* ==========================================================
           MOBILE
           ========================================================== */

        @media(max-width: 768px) {

            .property-admin {
                padding: 18px 15px;
            }


            .property-page-header {
                align-items: flex-start;

                flex-direction: column;
            }


            .property-page-header h1 {
                font-size: 23px;
            }


            .btn-property-primary {
                width: 100%;

                justify-content: center;
            }


            .property-toolbar {
                align-items: stretch;

                flex-direction: column;
            }


            .property-search {
                width: 100%;
            }


            .property-result-count {
                text-align: left;
            }

        }

    </style>


    <!-- ==========================================================
         PAGE HEADER
    ========================================================== -->

    <div class="property-page-header">

        <div>

            <span class="property-page-header__eyebrow">
                Property Management
            </span>

            <h1>
                Properties
            </h1>

            <p>
                Manage your commercial and investment property portfolio.
            </p>

        </div>


        <a
            href="<?= site_url('properties/create'); ?>"
            class="btn-property-primary">

            <i class="ti ti-plus"></i>

            Add Property

        </a>

    </div>


    <!-- ==========================================================
         TOOLBAR
    ========================================================== -->

    <div class="property-toolbar">

        <div class="property-search">

            <i class="ti ti-search"></i>

            <input
                type="text"
                id="propertySearch"
                placeholder="Search property...">

        </div>


        <div class="property-result-count">

            Showing

            <strong>
                <?= count($properties); ?>
            </strong>

            properties

        </div>

    </div>


    <!-- ==========================================================
         TABLE
    ========================================================== -->

    <div class="property-table-card">

        <div class="property-table-wrapper">

            <table
                class="property-table"
                id="propertyTable">

                <thead>

                    <tr>

                        <th>
                            Property
                        </th>

                        <th class="text-center">
                            Category
                        </th>

                        <th>
                            Location
                        </th>

                        <th class="text-center">
                            Asset Type
                        </th>

                        <th class="text-center">
                            Status
                        </th>

                        <th class="text-center">
                            Featured
                        </th>

                        <th class="text-end">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (!empty($properties)): ?>

                        <?php foreach ($properties as $property): ?>

                            <?php

                            $status =
                                strtolower(
                                    trim(
                                        $property['status'] ?? ''
                                    )
                                );


                            $statusClass =
                                'property-status--default';


                            if ($status === 'available') {

                                $statusClass =
                                    'property-status--available';

                            } elseif ($status === 'sold') {

                                $statusClass =
                                    'property-status--sold';

                            } elseif ($status === 'reserved') {

                                $statusClass =
                                    'property-status--reserved';

                            }


                            $thumbnail =
                                !empty($property['thumbnail'])
                                ? $property['thumbnail']
                                : 'property/default.jpg';

                            ?>

                            <tr>


                                <!-- PROPERTY -->

                                <td>

                                    <div class="property-list-info">

                                        <div class="property-list-thumbnail">

                                            <img
                                                src="<?= !empty($property['thumbnail'])
                                                    ? base_url($property['thumbnail'])
                                                    : base_url('assets/frontend/img/property/default.jpg'); ?>"
                                                alt="<?= htmlspecialchars(
                                                    $property['title']
                                                ); ?>"
                                                loading="lazy"
                                                onerror="this.src='<?= base_url(
                                                    'assets/frontend/img/property/default.jpg'
                                                ); ?>';">

                                        </div>


                                        <div>

                                            <div class="property-list-title">

                                                <?= htmlspecialchars(
                                                    $property['title']
                                                ); ?>

                                            </div>


                                            <div class="property-list-slug">

                                                <?= htmlspecialchars(
                                                    $property['slug']
                                                ); ?>

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <!-- CATEGORY -->

                                <td class="text-center">

                                    <span class="property-category">

                                        <?= htmlspecialchars(
                                            $property['category_name']
                                            ?? '-'
                                        ); ?>

                                    </span>

                                </td>


                                <!-- LOCATION -->

                                <td>

                                    <div class="property-location">

                                        <i class="ti ti-map-pin"></i>

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

                                </td>


                                <!-- ASSET TYPE -->

                                <td class="text-center">

                                    <?= htmlspecialchars(
                                        $property['asset_type']
                                        ?? '-'
                                    ); ?>

                                </td>


                                <!-- STATUS -->

                                <td class="text-center">

                                    <span
                                        class="property-status <?= $statusClass; ?>">

                                        <?= htmlspecialchars(
                                            $property['status']
                                            ?? '-'
                                        ); ?>

                                    </span>

                                </td>


                                <!-- FEATURED -->

                                <td class="text-center">

                                    <?php if (!empty($property['featured'])): ?>

                                        <i
                                            class="ti ti-star-filled property-featured"
                                            title="Featured"></i>

                                    <?php else: ?>

                                        <i
                                            class="ti ti-star property-not-featured"
                                            title="Not Featured"></i>

                                    <?php endif; ?>

                                </td>


                                <!-- ACTION -->

                                <td>

                                    <div class="property-actions">


                                        <!-- VIEW -->

                                        <a
                                            href="<?= site_url(
                                                'properties/view/' . $property['id']
                                            ); ?>"
                                            class="property-action"
                                            title="View Property">

                                            <i class="ti ti-eye"></i>

                                        </a>


                                        <!-- EDIT -->

                                        <a
                                            href="<?= site_url(
                                                'properties/edit/' .
                                                $property['id']
                                            ); ?>"
                                            class="property-action"
                                            title="Edit Property">

                                            <i class="ti ti-edit"></i>

                                        </a>


                                        <!-- DELETE -->

                                        <a
                                            href="<?= site_url('properties/delete/' . $property['id']); ?>"
                                            class="btn-delete-property property-action"
                                            data-title="<?= htmlspecialchars(
                                                $property['title'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>"
                                        >
                                            <i class="ti ti-trash"></i>
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php else: ?>

                        <tr>

                            <td colspan="7">

                                <div class="property-empty">

                                    <i class="ti ti-building"></i>

                                    <strong>
                                        No Properties Found
                                    </strong>

                                    <span>
                                        Start by adding your first property.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>


</div>


<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            /* ======================================================
            SEARCH
            ====================================================== */

            const searchInput =
                document.getElementById(
                    'propertySearch'
                );


            const table =
                document.getElementById(
                    'propertyTable'
                );


            if (
                searchInput &&
                table
            ) {

                searchInput.addEventListener(
                    'input',
                    function () {

                        const keyword =
                            this.value
                                .toLowerCase()
                                .trim();


                        const rows =
                            table.querySelectorAll(
                                'tbody tr'
                            );


                        rows.forEach(
                            function (row) {

                                const text =
                                    row.textContent
                                        .toLowerCase();


                                row.style.display =
                                    text.includes(keyword)
                                    ? ''
                                    : 'none';

                            }
                        );

                    }
                );

            }


            /* ======================================================
            DELETE CONFIRMATION
            ====================================================== */

            const deleteButtons =
                document.querySelectorAll(
                    '.btn-delete-property'
                );


            deleteButtons.forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function (event) {

                            event.preventDefault();


                            const deleteUrl =
                                this.getAttribute(
                                    'href'
                                );


                            Swal.fire({

                                title:
                                    'Delete Property?',

                                text:
                                    'This property and its related images/documents will be deleted.',

                                icon:
                                    'warning',

                                showCancelButton:
                                    true,

                                confirmButtonText:
                                    'Yes, Delete',

                                cancelButtonText:
                                    'Cancel',

                                confirmButtonColor:
                                    '#071b3a',

                                cancelButtonColor:
                                    '#94a3b8',

                                reverseButtons:
                                    true

                            }).then(
                                function (result) {

                                    if (
                                        result.isConfirmed
                                    ) {

                                        window.location.href =
                                            deleteUrl;

                                    }

                                }
                            );

                        }
                    );

                }
            );


            /* ======================================================
            FLASH MESSAGE
            ====================================================== */

            <?php if ($this->session->flashdata('success')): ?>

                Swal.fire({

                    icon: 'success',

                    title: 'Success',

                    text:
                        <?= json_encode(
                            $this->session->flashdata('success')
                        ); ?>,

                    confirmButtonColor:
                        '#071b3a'

                });

            <?php endif; ?>


            <?php if ($this->session->flashdata('error')): ?>

                Swal.fire({

                    icon: 'error',

                    title: 'Error',

                    text:
                        <?= json_encode(
                            $this->session->flashdata('error')
                        ); ?>,

                    confirmButtonColor:
                        '#071b3a'

                });

            <?php endif; ?>

        }
    );

    $(document).on(
        'click',
        '.btn-delete-property',
        function (e) {

            e.preventDefault();

            const url =
                $(this).attr('href');

            const title =
                $(this).data('title');


            Swal.fire({

                icon: 'warning',

                title: 'Delete Property?',

                html:
                    'Are you sure you want to delete ' +
                    '<strong>' +
                    $('<div>')
                        .text(title)
                        .html() +
                    '</strong>?' +
                    '<br><br>' +
                    '<small style="color:#64748b;">' +
                    'All property images and documents will also be removed.' +
                    '</small>',

                showCancelButton: true,

                confirmButtonText:
                    'Yes, Delete',

                cancelButtonText:
                    'Cancel',

                confirmButtonColor:
                    '#dc3545',

                cancelButtonColor:
                    '#64748b',

                reverseButtons: true,

                focusCancel: true

            }).then(
                function (result) {

                    if (
                        result.isConfirmed
                    ) {

                        window.location.href =
                            url;

                    }

                }
            );

        }
    );

</script>