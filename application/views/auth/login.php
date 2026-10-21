<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Login | Sinergi Asset</title>


    <!-- ==========================================================
    FAVICON
    ========================================================== -->

    <link
        rel="icon"
        type="image/png"
        href="<?= base_url('assets/frontend/img/favicon.png'); ?>">


    <!-- ==========================================================
    BOOTSTRAP
    ========================================================== -->

    <link
        rel="stylesheet"
        href="<?= base_url('assets/libs/bootstrap/dist/css/bootstrap.min.css'); ?>">


    <!-- ==========================================================
    FONT AWESOME
    ========================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <!-- ==========================================================
    GOOGLE FONT
    ========================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">


    <style>

        /* ==========================================================
        ROOT
        ========================================================== */

        :root {

            --navy:
                #071d3a;

            --navy-dark:
                #04152c;

            --navy-soft:
                #102f58;

            --gold:
                #dcae32;

            --gold-dark:
                #c99a20;

            --text:
                #0b1f3a;

            --muted:
                #64748b;

            --border:
                #e2e8f0;

            --white:
                #ffffff;

        }


        /* ==========================================================
        RESET
        ========================================================== */

        * {
            box-sizing: border-box;
        }


        html,
        body {

            margin: 0;
            padding: 0;

            width: 100%;
            min-height: 100%;

            font-family:
                'Manrope',
                sans-serif;

            background:
                #f5f7fa;

            color:
                var(--text);

        }


        body {

            min-height: 100vh;
            min-height: 100dvh;

        }


        /* ==========================================================
        LOGIN PAGE
        ========================================================== */

        .login-page {

            min-height: 100vh;
            min-height: 100dvh;

            display: flex;

            background:
                #f5f7fa;

        }


        /* ==========================================================
        LEFT BRAND PANEL
        ========================================================== */

        .login-brand {

            position: relative;

            width: 55%;

            min-height: 100vh;

            overflow: hidden;

            display: flex;

            align-items: center;

            padding:
                clamp(40px, 7vw, 100px);

            color:
                #fff;

            background:

                radial-gradient(
                    circle at 85% 20%,
                    rgba(220,174,50,.16),
                    transparent 25%
                ),

                radial-gradient(
                    circle at 15% 85%,
                    rgba(255,255,255,.06),
                    transparent 30%
                ),

                linear-gradient(
                    135deg,
                    #06182f 0%,
                    #0a2344 55%,
                    #12345f 100%
                );

        }


        /* ==========================================================
        DECORATIVE CIRCLE
        ========================================================== */

        .login-brand::before {

            content: '';

            position: absolute;

            width: 520px;
            height: 520px;

            border-radius: 50%;

            right: -180px;
            top: -180px;

            border:
                1px solid rgba(255,255,255,.08);

            box-shadow:
                0 0 0 70px rgba(255,255,255,.015),
                0 0 0 140px rgba(255,255,255,.01);

        }


        .login-brand::after {

            content: '';

            position: absolute;

            width: 420px;
            height: 420px;

            border-radius: 50%;

            left: -220px;
            bottom: -220px;

            background:
                rgba(220,174,50,.05);

            border:
                1px solid rgba(220,174,50,.08);

        }


        /* ==========================================================
        BRAND CONTENT
        ========================================================== */

        .login-brand__content {

            position: relative;

            z-index: 2;

            width: 100%;
            max-width: 650px;

        }


        /* ==========================================================
        LOGO
        ========================================================== */

        .login-brand__logo {

            display: inline-flex;

            align-items: center;

            margin-bottom: 50px;

        }


        .login-brand__logo img {

            width:
                clamp(90px, 10vw, 130px);

            height: auto;

            object-fit: contain;

        }


        /* ==========================================================
        EYEBROW
        ========================================================== */

        .login-eyebrow {

            display: flex;

            align-items: center;

            gap: 12px;

            margin-bottom: 20px;

            color:
                var(--gold);

            font-size: 11px;

            font-weight: 800;

            letter-spacing: 3px;

            text-transform: uppercase;

        }


        .login-eyebrow::before {

            content: '';

            width: 34px;
            height: 2px;

            background:
                var(--gold);

        }


        /* ==========================================================
        BRAND TITLE
        ========================================================== */

        .login-brand h1 {

            margin: 0 0 25px;

            max-width: 650px;

            color:
                #fff;

            font-size:
                clamp(42px, 5vw, 72px);

            font-weight: 800;

            line-height: 1.05;

            letter-spacing:
                -2px;

        }


        .login-brand h1 span {

            color:
                var(--gold);

        }


        /* ==========================================================
        BRAND DESCRIPTION
        ========================================================== */

        .login-brand__description {

            max-width: 540px;

            margin: 0;

            color:
                rgba(255,255,255,.72);

            font-size: 15px;

            line-height: 1.9;

        }


        /* ==========================================================
        BRAND FEATURES
        ========================================================== */

        .login-features {

            display: flex;

            flex-wrap: wrap;

            gap: 12px 24px;

            margin-top: 40px;

        }


        .login-feature {

            display: flex;

            align-items: center;

            gap: 9px;

            color:
                rgba(255,255,255,.78);

            font-size: 12px;

            font-weight: 600;

        }


        .login-feature i {

            color:
                var(--gold);

            font-size: 11px;

        }


        /* ==========================================================
        COPYRIGHT
        ========================================================== */

        .login-brand__footer {

            position: absolute;

            left:
                clamp(40px, 7vw, 100px);

            bottom: 35px;

            color:
                rgba(255,255,255,.45);

            font-size: 10px;

        }


        /* ==========================================================
        RIGHT PANEL
        ========================================================== */

        .login-form-panel {

            width: 45%;

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding:
                40px;

            background:
                #fff;

        }


        /* ==========================================================
        LOGIN BOX
        ========================================================== */

        .login-box {

            width: 100%;

            max-width: 420px;

        }


        /* ==========================================================
        MOBILE LOGO
        ========================================================== */

        .login-mobile-logo {

            display: none;

            text-align: center;

            margin-bottom: 35px;

        }


        .login-mobile-logo img {

            width: 100px;

            height: auto;

        }


        /* ==========================================================
        LOGIN HEADING
        ========================================================== */

        .login-heading {

            margin-bottom: 35px;

        }


        .login-heading__eyebrow {

            display: block;

            margin-bottom: 10px;

            color:
                var(--gold-dark);

            font-size: 10px;

            font-weight: 800;

            letter-spacing: 2.5px;

            text-transform: uppercase;

        }


        .login-heading h2 {

            margin: 0 0 10px;

            color:
                var(--text);

            font-size:
                clamp(28px, 3vw, 36px);

            font-weight: 800;

            letter-spacing:
                -1px;

        }


        .login-heading p {

            margin: 0;

            color:
                var(--muted);

            font-size: 13px;

            line-height: 1.7;

        }


        /* ==========================================================
        ERROR BOX
        ========================================================== */

        .login-error {

            display: flex;

            align-items: flex-start;

            gap: 12px;

            margin-bottom: 24px;

            padding:
                14px 16px;

            border:
                1px solid #fecdd3;

            border-radius: 12px;

            background:
                #fff1f2;

            color:
                #be123c;

            font-size: 12px;

            line-height: 1.5;

        }


        .login-error i {

            margin-top: 2px;

        }


        /* ==========================================================
        FORM GROUP
        ========================================================== */

        .login-form-group {

            margin-bottom: 22px;

        }


        .login-form-label {

            display: block;

            margin-bottom: 9px;

            color:
                #172b45;

            font-size: 12px;

            font-weight: 700;

        }


        /* ==========================================================
        INPUT
        ========================================================== */

        .login-input-wrapper {

            position: relative;

        }


        .login-input-icon {

            position: absolute;

            top: 50%;
            left: 17px;

            z-index: 2;

            transform:
                translateY(-50%);

            color:
                #94a3b8;

            font-size: 14px;

        }


        .login-input {

            width: 100%;

            height: 54px;

            padding:
                0 48px 0 48px;

            border:
                1px solid var(--border);

            border-radius: 12px;

            outline: none;

            background:
                #fff;

            color:
                var(--text);

            font-family:
                inherit;

            font-size: 13px;

            transition:
                .25s ease;

        }


        .login-input::placeholder {

            color:
                #a1acbb;

        }


        .login-input:hover {

            border-color:
                #cbd5e1;

        }


        .login-input:focus {

            border-color:
                var(--gold);

            box-shadow:
                0 0 0 4px rgba(220,174,50,.12);

        }


        /* ==========================================================
        PASSWORD TOGGLE
        ========================================================== */

        .password-toggle {

            position: absolute;

            top: 50%;
            right: 16px;

            transform:
                translateY(-50%);

            padding: 4px;

            border: none;

            background: transparent;

            color:
                #94a3b8;

            cursor: pointer;

            transition:
                .2s ease;

        }


        .password-toggle:hover {

            color:
                var(--gold-dark);

        }


        /* ==========================================================
        LOGIN BUTTON
        ========================================================== */

        .btn-login {

            width: 100%;

            height: 54px;

            margin-top: 5px;

            border: none;

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 10px;

            background:
                var(--gold);

            color:
                #fff;

            font-family:
                inherit;

            font-size: 13px;

            font-weight: 800;

            cursor: pointer;

            transition:
                .25s ease;

        }


        .btn-login:hover {

            background:
                var(--gold-dark);

            transform:
                translateY(-2px);

            box-shadow:
                0 12px 25px rgba(220,174,50,.25);

        }


        .btn-login:active {

            transform:
                translateY(0);

        }


        /* ==========================================================
        SECURITY NOTE
        ========================================================== */

        .login-security {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            margin-top: 22px;

            color:
                #94a3b8;

            font-size: 10px;

        }


        .login-security i {

            color:
                var(--gold);

        }


        /* ==========================================================
        RESPONSIVE
        ========================================================== */

        @media (max-width: 991px) {

            .login-brand {

                width: 50%;

                padding:
                    50px;

            }

            .login-form-panel {

                width: 50%;

                padding:
                    35px;

            }

            .login-brand h1 {

                font-size: 42px;

            }

        }


        /* ==========================================================
        MOBILE
        ========================================================== */

        @media (max-width: 767px) {

            .login-page {

                min-height: 100vh;

                background:
                    #fff;

            }


            .login-brand {

                display: none;

            }


            .login-form-panel {

                width: 100%;

                min-height: 100vh;

                min-height: 100dvh;

                padding:
                    35px 24px;

                align-items:
                    center;

            }


            .login-box {

                max-width:
                    420px;

            }


            .login-mobile-logo {

                display: block;

            }


            .login-mobile-logo img {

                width:
                    95px;

            }


            .login-heading {

                text-align:
                    center;

                margin-bottom:
                    30px;

            }


            .login-heading h2 {

                font-size:
                    28px;

            }


            .login-heading p {

                max-width:
                    300px;

                margin:
                    0 auto;

            }

        }


        /* ==========================================================
        SMALL MOBILE
        ========================================================== */

        @media (max-width: 380px) {

            .login-form-panel {

                padding:
                    28px 20px;

            }


            .login-mobile-logo {

                margin-bottom:
                    25px;

            }


            .login-heading {

                margin-bottom:
                    25px;

            }


            .login-input,
            .btn-login {

                height:
                    52px;

            }

        }

    </style>

</head>


<body>


<div class="login-page">


    <!-- ==========================================================
    BRAND PANEL
    ========================================================== -->

    <section class="login-brand">

        <div class="login-brand__content">


            <div class="login-brand__logo">

                <img
                    src="<?= base_url('assets/frontend/img/logo-white.png'); ?>"
                    alt="Sinergi Asset">

            </div>


            <div class="login-eyebrow">

                SINERGI ASSET

            </div>


            <h1>

                Integrated Asset
                <br>

                <span>Management</span>
                <br>

                & Property Solutions

            </h1>


            <p class="login-brand__description">

                Welcome to the Sinergi Asset management
                platform. Manage your property portfolio,
                asset information, and business operations
                through one centralized system.

            </p>


            <div class="login-features">

                <div class="login-feature">

                    <i class="fa-solid fa-circle-check"></i>

                    Professional Management

                </div>


                <div class="login-feature">

                    <i class="fa-solid fa-circle-check"></i>

                    Trusted Partnership

                </div>


                <div class="login-feature">

                    <i class="fa-solid fa-circle-check"></i>

                    Integrated Solutions

                </div>

            </div>

        </div>


        <div class="login-brand__footer">

            © <?= date('Y'); ?>
            PT Sinergi Aset Jagat Integrasi.
            All Rights Reserved.

        </div>

    </section>


    <!-- ==========================================================
    LOGIN FORM
    ========================================================== -->

    <section class="login-form-panel">

        <div class="login-box">


            <!-- Mobile Logo -->

            <div class="login-mobile-logo">

                <img
                    src="<?= base_url('assets/frontend/img/logo.png'); ?>"
                    alt="Sinergi Asset">

            </div>


            <!-- Heading -->

            <div class="login-heading">

                <span class="login-heading__eyebrow">

                    ADMINISTRATOR ACCESS

                </span>

                <h2>

                    Welcome back

                </h2>

                <p>

                    Sign in to access your Sinergi Asset
                    management dashboard.

                </p>

            </div>


            <!-- Error -->

            <?php if ($this->session->flashdata('error')): ?>

                <div class="login-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <div>

                        <?= $this->session->flashdata('error'); ?>

                    </div>

                </div>

            <?php endif; ?>


            <!-- Form -->

           <form action="<?= base_url('auth/process_login'); ?>" method="post">


                <!-- Username -->

                <div class="login-form-group">

                    <label
                        for="username"
                        class="login-form-label">

                        Username

                    </label>


                    <div class="login-input-wrapper">

                        <i
                            class="fa-regular fa-user login-input-icon">
                        </i>


                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="login-input"
                            placeholder="Enter your username"
                            autocomplete="username"
                            required>

                    </div>

                </div>


                <!-- Password -->

                <div class="login-form-group">

                    <label
                        for="password"
                        class="login-form-label">

                        Password

                    </label>


                    <div class="login-input-wrapper">

                        <i
                            class="fa-solid fa-lock login-input-icon">
                        </i>


                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="login-input"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required>


                        <button
                            type="button"
                            class="password-toggle"
                            id="togglePassword"
                            aria-label="Show password">

                            <i
                                class="fa-regular fa-eye"
                                id="passwordIcon">
                            </i>

                        </button>

                    </div>

                </div>


                <!-- Submit -->

                <button
                    type="submit"
                    class="btn-login">

                    <span>

                        Sign In

                    </span>

                    <i class="fa-solid fa-arrow-right"></i>

                </button>


                <!-- Security -->

                <div class="login-security">

                    <i class="fa-solid fa-shield-halved"></i>

                    Secure access to Sinergi Asset management system

                </div>

            </form>

        </div>

    </section>

</div>


<!-- ==========================================================
JS
========================================================== -->

<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js'); ?>"></script>

<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>


<script>

    document.addEventListener('DOMContentLoaded', function () {


        /*
        |--------------------------------------------------------------------------
        | PASSWORD TOGGLE
        |--------------------------------------------------------------------------
        */

        const togglePassword =
            document.getElementById('togglePassword');

        const password =
            document.getElementById('password');

        const passwordIcon =
            document.getElementById('passwordIcon');


        if (
            togglePassword &&
            password &&
            passwordIcon
        ) {

            togglePassword.addEventListener(
                'click',
                function () {

                    const isPassword =
                        password.getAttribute('type') === 'password';


                    password.setAttribute(
                        'type',
                        isPassword
                            ? 'text'
                            : 'password'
                    );


                    passwordIcon.classList.toggle(
                        'fa-eye',
                        !isPassword
                    );


                    passwordIcon.classList.toggle(
                        'fa-eye-slash',
                        isPassword
                    );


                    togglePassword.setAttribute(
                        'aria-label',
                        isPassword
                            ? 'Hide password'
                            : 'Show password'
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE ERROR AFTER USER STARTS TYPING
        |--------------------------------------------------------------------------
        */

        const inputs =
            document.querySelectorAll('.login-input');

        const errorBox =
            document.querySelector('.login-error');


        if (errorBox && inputs.length) {

            inputs.forEach(function (input) {

                input.addEventListener(
                    'input',
                    function () {

                        errorBox.style.display =
                            'none';

                    }
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | SUBMIT LOADING STATE
        |--------------------------------------------------------------------------
        */

        const loginForm =
            document.querySelector(
                'form[action*="process_login"]'
            );

        const loginButton =
            document.querySelector('.btn-login');


        if (loginForm && loginButton) {

            loginForm.addEventListener(
                'submit',
                function () {

                    loginButton.disabled =
                        true;

                    loginButton.style.opacity =
                        '0.75';

                    loginButton.innerHTML = `
                        <span>Signing in...</span>
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    `;

                }
            );

        }

    });

</script>


</body>

</html>