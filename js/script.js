// Home slideshow
(function () {
    function startCampusSlideshow() {
        var slides = document.querySelectorAll(".campus-slide");
        var dots = document.querySelectorAll("#sliderDots span");
        var caption = document.getElementById("sliderCaption");
        var captions = [
            "Kampus 1<br />&gt; St. Alfonsus",
            "Kampus 2<br />&gt; St. Thomas Aquinas",
            "Kampus 3<br />&gt; St. Bonaventura",
            "Kampus 4<br />&gt; St. Teresa"
        ];
        var currentSlide = 0;

        if (!slides.length) {
            return;
        }

        setInterval(function () {
            slides[currentSlide].className = "campus-slide";
            dots[currentSlide].className = "";
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].className = "campus-slide active-slide";
            dots[currentSlide].className = "active-dot";
            caption.innerHTML = captions[currentSlide];
        }, 4000);
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", startCampusSlideshow);
    } else {
        startCampusSlideshow();
    }
}());

// Setelah DOM siap menggunakkan jQuery
if (typeof $ !== "undefined") {
    $(document.body).ready(function () {
    console.log("UAJY Technology Workshop 2026 JavaScript loaded successfully.");
    // Client-side Form Validation (Registration Form)
    function validateField(fieldId, errorId, validationFn) {
        var $field = $("#" + fieldId);
        var $error = $("#" + errorId);
        var value = $field.val().trim();

        var isValid = validationFn(value);

        if (!isValid) {
            $field.addClass("is-invalid");
            $error.fadeIn(200);
            return false;
        } else {
            $field.removeClass("is-invalid");
            $error.fadeOut(200);
            return true;
        }
    }

    // Fungsi validasi bidang individual
    function isNameValid(val) {
        return val.length >= 3;
    }

    function isNpmValid(val) {
        // NPM harus 9 digit angka
        var regex = /^\d{9}$/;
        return regex.test(val);
    }

    function isEmailValid(val) {
        // Validasi email sederhana
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(val);
    }

    function isPhoneValid(val) {
        // Nomor telepon harus 10-14 digit angka
        var regex = /^\d{10,14}$/;
        return regex.test(val);
    }

    function isSelectValid(val) {
        // Validasi select (dropdown tidak kosong)
        return val !== "" && val !== null;
    }

    // Real-time blur validation
    $("#fullName").on("blur", function () {
        validateField("fullName", "fullNameError", isNameValid);
    });

    $("#npm").on("blur input", function () {
        // Karakter non-digit dihapus saat input
        var clean = $(this).val().replace(/\D/g, "");
        $(this).val(clean);
        validateField("npm", "npmError", isNpmValid);
    });

    $("#email").on("blur", function () {
        validateField("email", "emailError", isEmailValid);
    });

    $("#phone").on("blur input", function () {
        // Karakter non-digit dihapus saat input
        var clean = $(this).val().replace(/\D/g, "");
        $(this).val(clean);
        validateField("phone", "phoneError", isPhoneValid);
    });

    $("#studyProgram").on("change", function () {
        validateField("studyProgram", "studyProgramError", isSelectValid);
    });

    $("#workshopSession").on("change", function () {
        validateField("workshopSession", "workshopSessionError", isSelectValid);
    });

    // Form Submit Event Handler
    $("#registrationForm").on("submit", function (e) {
        var v1 = validateField("fullName", "fullNameError", isNameValid);
        var v2 = validateField("npm", "npmError", isNpmValid);
        var v3 = validateField("email", "emailError", isEmailValid);
        var v4 = validateField("phone", "phoneError", isPhoneValid);
        var v5 = validateField("studyProgram", "studyProgramError", isSelectValid);
        var v6 = validateField("workshopSession", "workshopSessionError", isSelectValid);

        if (!v1 || !v2 || !v3 || !v4 || !v5 || !v6) {
            e.preventDefault(); // Form tidak dikirim (dihentikan)
            
            // Scroll ke error pertama
            var $firstError = $(".is-invalid").first();
            if ($firstError.length > 0) {
                $("html, body").animate({
                    scrollTop: $firstError.offset().top - 100
                }, 400);
            }

            // Tampilkan error di atas form
            $("#statusBanner")
                .html("<div class='alert alert-error'>Harap perbaiki kesalahan pada formulir sebelum mendaftar.</div>")
                .slideDown(200);

            return false;
        }

        // Form valid!
        return true;
    });

    // Reset button
    $("#resetBtn").on("click", function () {
        $(".form-control, .form-select, .form-textarea").removeClass("is-invalid");
        $(".error-message").hide();
        $("#statusBanner").slideUp(200);
    });
    });
}
