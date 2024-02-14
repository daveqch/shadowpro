<script>
    "use strict";
    $(document).ready(function(){
        $(document.body).on('click','#submit_attendance',function(){
            var itemName = "{{ $ApplicationSetting->item_name  }}";
            var company = $("#company_id").val();
            var branch = $("#branch").val();
            var employee = $("#employee_id").val();
            var attendance_date = $("#attendance_date").val();
            if(attendance_date=="") {
                Swal.fire(
                    itemName,
                    '{{ __('Select A Month') }}',
                    'warning'
                );
                return;
            }
            if(company==undefined) {
                Swal.fire(
                    itemName,
                    '{{ __('Create A Company First') }}',
                    'warning'
                );
                return;
            }
            /* if(company=="") {
                Swal.fire(
                    itemName,
                    '{{ __('Select Comany First') }}',
                    'warning'
                );
                return;
            } */
            if(branch==undefined) {
                Swal.fire(
                    itemName,
                    '{{ __('Create A Branch First') }}',
                    'warning'
                );
                return;
            }
            if(branch=="") {
                Swal.fire(
                    itemName,
                    '{{ __('Select Branch First') }}',
                    'warning'
                );
                return;
            }
            if(employee == "") {
                Swal.fire(
                    itemName,
                    '{{ __('Please Select An Employee') }}',
                    'warning'
                );
                return;
            }
        });

        $('#company_id').on('change', function(){
            var companyId = $(this).val();
            if(companyId){
                $.ajax({
                    url: '{{ url('salary/selectedBranchData') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type:"GET",
                    data:{companyId:companyId},
                    success:function(html){
                        if(html){
                            $("#branch").empty();
                            $("#branch").append('<option value="" >{{ __('Select Branch') }}</option>');
                            $.each(html,function(key,value){
                                $("#branch").append('<option value="'+key+'">'+value+'</option>');
                            });

                        }else{
                            $("#branch").empty();
                        }
                    }
                });

            } else{
                $('#branch').html('<option value="">{{ __('Select Company First') }}</option>');
            }
        });

        $('#branch').on('change', function () {
            var branchId = $(this).val();
            if(branchId){
                $.ajax({
                    url: '{{ url('salary/selectedEmployeeData') }}',
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
