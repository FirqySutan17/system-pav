    <!-- ==========================================================
        FOOTER
    ========================================================== -->

    <footer class="footer">

        <div class="container">

            <div class="row gy-5">

                <!-- Company -->
                <div class="col-lg-4">

                    <div class="footer-brand">

                        <img src="<?= base_url('assets/frontend/img/logo-white.png'); ?>"
                            alt="PT Sinergi Aset Jagat Integrasi"
                            class="footer-logo">

                        <p class="footer-description">

                            PT Sinergi Aset Jagat Integrasi delivers
                            strategic consulting, asset management,
                            and property management
                            services for sustainable business growth.

                        </p>

                        <!-- <div class="footer-social">

                            <a href="#"><i class="bi bi-linkedin"></i></a>

                            <a href="#"><i class="bi bi-instagram"></i></a>

                        </div> -->

                    </div>

                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-4">

                    <h5>Quick Links</h5>

                    <ul>

                        <li><a href="<?= site_url(); ?>">Home</a></li>

                        <li><a href="<?= site_url('property'); ?>">Properties</a></li>

                    </ul>

                </div>

                <!-- Services -->
                <div class="col-lg-3 col-md-4">

                    <h5>Business Services</h5>

                    <ul>

                        <li>Management Consulting</li>

                        <li>Asset Management</li>

                        <li>Recovery Solution</li>

                        <li>Property Management</li>

                    </ul>

                </div>

                <!-- Contact -->
                <div class="col-lg-3 col-md-4">

                    <h5>Contact</h5>

                    <ul class="footer-contact">

                        <li>

                            <i class="bi bi-geo-alt-fill"></i>

                            Jl Dharmawangsa VI No. 3, Pulo - Kebayoran Baru Jakarta Selatan 12160

                        </li>

                        <li>

                            <i class="bi bi-whatsapp"></i>

                            +62 813-1687-4613

                        </li>

                        <li>

                            <i class="bi bi-telephone-fill"></i>

                            +62 21 3825 0767 

                        </li>

                    </ul>

                </div>

            </div>

            <div class="footer-bottom">

                <p>

                    © <?= date('Y'); ?>

                    PT Sinergi Aset Jagat Integrasi.

                    All Rights Reserved.

                </p>

            </div>

        </div>

    </footer>
    
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <!-- Custom -->
    <script src="<?= base_url('assets/frontend/js/custom.js');?>"></script>

    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>

</body>
</html>