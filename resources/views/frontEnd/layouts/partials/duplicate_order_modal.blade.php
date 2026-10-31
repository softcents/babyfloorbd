@if(session('show_order_limit_modal'))
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var whatsappNumber = "{{ preg_replace('/[^0-9]/', '', $contact->whatsapp ?? $contact->hotline ?? '8801700000000') }}";

    Swal.fire({
        title: '',
        html: `
            <div class="custom-modal-content">
                <div class="modal-header-custom">
                    <div class="header-left">
                        <i class="fas fa-exclamation-triangle header-icon"></i>
                        <span>Duplicate Order Alert</span>
                    </div>
                    <i class="fas fa-times close-icon" onclick="Swal.close()"></i>
                </div>
                <div class="modal-body-custom">
                    <p>
                        <b>সতর্কতা!</b> আপনি ইতিমধ্যে এই পণ্যটির জন্য অর্ডার দিয়েছেন। নির্দিষ্ট সময়ের মধ্যে একই পণ্যের পুনরায় অর্ডার দেওয়া অনুমোদিত নয়।
                        👉 আপনি যদি সত্যিই আবার অর্ডার করতে চান, তাহলে নিচে দেওয়া WhatsApp নম্বরে যোগাযোগ করুন:
                    </p>
                </div>
                <div class="modal-footer-custom">
                    <a href="https://wa.me/${whatsappNumber}?text=${encodeURIComponent('আমি একই পণ্য পুনরায় অর্ডার করতে চাই, অনুগ্রহ করে সাহায্য করুন।')}" target="_blank" class="btn-whatsapp-custom">
                        <i class="fab fa-whatsapp"></i> WhatsApp এ যোগাযোগ
                    </a>
                    <button onclick="Swal.close()" class="btn-close-custom">বন্ধ করুন</button>
                </div>
            </div>
        `,
        showConfirmButton: false,
        background: 'transparent',
        customClass: { popup: 'swal-no-padding' },
        allowOutsideClick: false
    });
});
</script>

<style>
.swal-no-padding { padding: 0 !important; background: none !important; box-shadow: none !important; overflow: visible !important; }
.custom-modal-content { background: white; border-radius: 8px; overflow: hidden; font-family: inherit; box-shadow: 0 10px 25px rgba(0,0,0,0.5); max-width: 500px; margin: 0 auto; }
.modal-header-custom { background-color: var(--primary); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; color: white; font-size: 18px; font-weight: bold; }
.header-left { display: flex; align-items: center; gap: 10px; }
.header-icon { color: #facc15; font-size: 20px; }
.close-icon { cursor: pointer; opacity: 0.8; font-size: 20px; }
.close-icon:hover { opacity: 1; }
.modal-body-custom { padding: 30px 25px; text-align: left; font-size: 15px; line-height: 1.6; color: #4b5563; }
.modal-footer-custom { padding: 0 25px 30px 25px; display: flex; justify-content: center; gap: 15px; }
.btn-whatsapp-custom { background-color: #10b981; color: white !important; text-decoration: none; padding: 10px 20px; border-radius: 50px; font-weight: bold; font-size: 13px; display: flex; align-items: center; gap: 8px; }
.btn-whatsapp-custom:hover { background-color: #059669; }
.btn-close-custom { background-color: #dc2626; color: white; padding: 10px 30px; border-radius: 50px; font-weight: bold; font-size: 14px; border: none; cursor: pointer; }
.btn-close-custom:hover { background-color: var(--primary-dark); }
@media (max-width: 450px) {
    .modal-footer-custom { flex-direction: column; }
    .btn-whatsapp-custom, .btn-close-custom { width: 100%; justify-content: center; }
}
</style>
@endif
