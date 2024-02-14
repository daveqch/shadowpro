<script>
    "use strict";
        $("#date").flatpickr({
        dateFormat: "Y-m-d",
        "locale": {
            "firstDayOfWeek": 1
        }
    });
    
    $(document).ready( function () {
        $('.attendance_employee').on('click',function(){
            var itemName = "{{ $ApplicationSetting->item_name  }}";
            let employee_id = $(this).data('id')
            let requestDate = $("#date").val();

            $.ajax({
                url: '{{ url('attendance/getInTimeOutTime') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type:"GET",
                data:{employee_id:employee_id, requestDate:requestDate},
                dataType:"JSON",
                success:function(data){
                    console.log(data);
                    $("#in_time").val(data.in_time);
                    $("#out_time").val(data.out_time);
                }
            });


            $.ajax({
                url: '{{ url('attendance/selectedLeaveType') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type:"GET",
                success:function(html){
                    if(html){
                        $("#leave_type").empty();
                        $("#leave_type").append('<option value="" >{{ __('Select Leave Type') }}</option>');
                        $.each(html,function(key,value){
                            $("#leave_type").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#leave_type").empty();
                    }
                }
            });

            $("#attendance_employee").val(attendance_employee);
            $("#employee_name_id").val(employee_id);
            $('#attendance_employee_modal').modal('show');

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