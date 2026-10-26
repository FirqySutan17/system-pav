
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="icon"
        type="image/png"
        href="<?= base_url('assets/frontend/img/pav-logo.png'); ?>"
    >

    <link
        rel="apple-touch-icon"
        href="<?= base_url('assets/frontend/img/pav-logo.png'); ?>"
    >

    <title>PT PITEK BALAP PERKASA | Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --pav-orange: #f59a23;
            --pav-gold: #ffc65c;
            --pav-dark: #111111;
            --pav-muted: #a6a6a6;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--pav-dark);
            color: #ffffff;
        }

        .home-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            position: relative;
            overflow: hidden;
            isolation: isolate;
            background:
                radial-gradient(
                    ellipse at 50% 45%,
                    rgba(245, 154, 35, 0.10) 0%,
                    rgba(245, 154, 35, 0.025) 35%,
                    transparent 68%
                ),
                #111111;
        }

        /* Decorative orange accents */
        .home-page::before,
        .home-page::after {
            content: "";
            position: absolute;
            z-index: -1;
            pointer-events: none;
            border-radius: 50%;
        }

        .home-page::before {
            width: 520px;
            height: 520px;
            top: -360px;
            right: -140px;
            border: 1px solid rgba(245, 154, 35, 0.25);
            box-shadow:
                0 0 0 45px rgba(245, 154, 35, 0.025),
                0 0 0 90px rgba(245, 154, 35, 0.02);
        }

        .home-page::after {
            width: 450px;
            height: 450px;
            bottom: -350px;
            left: -150px;
            border: 1px solid rgba(245, 154, 35, 0.2);
            box-shadow:
                0 0 0 40px rgba(245, 154, 35, 0.025),
                0 0 0 80px rgba(245, 154, 35, 0.02);
        }

        .home-content {
            width: 100%;
            max-width: 680px;
            text-align: center;
            padding: 54px 48px 44px;
            border: 1px solid rgba(255, 190, 90, 0.18);
            border-radius: 24px;
            background: rgba(24, 24, 24, 0.88);
            box-shadow:
                0 25px 80px rgba(0, 0, 0, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.025);
            animation: fadeIn 0.8s ease both;
        }

        .logo-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 36px;
        }

        .home-logo {
            display: block;
            width: 100%;
            max-width: 440px;
            height: auto;
            object-fit: contain;
        }

        .company-label {
            color: var(--pav-orange);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .company-name {
            color: #ffffff;
            font-size: clamp(23px, 4vw, 32px);
            font-weight: 700;
            line-height: 1.35;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .company-description {
            max-width: 440px;
            margin: 0 auto 34px;
            color: var(--pav-muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .gold-divider {
            width: 65px;
            height: 3px;
            margin: 0 auto 30px;
            border-radius: 10px;
            background: linear-gradient(
                90deg,
                var(--pav-orange),
                var(--pav-gold)
            );
        }

        .btn-login {
            width: 100%;
            max-width: 340px;
            min-height: 58px;
            margin: 0 auto;
            padding: 15px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;

            color: #17110a;
            background: linear-gradient(
                110deg,
                #e88a16 0%,
                #ffbd4a 52%,
                #f39a20 100%
            );

            border: 1px solid rgba(255, 220, 150, 0.65);
            border-radius: 9px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;

            box-shadow: 0 8px 25px rgba(245, 154, 35, 0.13);
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                filter 0.25s ease;
        }

        .btn-login:hover {
            color: #111111;
            filter: brightness(1.08);
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(245, 154, 35, 0.25);
        }

        .btn-login:focus-visible {
            outline: 3px solid #ffffff;
            outline-offset: 4px;
        }

        .login-icon {
            width: 21px;
            height: 21px;
            flex-shrink: 0;
        }

        .home-footer {
            margin-top: 38px;
            padding-top: 22px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            color: #737373;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .home-footer span {
            color: var(--pav-orange);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .home-page {
                padding: 20px 16px;
            }

            .home-content {
                padding: 42px 24px 28px;
                border-radius: 18px;
            }

            .home-logo {
                max-width: 330px;
            }

            .logo-wrapper {
                margin-bottom: 30px;
            }

            .company-label {
                font-size: 9px;
                letter-spacing: 3px;
            }

            .company-name {
                letter-spacing: 0.5px;
            }

            .company-description {
                font-size: 13px;
            }

            .home-footer {
                margin-top: 30px;
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

<main class="home-page">

    <section class="home-content">

        <!-- PAV Logo -->
        <div class="logo-wrapper">
            <img
                src="<?= base_url('assets/frontend/img/pav-logo.png'); ?>"
                alt="Logo PAV - PT Pitek Balap Perkasa"
                class="home-logo"
            >
        </div>

        <div class="company-label">
            Welcome to
        </div>

        <h1 class="company-name">
            PT PITEK BALAP PERKASA
        </h1>

        <div class="gold-divider"></div>

        <p class="company-description">
            Welcome to the official platform of
            PT Pitek Balap Perkasa.
            Please log in to continue to the system.
        </p>

        <!-- Login Button -->
        <a href="<?= site_url('login'); ?>" class="btn-login">

            <svg class="login-icon"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 aria-hidden="true">

                <path d="M10 17l5-5-5-5"/>
                <path d="M15 12H3"/>
                <path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/>

            </svg>

            <span>LOGIN TO SYSTEM</span>

        </a>

        <footer class="home-footer">
            &copy; <?= date('Y'); ?>
            <span>PT PITEK BALAP PERKASA</span>.
            All rights reserved.
        </footer>

    </section>

</main>

</body>
</html>
