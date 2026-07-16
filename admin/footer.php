<footer class="footer footer-static footer-light">

    <p class="clearfix mb-0">

        <span class="text-center float-md-start d-block d-md-inline-block mt-25">

            © <?= date('Y'); ?>

            <a
                class="ms-25"
                href="https://www.nfcmedya.com.tr"
                target="_blank"
                rel="noopener noreferrer"
            >
                NFC Medya
            </a>

        </span>

    </p>

</footer>

<script src="assets/dist/js/jquery-3.7.1.js"></script>
<script src="assets/dist/js/vendors.min.js"></script>

<script src="assets/dist/js/jquery.dataTables.min.js"></script>
<script src="assets/dist/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/dist/js/dataTables.responsive.min.js"></script>
<script src="assets/dist/js/responsive.bootstrap5.min.js"></script>
<script src="assets/dist/js/datatables.checkboxes.min.js"></script>
<script src="assets/dist/js/datatables.buttons.min.js"></script>
<script src="assets/dist/js/pdfmake.min.js"></script>
<script src="assets/dist/js/vfs_fonts.js"></script>
<script src="assets/dist/js/buttons.html5.min.js"></script>
<script src="assets/dist/js/buttons.print.min.js"></script>
<script src="assets/dist/js/dataTables.rowGroup.min.js"></script>

<script src="assets/dist/js/flatpickr.min.js"></script>
<script src="assets/dist/js/bs-stepper.min.js"></script>
<script src="assets/dist/js/jquery.validate.min.js"></script>
<script src="assets/dist/js/jquery.repeater.min.js"></script>

<script src="assets/dist/js/app-menu.js"></script>
<script src="assets/dist/js/app.js"></script>

<script src="assets/dist/js/form-wizard.js"></script>
<script src="assets/dist/js/form-repeater.js"></script>
<script src="assets/dist/js/table-datatables-basic.js"></script>

<script src="assets/dist/js/sweetalert2.all.min.js"></script>

<script src="assets/dist/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Feather Icons
    |--------------------------------------------------------------------------
    */

    if (typeof feather !== 'undefined') {

        feather.replace({
            width: 14,
            height: 14
        });

    }

    /*
    |--------------------------------------------------------------------------
    | Delete Modal
    |--------------------------------------------------------------------------
    */

    const deleteModal = document.getElementById('ilanDelete');

    if (deleteModal) {

        deleteModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            if (!button) return;

            const sliderId = button.getAttribute('data-id');

            const deleteInput = deleteModal.querySelector('#deleteId');

            if (deleteInput) {

                deleteInput.value = sliderId;

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | TinyMCE
    |--------------------------------------------------------------------------
    */

    if (typeof tinymce !== 'undefined') {

        tinymce.init({

            selector: 'textarea',

            height: 350,

            menubar: false,

            branding: false,

            promotion: false,

            plugins: [
                'advlist',
                'autolink',
                'lists',
                'link',
                'image',
                'charmap',
                'preview',
                'anchor',
                'searchreplace',
                'visualblocks',
                'code',
                'fullscreen',
                'insertdatetime',
                'media',
                'table',
                'code',
                'help',
                'wordcount'
            ],

            toolbar:
                'undo redo | ' +
                'blocks | bold italic underline | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | ' +
                'link image media | code fullscreen preview',

            content_style:
                'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',

            browser_spellcheck: true,

            contextmenu: false,

            verify_html: true,

            cleanup: true,

            convert_urls: false,

            relative_urls: false,

            remove_script_host: false,

            invalid_elements: 'script,iframe',

            extended_valid_elements: 'img[class|src|border=0|alt|title|width|height|style]',

        });

    }

});
</script>

</body>
</html>