<div id="attendance_employee_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{ __('Attendance Employee') }}
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" enctype="multipart/form-data" id="auto_category_form">
                    <input type="hidden" name="attendance_employee" id="attendance_employee">
                    <input type="hidden" name="employee_name_id" id="employee_name_id">

                    <div class="row">
                        <div class="col-md-3 ambitious-model-from-tc-pt">{{ __('Attendance') }}</div>
                        <div class="col-md-9">
                                <input onclick="myFunction()" id="attendance_status" class="form-control" name="attendance_status" type="checkbox" checked data-toggle="toggle" data-on="Present " data-off="Absent" data-onstyle="success" data-offstyle="danger" data-width="100">
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-3 ambitious-model-from-tc-pt present_block">{{ __('In Time') }}</div>
                        <div class="col-md-3 present_block">
                            <input autocomplete="off" class="form-control" id="in_time" name="in_time" placeholder="In Time">
                        </div>
                        <div class="col-md-3 ambitious-model-from-tc-pt present_block">{{ __('Out Time') }}</div>
                        <div class="col-md-3 present_block">
                            <input autocomplete="off" class="form-control" id="out_time" name="out_time" id="link"  placeholder="Out Time">
                        </div>
                    </div>

                    <div class="row absent_block">
                        <div class="col-md-3 ambitious-model-from-tc-pt">
                            {{ __('Leave') }}
                        </div>
                        <div class="col-md-3">
                            <input id="leave_status" class="form-control" name="leave_status" type="checkbox" checked data-toggle="toggle" data-on="Paid Leave" data-off="Unpaid Leave" data-onstyle="success" data-offstyle="danger" data-width="130">
                        </div>

                        <div class="col-md-2 ambitious-model-from-tc-pt paid_block">
                            {{ __('Type') }}
                        </div>

                        <div class="col-md-4 paid_block">
                            <select class="form-control" id="leave_type" name="leave_type">
                                <option value="">{{ __('Select Leave Type') }}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <input id="save_attendance" name="submit" type="submit" class="btn btn-outline btn-info btn-lg" value="{{ __('Save') }}"/>
                        <input type="button" class="btn btn-outline btn-warning btn-lg" value="{{ __('Cancel') }}" data-dismiss="modal"/>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>

<script>
    "use strict";
    $(document).ready(function(){
        $('#attendance_status').on('change', function(){
            var checkBox = document.getElementById("attendance_status");
            if (checkBox.checked == true){
                $(".absent_block").hide();
                $(".present_block").show();
            } else {
                $(".present_block").hide();
                $(".absent_block").show();
            }
        });

        $('#leave_status').on('change', function(){
            var checkBox = document.getElementById("leave_status");
            if (checkBox.checked == true){
                $(".paid_block").show();
            } else {
                $(".paid_block").hide();
            }
        });

        $(".absent_block, #datatableTable").hide();

        $('#in_time').clockpicker({
            placement: 'bottom',
            twelvehour: true,
            align: 'left',
            autoclose: true,
            'default': 'now'

        });

        $('#out_time').clockpicker({
            placement: 'bottom',
            twelvehour: true,
            align: 'left',
            autoclose: true,
            'default': 'now'
        });

        $(document.body).on('click','#save_attendance',function() {
            var itemName = "{{ $ApplicationSetting->item_name  }}";
            var checkBox = document.getElementById("attendance_status");
            var leave_status = document.getElementById("leave_status");
            var attendance_employee = $("#attendance_employee").val();
            var presentAbsent = "1";
            var unpainLeave = null;
            var in_time = $("#in_time").val();
            var out_time = $("#out_time").val();
            var leave_type = $("#leave_type").val();
            var employee_id = $("#employee_name_id").val();
            let requestDate = $("#date").val();
            if (checkBox.checked == true){
                var presentAbsent = "1";
                if(in_time=="") {
                    Swal.fire(
                        itemName,
                        '{{ __('Select Employee In Time') }}',
                        'warning'
                    );
                    return;
                }
            } else {
                var presentAbsent = "0";
                if (leave_status.checked == true) {
                    if(leave_type==undefined) {
                        Swal.fire(
                            itemName,
                            '{{ __('Select A Leave Type') }}',
                            'warning'
                        );
                        return;
                    }

                    if(leave_type=="") {
                        Swal.fire(
                            itemName,
                            '{{ __('Select A Leave Type') }}',
                            'warning'
                        );
                        return;
                    }
                } else {
                    var unpainLeave = "0";
                }

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
                    url: '{{ url('attendance/updateAttendanceAction') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    data: {
                        employee_id: employee_id,
                        requestDate: requestDate,
                        presentAbsent: presentAbsent,
                        unpainLeave: unpainLeave,
                        in_time: in_time,
                        out_time: out_time,
                        leave_type:leave_type
                    },

                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 0) {
                            Swal.fire(
                                itemName,
                                '{{ __('Oops Something Wrong') }}',
                                'warning'
                            ).then(function() {
                                $('#attendance_employee_modal').modal('hide');
                                $('#laravel_datatable').DataTable().ajax.reload();
                            });
                        } else if(response.status == 3) {

                            Swal.fire(
                                itemName,
                                '{{ __('Time Format Not Correct. Please check In Time & Out Time') }}',
                                'warning'
                            ).then(function() {
                                $('#attendance_employee_modal').modal('hide');
                                $('#laravel_datatable').DataTable().ajax.reload();
                            });
                        } else {
                            Swal.fire(
                                itemName,
                                '{{ __('Attendance Saved') }}',
                                'success'
                            ).then(function() {
                                $('#attendance_employee_modal').modal('hide');
                                location.reload();
                                //$('#laravel_datatable').DataTable().ajax.reload();
                            });
                        }
                    }
                });
            }
        });
    });
</script>
