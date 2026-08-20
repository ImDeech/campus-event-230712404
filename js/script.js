/* ==========================================================================
   UAJY Technology Workshop 2026 - Main JavaScript & jQuery Script
   File: js/script.js
   Specification: Form Validation, DOM Manipulation, jQuery Effects & Features
   ========================================================================== */

// Homepage slideshow works independently from the optional jQuery features.
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

// Execute script after DOM ready using jQuery
if (typeof $ !== "undefined") {
    $(document.body).ready(function () {
    console.log("UAJY Technology Workshop 2026 JavaScript loaded successfully.");

    /* ----------------------------------------------------------------------
       1. Theme Toggle (Dark Mode / Light Mode DOM Manipulation)
       ---------------------------------------------------------------------- */
    var savedTheme = localStorage.getItem("uajy_theme");
    if (savedTheme === "dark") {
        $("body").addClass("dark-theme");
        $("#themeToggleBtn").text("Mode Terang");
    }

    $("#themeToggleBtn").on("click", function () {
        $("body").toggleClass("dark-theme");
        var isDark = $("body").hasClass("dark-theme");

        if (isDark) {
            $(this).text("Mode Terang");
            localStorage.setItem("uajy_theme", "dark");
        } else {
            $(this).text("Mode Gelap");
            localStorage.setItem("uajy_theme", "light");
        }
    });

    /* ----------------------------------------------------------------------
       2. Countdown Timer Widget (DOM Manipulation)
       ---------------------------------------------------------------------- */
    if ($("#countdownWidget").length > 0) {
        var eventDate = new Date("August 25, 2026 08:00:00").getTime();

        function updateCountdown() {
            var now = new Date().getTime();
            var distance = eventDate - now;

            if (distance < 0) {
                $("#countdownWidget").html("<div class='timer-unit'>Event Sedang Berlangsung!</div>");
                return;
            }

            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // DOM manipulation
            $("#timerDays").text(days < 10 ? "0" + days : days);
            $("#timerHours").text(hours < 10 ? "0" + hours : hours);
            $("#timerMinutes").text(minutes < 10 ? "0" + minutes : minutes);
            $("#timerSeconds").text(seconds < 10 ? "0" + seconds : seconds);
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    /* ----------------------------------------------------------------------
       3. Live Character Counter for Textarea (DOM Manipulation)
       ---------------------------------------------------------------------- */
    var maxNotesChar = 200;
    $("#notes").on("input keyup", function () {
        var currentLength = $(this).val().length;
        var remaining = maxNotesChar - currentLength;

        if (remaining < 0) {
            $(this).val($(this).val().substring(0, maxNotesChar));
            remaining = 0;
        }

        // DOM update
        $("#charCount").text(remaining);
        if (remaining < 20) {
            $("#charCount").css("color", "var(--error-color)");
        } else {
            $("#charCount").css("color", "inherit");
        }
    });

    /* ----------------------------------------------------------------------
       4. Live Preview Registration Summary (DOM Manipulation)
       ---------------------------------------------------------------------- */
    function updateLivePreview() {
        var nameVal = $("#fullName").val().trim();
        var npmVal = $("#npm").val().trim();
        var sessionVal = $("#workshopSession").val();

        if (nameVal !== "" || npmVal !== "" || sessionVal !== "") {
            $("#livePreviewCard").slideDown(200);
            $("#prevName").text(nameVal !== "" ? nameVal : "-");
            $("#prevNpm").text(npmVal !== "" ? npmVal : "-");
            $("#prevSession").text(sessionVal !== "" ? sessionVal : "-");
        } else {
            $("#livePreviewCard").slideUp(200);
        }
    }

    $("#fullName, #npm, #workshopSession").on("input change", function () {
        updateLivePreview();
    });

    /* ----------------------------------------------------------------------
       5. Client-side Form Validation (Registration Form)
       ---------------------------------------------------------------------- */
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

    // Individual field validation functions
    function isNameValid(val) {
        return val.length >= 3;
    }

    function isNpmValid(val) {
        // NPM MUST BE EXACTLY 9 DIGITS per specification requirement
        var regex = /^\d{9}$/;
        return regex.test(val);
    }

    function isEmailValid(val) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(val);
    }

    function isPhoneValid(val) {
        var regex = /^\d{10,14}$/;
        return regex.test(val);
    }

    function isSelectValid(val) {
        return val !== "" && val !== null;
    }

    // Real-time blur validation
    $("#fullName").on("blur", function () {
        validateField("fullName", "fullNameError", isNameValid);
    });

    $("#npm").on("blur input", function () {
        // Sanitize non-digits
        var clean = $(this).val().replace(/\D/g, "");
        $(this).val(clean);
        validateField("npm", "npmError", isNpmValid);
    });

    $("#email").on("blur", function () {
        validateField("email", "emailError", isEmailValid);
    });

    $("#phone").on("blur input", function () {
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
            e.preventDefault(); // Stop form submission if invalid
            
            // Scroll to the first error element
            var $firstError = $(".is-invalid").first();
            if ($firstError.length > 0) {
                $("html, body").animate({
                    scrollTop: $firstError.offset().top - 100
                }, 400);
            }

            // Display alert banner via DOM
            $("#statusBanner")
                .html("<div class='alert alert-error'>Harap perbaiki kesalahan pada formulir sebelum mendaftar.</div>")
                .slideDown(200);

            return false;
        }

        // Form is valid! Allow native submission to php/process_registration.php
        return true;
    });

    // Reset button clears preview
    $("#resetBtn").on("click", function () {
        $(".form-control, .form-select, .form-textarea").removeClass("is-invalid");
        $(".error-message").hide();
        $("#livePreviewCard").slideUp(200);
        $("#statusBanner").slideUp(200);
        $("#charCount").text(maxNotesChar);
    });

    /* ----------------------------------------------------------------------
       6. Schedule Table Search Filter (jQuery Feature)
       ---------------------------------------------------------------------- */
    $("#scheduleSearchInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#scheduleTable tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    });
}
