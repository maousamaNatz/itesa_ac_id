// Form validation
const form = document.querySelector("form");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const passwordConfirmInput = document.getElementById("password_confirmation");
const usernameInput = document.getElementById("username");

form.addEventListener("submit", function (e) {
    let isValid = true;
    let errorMessage = "";

    // Validasi format email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailInput.value)) {
        isValid = false;
        errorMessage = "Masukkan alamat email yang valid";
        emailInput.classList.add("border-red-500");
    } else {
        emailInput.classList.remove("border-red-500");
    }

    // Validasi panjang password
    if (passwordInput.value.length < 8) {
        isValid = false;
        errorMessage = "Password harus minimal 8 karakter";
        passwordInput.classList.add("border-red-500");
    } else {
        passwordInput.classList.remove("border-red-500");
    }

    // Validasi konfirmasi password
    if (passwordInput.value !== passwordConfirmInput.value) {
        isValid = false;
        errorMessage = "Konfirmasi password tidak cocok";
        passwordConfirmInput.classList.add("border-red-500");
    } else {
        passwordConfirmInput.classList.remove("border-red-500");
    }

    // Validasi terms
    const termsCheckbox = document.getElementById("terms");
    if (!termsCheckbox.checked) {
        isValid = false;
        errorMessage = "Anda harus menyetujui syarat dan ketentuan";
    }

    // Validasi username
    if (usernameInput.value.length < 3) {
        isValid = false;
        errorMessage = "Username minimal 3 karakter";
        usernameInput.classList.add("border-red-500");
    } else if (!/^[a-zA-Z0-9_]+$/.test(usernameInput.value)) {
        isValid = false;
        errorMessage =
            "Username hanya boleh mengandung huruf, angka, dan underscore";
        usernameInput.classList.add("border-red-500");
    } else {
        usernameInput.classList.remove("border-red-500");
    }

    // Tampilkan error jika ada
    if (!isValid) {
        e.preventDefault();
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: errorMessage,
        });
    }
});

// Real-time email validation
emailInput.addEventListener("input", function () {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(this.value)) {
        this.classList.add("border-red-500");
        this.classList.remove("border-green-500");
    } else {
        this.classList.remove("border-red-500");
        this.classList.add("border-green-500");
    }
});

// Real-time password strength indicator
passwordInput.addEventListener("input", function () {
    const strength = checkPasswordStrength(this.value);
    updatePasswordStrengthIndicator(strength);
});

function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[!@#$%^&*(),.?":{}|<>]+/)) strength++;
    return strength;
}

function updatePasswordStrengthIndicator(strength) {
    const strengthText = [
        "Sangat Lemah",
        "Lemah",
        "Sedang",
        "Kuat",
        "Sangat Kuat",
    ];
    const strengthColors = ["red", "orange", "yellow", "lime", "green"];

    // Tambahkan atau update indikator kekuatan password
    let indicator = document.getElementById("password-strength");
    if (!indicator) {
        indicator = document.createElement("p");
        indicator.id = "password-strength";
        indicator.className = "mt-1 text-sm";
        passwordInput.parentNode.appendChild(indicator);
    }

    if (strength > 0) {
        indicator.textContent = `Kekuatan Password: ${
            strengthText[strength - 1]
        }`;
        indicator.className = `mt-1 text-sm text-${
            strengthColors[strength - 1]
        }-600`;
    } else {
        indicator.textContent = "Kekuatan Password: Sangat Lemah";
        indicator.className = "mt-1 text-sm text-red-600";
    }
}

// Real-time password confirmation validation
passwordConfirmInput.addEventListener("input", function () {
    if (this.value === passwordInput.value) {
        this.classList.remove("border-red-500");
        this.classList.add("border-green-500");
    } else {
        this.classList.add("border-red-500");
        this.classList.remove("border-green-500");
    }
});

// Real-time username validation
usernameInput.addEventListener("input", function () {
    if (this.value.length < 3 || !/^[a-zA-Z0-9_]+$/.test(this.value)) {
        this.classList.add("border-red-500");
        this.classList.remove("border-green-500");
    } else {
        this.classList.remove("border-red-500");
        this.classList.add("border-green-500");
    }
});

// Fungsi untuk toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const iconHide = document.getElementById(`${inputId}-icon-hide`);
    const iconShow = document.getElementById(`${inputId}-icon-show`);

    if (input.type === "password") {
        input.type = "text";
        iconHide.classList.add("hidden");
        iconShow.classList.remove("hidden");
    } else {
        input.type = "password";
        iconHide.classList.remove("hidden");
        iconShow.classList.add("hidden");
    }
}
