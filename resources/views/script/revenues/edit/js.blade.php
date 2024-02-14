<script>
    "use strict";
        $(document).ready(function() {
            var quill = new Quill('#input_description', {
            theme: 'snow'
        });

        var description = $("#description").val();
        quill.clipboard.dangerouslyPasteHTML(description);
        quill.root.blur();
        $('#input_description').on('keyup', function(){
            var input_description = quill.container.firstChild.innerHTML;
            $("#description").val(input_description);
        });
    });
</script>
