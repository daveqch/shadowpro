<script>
    "use strict";
    $(document).ready(function() {
        var equill = new Quill('#edit_input_address', {
            theme: 'snow'
        });
        var address = $("#bank_address").val();
        equill.clipboard.dangerouslyPasteHTML(address);
        equill.root.blur();
        $('#edit_input_address').on('keyup', function() {
            var edit_input_address = equill.container.firstChild.innerHTML;
            $("#bank_address").val(edit_input_address);
        });
    });
</script>
