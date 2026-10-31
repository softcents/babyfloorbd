(function () {
    'use strict';

    /* Password show/hide */
    document.querySelectorAll('.auth-toggle-pass').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('data-target'));
            if (!input) return;
            var isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            var icon = btn.querySelector('i');
            if (icon) {
                icon.className = isPass ? 'far fa-eye-slash' : 'far fa-eye';
            }
        });
    });

    function showError(fieldId, message) {
        var el = document.querySelector('.auth-error[data-for="' + fieldId + '"]');
        if (el) {
            el.textContent = message || '';
            el.classList.toggle('is-visible', !!message);
        }
        var input = document.getElementById(fieldId);
        if (input) {
            input.classList.toggle('is-invalid', !!message);
        }
    }

    function clearErrors(form) {
        form.querySelectorAll('.auth-error').forEach(function (el) {
            el.textContent = '';
            el.classList.remove('is-visible');
        });
        form.querySelectorAll('.is-invalid').forEach(function (el) {
            el.classList.remove('is-invalid');
        });
    }

    function isValidEmail(val) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    }

    function isValidPhone(val) {
        return /^01[3-9]\d{8}$/.test(val.replace(/\s/g, ''));
    }

    /* Login */
    var loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(loginForm);

            var user = document.getElementById('loginUser');
            var pass = document.getElementById('loginPassword');
            var valid = true;

            if (!user.value.trim()) {
                showError('loginUser', 'মোবাইল নম্বর বা ইমেইল দিন।');
                valid = false;
            } else if (!isValidEmail(user.value.trim()) && !isValidPhone(user.value.trim())) {
                showError('loginUser', 'সঠিক মোবাইল নম্বর বা ইমেইল দিন।');
                valid = false;
            }

            if (!pass.value) {
                showError('loginPassword', 'পাসওয়ার্ড দিন।');
                valid = false;
            } else if (pass.value.length < 6) {
                showError('loginPassword', 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষর হতে হবে।');
                valid = false;
            }

            if (valid) {
                window.location.href = 'index.html';
            }
        });
    }

    /* Register */
    var registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(registerForm);

            var name = document.getElementById('regName');
            var phone = document.getElementById('regPhone');
            var email = document.getElementById('regEmail');
            var password = document.getElementById('regPassword');
            var confirm = document.getElementById('regConfirm');
            var terms = document.getElementById('regTerms');
            var valid = true;

            if (!name.value.trim()) {
                showError('regName', 'আপনার নাম লিখুন।');
                valid = false;
            }

            if (!isValidPhone(phone.value)) {
                showError('regPhone', 'সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন।');
                valid = false;
            }

            if (email.value.trim() && !isValidEmail(email.value.trim())) {
                showError('regEmail', 'সঠিক ইমেইল ঠিকানা দিন।');
                valid = false;
            }

            if (!password.value || password.value.length < 6) {
                showError('regPassword', 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষর হতে হবে।');
                valid = false;
            }

            if (password.value !== confirm.value) {
                showError('regConfirm', 'পাসওয়ার্ড মিলছে না।');
                valid = false;
            }

            if (!terms.checked) {
                showError('regTerms', 'শর্তাবলী মেনে নিতে হবে।');
                valid = false;
            }

            if (valid) {
                window.location.href = 'login.html';
            }
        });
    }

    /* Social buttons — demo */
    document.querySelectorAll('.auth-social-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            alert('সোশ্যাল লগইন শীঘ্রই যুক্ত হবে।');
        });
    });
})();
