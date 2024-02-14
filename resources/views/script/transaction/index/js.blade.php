<script>
    "use strict";
    $(document).ready( function () {

        $(".flatpickr").flatpickr({
            enableTime: false
        });

        $('#laravel_datatable').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
