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

    var DEMO_ORDERS = {
        'LCH-10245': { phone: '01712345678', product: 'অর্গানিক বাগানের লিচু × ১ কেজি', status: 'ডেলিভারির পথে' },
        'LCH-10240': { phone: '01812345678', product: 'প্রিমিয়াম মিষ্টি লিচু × ২ কেজি', status: 'ডেলিভারি সম্পন্ন' }
    };

    var form = document.getElementById('trackForm');
    var result = document.getElementById('trackResult');
    var empty = document.getElementById('trackEmpty');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var orderId = document.getElementById('orderId').value.trim().toUpperCase();
            var phone = document.getElementById('trackPhone').value.trim();
            var valid = true;

            showError('orderId', '');
            showError('trackPhone', '');

            if (!orderId) {
                showError('orderId', 'অর্ডার আইডি দিন।');
                valid = false;
            }
            if (!isValidPhone(phone)) {
                showError('trackPhone', 'সঠিক মোবাইল নম্বর দিন।');
                valid = false;
            }

            if (!valid) return;

            var order = DEMO_ORDERS[orderId];
            if (order && order.phone === phone.replace(/\s/g, '')) {
                if (result) result.hidden = false;
                if (empty) empty.hidden = true;
                document.getElementById('resultOrderId').textContent = orderId;
                document.getElementById('resultProduct').textContent = order.product;
                document.getElementById('resultStatus').textContent = order.status;

                var steps = document.querySelectorAll('#trackTimeline .track-step');
                var allDone = order.status === 'ডেলিভারি সম্পন্ন';
                steps.forEach(function (step, i) {
                    step.classList.remove('done', 'active');
                    if (allDone) {
                        step.classList.add('done');
                    } else if (i < 2) {
                        step.classList.add('done');
                    } else if (i === 2) {
                        step.classList.add('active');
                    }
                });
            } else {
                if (result) result.hidden = true;
                if (empty) empty.hidden = false;
            }
        });
    }
})();
