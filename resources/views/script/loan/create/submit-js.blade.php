<script>
    "use strict";

    $("#edit_loan_date").flatpickr({
        dateFormat: "Y-m-d",
        "locale": {
            "firstDayOfWeek": 1
        }
    });

    $("#edit_from_date").flatpickr({
        dateFormat: "Y-m-d",
        "locale": {
            "firstDayOfWeek": 1
        }
    });



    $(document).ready( function () {
        $(document.body).on('click','#save_loan_status',function() {
            var itemName = "{{ $ApplicationSetting->item_name  }}";
            var loan_id = $("#loan_id").val();
            var checkBox = document.getElementById("loan_status");

            if (checkBox.checked == true){
                var loan_status = "3";
            } else {
                var loan_status = "2";
            }

            var demo = "{{ $ApplicationSetting->is_demo }}";
            if (demo == 1) {
                var itemName = "{{ $ApplicationSetting->item_name  }}";
                Swal.fire(
                    itemName,
                    '{{ __('This Feature Is Disabled In Demo Version') }}',
                    'warning'
                );
                return;
            } else {
                $.ajax({
                    url: '{{ url('loan/loanStatus') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    data: {
                        loan_id: loan_id,
                        loan_status: loan_status
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 0) {
                            Swal.fire(
                                itemName,
                                '{{ __('Oops Something Wrong') }}',
                                'warning'
                            ).then(function() {
                                $('#save_loan_status').removeClass('disabled');
                                $('#loan_approv_modal').modal('hide');
                                $('#laravel_datatable').DataTable().ajax.reload();
                            });
                        }  else {
                            Swal.fire(
                                itemName,
                                '{{ __('Loan Status Saved') }}',
                                'success'
                            ).then(function() {
                                $('#save_loan_status').removeClass('disabled');
                                $('#loan_approv_modal').modal('hide');
                                $('#laravel_datatable').DataTable().ajax.reload();
                            });
                        }
                    }
                });
            }
        });

    });
</script>
