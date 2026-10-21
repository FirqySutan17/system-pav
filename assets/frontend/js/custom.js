document.addEventListener("DOMContentLoaded", function () {
  const header = document.getElementById("header");

  const menu = document.getElementById("mobileMenu");

  const overlay = document.querySelector(".mobile-overlay");

  const toggle = document.getElementById("navbarToggle");

  const close = document.getElementById("mobileClose");

  const mobileLinks = document.querySelectorAll(".mobile-menu a");

  /* ===============================
       Sticky Header
    =============================== */

  const logo = document.getElementById("navbarLogo");

  function stickyHeader() {
    if (!header) {
      return;
    }

    const isTransparentPage = header.dataset.transparent === "true";

    /*
    |--------------------------------------------------------------------------
    | SOLID HEADER PAGE
    |--------------------------------------------------------------------------
    |
    | Property detail dan halaman lain yang tidak menggunakan
    | transparent header akan selalu menggunakan navbar putih.
    |
    */

    if (!isTransparentPage) {
      header.classList.add("scrolled");

      if (logo && logo.dataset.dark) {
        logo.src = logo.dataset.dark;
      }

      return;
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSPARENT HEADER PAGE
    |--------------------------------------------------------------------------
    */

    if (window.scrollY > 40) {
      header.classList.add("scrolled");

      if (logo && logo.dataset.dark) {
        logo.src = logo.dataset.dark;
      }
    } else {
      header.classList.remove("scrolled");

      if (logo && logo.dataset.white) {
        logo.src = logo.dataset.white;
      }
    }
  }

  stickyHeader();

  window.addEventListener("scroll", stickyHeader, { passive: true });

  /* ==========================================================
   Property Gallery Lightbox
========================================================== */

  const lightbox = document.getElementById("propertyLightbox");

  if (lightbox) {
    const lightboxImage = document.getElementById("lightboxImage");

    const lightboxCurrent = document.getElementById("lightboxCurrent");

    const lightboxClose = document.getElementById("lightboxClose");

    const lightboxPrev = document.getElementById("lightboxPrev");

    const lightboxNext = document.getElementById("lightboxNext");

    const thumbnails = lightbox.querySelectorAll(
      ".property-lightbox__thumbnail",
    );

    const openButtons = document.querySelectorAll("[data-open-gallery]");

    /*
    |--------------------------------------------------------------------------
    | Build Image Array
    |--------------------------------------------------------------------------
    */

    const galleryImages = Array.from(thumbnails).map(function (thumbnail) {
      return thumbnail.querySelector("img").src;
    });

    let currentIndex = 0;

    /*
    |--------------------------------------------------------------------------
    | Show Image
    |--------------------------------------------------------------------------
    */

    function showLightboxImage(index) {
      if (!galleryImages.length) {
        return;
      }

      /*
        Loop image
        */

      if (index < 0) {
        index = galleryImages.length - 1;
      }

      if (index >= galleryImages.length) {
        index = 0;
      }

      currentIndex = index;

      /*
        Main Image
        */

      lightboxImage.src = galleryImages[currentIndex];

      /*
        Counter
        */

      lightboxCurrent.textContent = currentIndex + 1;

      /*
        Thumbnail Active
        */

      thumbnails.forEach(function (thumbnail) {
        thumbnail.classList.remove("active");
      });

      if (thumbnails[currentIndex]) {
        thumbnails[currentIndex].classList.add("active");

        thumbnails[currentIndex].scrollIntoView({
          behavior: "smooth",
          block: "nearest",
          inline: "center",
        });
      }
    }

    /*
    |--------------------------------------------------------------------------
    | Open
    |--------------------------------------------------------------------------
    */

    function openLightbox(index = 0) {
      showLightboxImage(index);

      lightbox.classList.add("active");

      lightbox.setAttribute("aria-hidden", "false");

      document.body.classList.add("lightbox-open");
    }

    /*
    |--------------------------------------------------------------------------
    | Close
    |--------------------------------------------------------------------------
    */

    function closeLightbox() {
      lightbox.classList.remove("active");

      lightbox.setAttribute("aria-hidden", "true");

      document.body.classList.remove("lightbox-open");
    }

    /*
    |--------------------------------------------------------------------------
    | Open Buttons
    |--------------------------------------------------------------------------
    */

    openButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        const index = parseInt(this.dataset.openGallery) || 0;

        openLightbox(index);
      });
    });

    /*
    |--------------------------------------------------------------------------
    | Gallery Images
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll("[data-gallery-index]").forEach(function (image) {
      image.style.cursor = "pointer";

      image.addEventListener("click", function () {
        openLightbox(parseInt(this.dataset.galleryIndex) || 0);
      });
    });

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    lightboxPrev.addEventListener("click", function () {
      showLightboxImage(currentIndex - 1);
    });

    lightboxNext.addEventListener("click", function () {
      showLightboxImage(currentIndex + 1);
    });

    /*
    |--------------------------------------------------------------------------
    | Thumbnail
    |--------------------------------------------------------------------------
    */

    thumbnails.forEach(function (thumbnail) {
      thumbnail.addEventListener("click", function () {
        showLightboxImage(parseInt(this.dataset.index));
      });
    });

    /*
    |--------------------------------------------------------------------------
    | Close
    |--------------------------------------------------------------------------
    */

    lightboxClose.addEventListener("click", closeLightbox);

    lightbox
      .querySelector(".property-lightbox__backdrop")
      .addEventListener("click", closeLightbox);

    /*
    |--------------------------------------------------------------------------
    | Keyboard
    |--------------------------------------------------------------------------
    */

    document.addEventListener("keydown", function (event) {
      if (!lightbox.classList.contains("active")) {
        return;
      }

      if (event.key === "Escape") {
        closeLightbox();
      }

      if (event.key === "ArrowLeft") {
        showLightboxImage(currentIndex - 1);
      }

      if (event.key === "ArrowRight") {
        showLightboxImage(currentIndex + 1);
      }
    });
  }

  /* ===============================
       Open Mobile Menu
    =============================== */

  function openMenu() {
    menu.classList.add("active");
    overlay.classList.add("active");
    toggle.classList.add("active");
    document.body.style.overflow = "hidden";
  }

  /* ===============================
       Close Mobile Menu
    =============================== */

  function closeMenu() {
    menu.classList.remove("active");
    overlay.classList.remove("active");
    toggle.classList.remove("active");
    document.body.style.overflow = "";
  }

  if (toggle) {
    toggle.addEventListener("click", openMenu);
  }

  if (close) {
    close.addEventListener("click", closeMenu);
  }

  overlay.addEventListener("click", closeMenu);

  mobileLinks.forEach(function (link) {
    link.addEventListener("click", closeMenu);
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      closeMenu();
    }
  });
});
