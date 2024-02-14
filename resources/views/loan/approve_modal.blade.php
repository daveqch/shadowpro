<div id="approveModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{ __('Loan Status') }}
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" enctype="multipart/form-data" id="auto_category_form">
                     <input type="hidden" name="loan_id" id="loan_id">
                     <select name="loan_status" id="loan_status" class="form-control loan_status" required>
                        <option value="">--@lang('Select')--</option>
                        <option value="3">@lang('Approve')</option>
                        <option value="2">@lang('Not Approve')</option>
                    </select>
                    <div class="text-danger" id="message"></div>
                </form>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center clearfix">
                    <input id="save_loan_status" name="submit" type="submit" class="btn btn-outline btn-info btn-lg" value="{{ __('Save') }}"/>
                    <input type="button" class="btn btn-outline btn-warning btn-lg" value="{{ __('Cancel') }}" data-dismiss="modal"/>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>

<script>
    "use strict";

    $(document).ready(function () {
        $("#approveModal").on("show.bs.modal", function (e) {
             $("#message").text('');
            var loanId = $(e.relatedTarget).data("id");
            var status = $(e.relatedTarget).data("status");
            console.log(status);
            $("#loan_id").val(loanId);
            if(status == 2 || status ==3)$(".loan_status").val(status);
        });
    });

    $(document).ready( function () {
        $(document.body).on('click','#save_loan_status',function() {
            var itemName = "{{ $ApplicationSetting->item_name  }}";
            var loan_id = $("#loan_id").val();
            var loan_status = $(".loan_status").val();
            if(loan_status.length <= 0){
                $("#message").text('The loan status field is required.');
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
                                $("#message").text('');
                                $('#approveModal').modal('hide');
                                location.reload();
                            });
                        }  else {
                            Swal.fire(
                                itemName,
                                '{{ __('Loan Status Saved') }}',
                                'success'
                            ).then(function() {
                                $("#message").text('');
                                $('#approveModal').modal('hide');
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    });
</script>
