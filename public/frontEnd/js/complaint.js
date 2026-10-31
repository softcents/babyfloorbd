(function () {
    'use strict';

    function showError(fieldId, message) {
        var el = document.querySelector('.auth-error[data-for="' + fieldId + '"]');
        if (el) {
            el.textContent = message || '';
            el.classList.toggle('is-visible', !!message);
        }
    }

    function isValidPhone(val) {
        return /^01[3-9]\d{8}$/.test(val.replace(/\s/g, ''));
    }

    var form = document.getElementById('complaintForm');
    var success = document.getElementById('complaintSuccess');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var name = document.getElementById('compName');
            var phone = document.getElementById('compPhone');
            var type = document.getElementById('compType');
            var message = document.getElementById('compMessage');
            var valid = true;

            ['compName', 'compPhone', 'compType', 'compMessage'].forEach(function (id) {
                showError(id, '');
            });

            if (!name.value.trim()) {
                showError('compName', 'আপনার নাম লিখুন।');
                valid = false;
            }
            if (!isValidPhone(phone.value)) {
                showError('compPhone', 'সঠিক মোবাইল নম্বর দিন।');
                valid = false;
            }
            if (!type.value) {
                showError('compType', 'কমপ্লেইনের ধরন নির্বাচন করুন।');
                valid = false;
            }
            if (!message.value.trim()) {
                showError('compMessage', 'বিস্তারিত লিখুন।');
                valid = false;
            }

            if (!valid) return;

            var ticket = 'TKT-' + Date.now().toString().slice(-6);
            form.hidden = true;
            if (success) {
                success.hidden = false;
                document.getElementById('ticketId').textContent = ticket;
            }
        });
    }
})();
