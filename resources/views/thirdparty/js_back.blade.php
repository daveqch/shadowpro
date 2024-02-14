<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('plugins/alertifyjs/alertify.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('plugins/custom/js/quill.js') }}"></script>
<script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/swal.js') }}"></script>
 <script src="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
 <script src="{{ asset('js/bootstrap-toggle.min.js') }}"></script>

<script>
    function selectChange(val) {
        $('#myForm').submit();
    }

    $(document).on('click', '#doPrint', function(){
        var printContent = $('#print-area').html();
        $('body').html(printContent);
        window.print();
        location.reload();
    });

    $(document).on('click', '#doDownload', function(){
        var printContent = $('#print-area').html();
        var file = $('body').html(printContent).download();
        var filename = "invoice.pdf";
        download(filename, file);
    });
</script>
