<script>

    "use strict";

    $("#date").flatpickr({
        dateFormat: "Y-m-d",
        "locale": {
            "firstDayOfWeek": 1
        }
    });

    $(document).ready(function() {

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

    $(document).ready( function () {

        $('#notice_date').flatpickr({
            dateFormat: "Y-m-d",
            "locale": {
                "firstDayOfWeek": 1
            }
        });

         $('#termination_date').flatpickr({
            dateFormat: "Y-m-d",
            "locale": {
                "firstDayOfWeek": 1
            }
        });
      
        // Employee

        $('#branch_id').on('change', function(){
            var branchId = $(this).val();
            if(branchId){
                $.ajax({
                    url: '{{ url('/loan/selectedEmployeeData') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type:"GET",
                    data:{branchId:branchId},
                    success:function(html){
                        if(html){
                            $("#employee_id").empty();
                            $("#employee_id").append('<option value="">{{ __('Select Employee') }}</option>');
                            $.each(html,function(key,value){
                                $("#employee_id").append('<option value="'+key+'">'+value+'</option>');
                            });

                        }else{
                            $("#employee_id").empty();
                        }
                    }
                });

            } else{
                $('#employee_name').html('<option value="">{{ __('Select Branch First') }}</option>');
            }
        });
    });
</script>
