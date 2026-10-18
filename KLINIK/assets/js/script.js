
// ==========================================
// RUMAH SAKIT AHMADI 5
// JavaScript tambahan aplikasi
// ==========================================

(function ($) {
    'use strict';

    $(function () {
        // Tooltip Bootstrap, jika digunakan oleh halaman.
        $('[data-toggle="tooltip"]').tooltip();

        // Konfirmasi untuk elemen yang menggunakan data-confirm.
        $(document).on('click', '[data-confirm]', function (event) {
            const pesan = $(this).attr('data-confirm');

            if (pesan && !window.confirm(pesan)) {
                event.preventDefault();
                event.stopPropagation();
            }
        });

        // Preview gambar untuk input file yang menggunakan data-image-preview.
        $(document).on('change', 'input[type="file"][data-image-preview]', function () {
            const input = this;
            const target = $(input).attr('data-image-preview');
            const gambar = document.querySelector(target);

            if (!gambar || !input.files || !input.files[0]) {
                return;
            }

            if (!input.files[0].type.startsWith('image/')) {
                alert('Silakan pilih file gambar.');
                input.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {
                gambar.src = event.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        });
    });
})(jQuery);
