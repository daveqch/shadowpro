<script>

    "use strict";

    $("#loan_date").flatpickr({
        dateFormat: "Y-m-d",
        "locale": {
            "firstDayOfWeek": 1
        }
    });

    $("#from_date").flatpickr({
        dateFormat: "Y-m-d",
        "locale": {
            "firstDayOfWeek": 1
        }
    });

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

    $(document).ready( function () {

        $('#interest').on('change', function(){
            var loan_amount=0,loan_amount_number=0,loan_installment=0,loan_installment_number=0,interest=0,interest_number=0,interest_amount=0,interest_amount_number=0,actual_loan_amount=0,amount=0;

             loan_amount = $('#loan_amount').val();
             loan_amount_number = Number(loan_amount);
             loan_installment  = $('#loan_installment').val();
             loan_installment_number = Number(loan_installment);
             interest = $('#interest').val();
             interest_number = Number(interest);
             interest_amount = ((loan_amount*interest_number) / 100).toFixed(2);
             interest_amount_number = Number(interest_amount);
             actual_loan_amount = loan_amount_number+interest_amount_number;
            $("#actual_loan_amount").val(actual_loan_amount);
            var amount = (((loan_amount_number+interest_amount_number)/loan_installment_number)).toFixed(2);
            if(!isNaN(amount) && isFinite(amount)) $("#loan_installment_amount").val(amount);
            
        });

       

        $('#loan_amount').on('change', function(){
            var loan_amount = $('#loan_amount').val();
            var loan_amount_number = Number(loan_amount);
            var loan_installment  = $('#loan_installment').val();
            var loan_installment_number = Number(loan_installment);
            var interest = $('#interest').val();
            var interest_number = Number(interest);
            var interest_amount = ((loan_amount*interest_number) / 100).toFixed(2);
            var interest_amount_number = Number(interest_amount);
            var actual_loan_amount = loan_amount_number+interest_amount_number;
            $("#actual_loan_amount").val(actual_loan_amount);
            var amount = (((loan_amount_number+interest_amount_number)/loan_installment_number)).toFixed(2);
            if(!isNaN(amount) && isFinite(amount)) $("#loan_installment_amount").val(amount);
        });

        $('#loan_installment').on('change', function(){
            var loan_amount = $('#loan_amount').val();
            var loan_amount_number = Number(loan_amount);
            var loan_installment  = $('#loan_installment').val();
            var loan_installment_number = Number(loan_installment);
            var interest = $('#interest').val();
            var interest_number = Number(interest);
            var interest_amount = ((loan_amount*interest_number) / 100).toFixed(2);
            var interest_amount_number = Number(interest_amount);
            var actual_loan_amount = loan_amount_number+interest_amount_number;
            var amount = (((loan_amount_number+interest_amount_number)/loan_installment_number)).toFixed(2);
            if(!isNaN(amount) && isFinite(amount))$("#loan_installment_amount").val(amount);
        });



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
