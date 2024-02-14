<script>
"use strict";
$(document).ready(function() {
    "use strict";

    var quill = new Quill('#input_note', {
        theme: 'snow'
    });

    var note = $("#note").val();
    quill.clipboard.dangerouslyPasteHTML(note);
    quill.root.blur();
    $('#input_note').on('keyup', function(){
        var input_note = quill.container.firstChild.innerHTML;
        $("#note").val(input_note);
    });
});
</script>
