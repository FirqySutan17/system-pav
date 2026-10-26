
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login | PT PITEK BALAP PERKASA</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png"
          href="<?= base_url('assets/frontend/img/pav-logo.png'); ?>">

    <link rel="apple-touch-icon"
          href="<?= base_url('assets/frontend/img/pav-logo.png'); ?>">

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="<?= base_url('assets/libs/bootstrap/dist/css/bootstrap.min.css'); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --pav-black: #111111;
            --pav-dark: #191714;
            --pav-orange: #f59a23;
            --pav-gold: #ffc65c;
            --pav-gold-dark: #d98212;
            --pav-text: #242424;
            --pav-muted: #858585;
            --pav-border: #e8e5e0;
            --pav-white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            font-family: 'Manrope', sans-serif;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            color: var(--pav-text);
            background: #f7f6f4;
        }

        .login-page {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
        }

        /* =====================================
           LEFT BRAND PANEL
        ===================================== */

        .login-brand {
            position: relative;
            isolation: isolate;
            width: 55%;
            min-height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: clamp(40px, 7vw, 100px);
            color: #fff;

            background:
                radial-gradient(
                    circle at 85% 20%,
                    rgba(245, 154, 35, .17),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 15% 85%,
                    rgba(255, 198, 92, .07),
                    transparent 32%
                ),
                linear-gradient(
                    135deg,
                    #101010 0%,
                    #191713 60%,
                    #292017 100%
                );
        }

        .login-brand::before,
        .login-brand::after {
            content: '';
            position: absolute;
            z-index: -1;
            pointer-events: none;
            border-radius: 50%;
        }

        .login-brand::before {
            width: 520px;
            height: 520px;
            right: -180px;
            top: -180px;
            border: 1px solid rgba(255, 198, 92, .15);
            box-shadow:
                0 0 0 65px rgba(255, 198, 92, .025),
                0 0 0 130px rgba(255, 198, 92, .018);
        }

        .login-brand::after {
            width: 420px;
            height: 420px;
            left: -220px;
            bottom: -220px;
            border: 1px solid rgba(245, 154, 35, .15);
            background: rgba(245, 154, 35, .025);
        }

        .login-brand__content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 650px;
            animation: fadeIn .8s ease both;
        }

        /* Logo */

        .login-brand__logo {
            display: block;
            margin-bottom: 48px;
        }

        .login-brand__logo img {
            display: block;
            width: min(100%, 430px);
            height: auto;
            object-fit: contain;
        }

        /* Eyebrow */

        .login-eyebrow {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            color: var(--pav-gold);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .login-eyebrow::before {
            content: '';
            width: 34px;
            height: 2px;
            background: linear-gradient(
                90deg,
                var(--pav-orange),
                var(--pav-gold)
            );
        }

        /* Brand title */

        .login-brand h1 {
            max-width: 650px;
            margin: 0 0 25px;
            color: #fff;
            font-size: clamp(38px, 4.5vw, 64px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -2px;
        }

        .login-brand h1 span {
            color: var(--pav-gold);
        }

        .login-brand__description {
            max-width: 500px;
            margin: 0;
            color: rgba(255, 255, 255, .70);
            font-size: 14px;
            line-height: 1.9;
        }

        /* Features */

        .login-features {
            display: flex;
            flex-wrap: wrap;
            gap: 14px 22px;
            margin-top: 35px;
        }

        .login-feature {
            display: flex;
            align-items: center;
            gap: 9px;
            color: rgba(255, 255, 255, .8);
            font-size: 11px;
            font-weight: 600;
        }

        .login-feature i {
            color: var(--pav-gold);
            font-size: 12px;
        }

        .login-brand__footer {
            position: absolute;
            left: clamp(40px, 7vw, 100px);
            bottom: 30px;
            color: rgba(255, 255, 255, .43);
            font-size: 10px;
        }

        /* =====================================
           RIGHT LOGIN PANEL
        ===================================== */

        .login-form-panel {
            width: 45%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 45px;
            background: #fff;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            animation: fadeIn .8s ease both;
        }

        .login-mobile-logo {
            display: none;
            text-align: center;
            margin-bottom: 32px;
        }

        .login-mobile-logo img {
            display: block;
            width: min(100%, 300px);
            max-height: 110px;
            margin: 0 auto;
            object-fit: contain;
        }

        /* Heading */

        .login-heading {
            margin-bottom: 32px;
        }

        .login-heading__eyebrow {
            display: block;
            margin-bottom: 10px;
            color: var(--pav-gold-dark);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .login-heading h2 {
            margin: 0 0 10px;
            color: #171717;
            font-size: clamp(28px, 3vw, 36px);
            font-weight: 800;
            letter-spacing: -1px;
        }

        .login-heading p {
            margin: 0;
            color: var(--pav-muted);
            font-size: 13px;
            line-height: 1.8;
        }

        /* Error */

        .login-error {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 24px;
            padding: 14px 16px;
            border: 1px solid #fecaca;
            border-radius: 10px;
            background: #fff1f2;
            color: #be123c;
            font-size: 12px;
            line-height: 1.6;
        }

        .login-error i {
            margin-top: 3px;
        }

        /* Form */

        .login-form-group {
            margin-bottom: 22px;
        }

        .login-form-label {
            display: block;
            margin-bottom: 9px;
            color: #292929;
            font-size: 12px;
            font-weight: 700;
        }

        .login-input-wrapper {
            position: relative;
        }

        .login-input-icon {
            position: absolute;
            top: 50%;
            left: 17px;
            z-index: 2;
            transform: translateY(-50%);
            color: #999;
            font-size: 14px;
        }

        .login-input {
            width: 100%;
            height: 54px;
            padding: 0 48px;
            border: 1px solid var(--pav-border);
            border-radius: 10px;
            outline: none;
            background: #fff;
            color: var(--pav-text);
            font-family: inherit;
            font-size: 13px;
            transition: border-color .2s, box-shadow .2s;
        }

        .login-input::placeholder {
            color: #a5a5a5;
        }

        .login-input:hover {
            border-color: #d4c8b6;
        }

        .login-input:focus {
            border-color: var(--pav-orange);
            box-shadow: 0 0 0 4px rgba(245, 154, 35, .12);
        }

        /* Password visibility */

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            padding: 5px;
            border: none;
            background: transparent;
            color: #999;
            cursor: pointer;
        }

        .password-toggle:hover {
            color: var(--pav-gold-dark);
        }

        /* Login button */

        .btn-login {
            width: 100%;
            min-height: 54px;
            margin-top: 5px;
            padding: 12px 20px;
            border: 1px solid rgba(255, 198, 92, .65);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;

            background: linear-gradient(
                110deg,
                #e88a16 0%,
                #ffbd4a 52%,
                #f39a20 100%
            );

            color: #20160b;
            font-family: inherit;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1px;
            cursor: pointer;
            transition: .25s ease;
        }

        .btn-login:hover {
            background: linear-gradient(
                110deg,
                #d98212,
                #f3a52d,
                #df8210
            );
            color: #17110a;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(245, 154, 35, .23);
        }

        .btn-login:disabled {
            opacity: .7;
            cursor: wait;
            transform: none;
        }

        /* Security */

        .login-security {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 22px;
            color: #999;
            font-size: 10px;
            text-align: center;
        }

        .login-security i {
            color: var(--pav-gold-dark);
        }

        .login-footer-mobile {
            display: none;
        }

        /* Animation */

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =====================================
           TABLET
        ===================================== */

        @media (max-width: 991px) {
            .login-brand {
                width: 50%;
                padding: 40px;
            }

            .login-form-panel {
                width: 50%;
                padding: 30px;
            }

            .login-brand__logo img {
                max-width: 340px;
            }

            .login-brand h1 {
                font-size: 40px;
                letter-spacing: -1px;
            }
        }

        /* =====================================
           MOBILE
        ===================================== */

        @media (max-width: 767px) {
            .login-page {
                background: #fff;
            }

            .login-brand {
                display: none;
            }

            .login-form-panel {
                width: 100%;
                min-height: 100vh;
                min-height: 100dvh;
                padding: 35px 24px;
            }

            .login-box {
                max-width: 420px;
            }

            .login-mobile-logo {
                display: block;
            }

            .login-heading {
                text-align: center;
                margin-bottom: 28px;
            }

            .login-heading h2 {
                font-size: 28px;
            }

            .login-heading p {
                max-width: 300px;
                margin: 0 auto;
            }

            .login-footer-mobile {
                display: block;
                margin-top: 30px;
                color: #999;
                font-size: 10px;
                text-align: center;
            }
        }

        @media (max-width: 380px) {
            .login-form-panel {
                padding: 28px 20px;
            }

            .login-input,
            .btn-login {
                min-height: 52px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body>

<div class="login-page">

    <!-- =====================================
         BRAND PANEL
    ====================================== -->

    <section class="login-brand">

        <div class="login-brand__content">

            <div class="login-brand__logo">
                <img
                    src="<?= base_url('assets/frontend/img/pav-logo.png'); ?>"
                    alt="PAV - PT Pitek Balap Perkasa">
            </div>

            <div class="login-eyebrow">
                Official Company Platform
            </div>

            <h1>
                PT PITEK<br>
                <span>BALAP PERKASA</span>
            </h1>

            <p class="login-brand__description">
                Welcome to the official platform of
                PT Pitek Balap Perkasa. Sign in to access
                your account and continue to the company system.
            </p>

            <div class="login-features">

                <div class="login-feature">
                    <i class="fa-solid fa-circle-check"></i>
                    Secure Access
                </div>

                <div class="login-feature">
                    <i class="fa-solid fa-circle-check"></i>
                    Reliable Platform
                </div>

                <div class="login-feature">
                    <i class="fa-solid fa-circle-check"></i>
                    Authorized Users
                </div>

            </div>

        </div>

        <div class="login-brand__footer">
            &copy; <?= date('Y'); ?>
            PT PITEK BALAP PERKASA. All Rights Reserved.
        </div>

    </section>

    <!-- =====================================
         LOGIN FORM
    ====================================== -->

    <section class="login-form-panel">

        <div class="login-box">

            <!-- Mobile Logo -->

            <div class="login-mobile-logo">
                <img
                    src="<?= base_url('assets/frontend/img/pav-logo.png'); ?>"
                    alt="PAV - PT Pitek Balap Perkasa">
            </div>

            <!-- Heading -->

            <div class="login-heading">

                <span class="login-heading__eyebrow">
                    Administrator Access
                </span>

                <h2>Welcome back</h2>

                <p>
                    Sign in with your username and password
                    to access the company system.
                </p>

            </div>

            <!-- Error Message -->

            <?php if ($this->session->flashdata('error')): ?>

                <div class="login-error" role="alert">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <div>
                        <?= html_escape($this->session->flashdata('error')); ?>
                    </div>

                </div>

            <?php endif; ?>

            <!-- Login Form -->

            <form
                action="<?= site_url('auth/process_login'); ?>"
                method="post"
                id="loginForm">

                <!-- Username -->

                <div class="login-form-group">

                    <label for="username" class="login-form-label">
                        Username
                    </label>

                    <div class="login-input-wrapper">

                        <i class="fa-regular fa-user login-input-icon"></i>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="login-input"
                            placeholder="Enter your username"
                            autocomplete="username"
                            required
                            autofocus>

                    </div>

                </div>

                <!-- Password -->

                <div class="login-form-group">

                    <label for="password" class="login-form-label">
                        Password
                    </label>

                    <div class="login-input-wrapper">

                        <i class="fa-solid fa-lock login-input-icon"></i>

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
                            aria-label="Show password"
                            aria-pressed="false">

                            <i class="fa-regular fa-eye"
                               id="passwordIcon"></i>

                        </button>

                    </div>

                </div>

                <!-- Submit -->

                <button type="submit" class="btn-login" id="loginButton">

                    <span id="loginButtonText">SIGN IN</span>

                    <i class="fa-solid fa-arrow-right"
                       id="loginButtonIcon"></i>

                </button>

                <!-- Security -->

                <div class="login-security">
                    <i class="fa-solid fa-shield-halved"></i>
                    Secure access to PT Pitek Balap Perkasa
                </div>

            </form>

            <div class="login-footer-mobile">
                &copy; <?= date('Y'); ?>
                PT PITEK BALAP PERKASA
            </div>

        </div>

    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');

    /* Show / hide password */

    if (togglePassword && password && passwordIcon) {

        togglePassword.addEventListener('click', function () {

            const showPassword = password.type === 'password';

            password.type = showPassword ? 'text' : 'password';

            passwordIcon.classList.toggle(
                'fa-eye',
                !showPassword
            );

            passwordIcon.classList.toggle(
                'fa-eye-slash',
                showPassword
            );

            togglePassword.setAttribute(
                'aria-label',
                showPassword ? 'Hide password' : 'Show password'
            );

            togglePassword.setAttribute(
                'aria-pressed',
                showPassword ? 'true' : 'false'
            );

        });

    }

    /* Hide error message when user edits input */

    const inputs = document.querySelectorAll('.login-input');
    const errorBox = document.querySelector('.login-error');

    if (errorBox) {

        inputs.forEach(function (input) {

            input.addEventListener('input', function () {
                errorBox.style.display = 'none';
            });

        });

    }

    /* Submit loading state */

    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const loginButtonText = document.getElementById('loginButtonText');
    const loginButtonIcon = document.getElementById('loginButtonIcon');

    if (loginForm && loginButton) {

        loginForm.addEventListener('submit', function () {

            if (!loginForm.checkValidity()) {
                return;
            }

            loginButton.disabled = true;
            loginButtonText.textContent = 'SIGNING IN...';

            loginButtonIcon.classList.remove('fa-arrow-right');
            loginButtonIcon.classList.add('fa-spinner', 'fa-spin');

        });

    }

});
</script>

</body>
</html>
