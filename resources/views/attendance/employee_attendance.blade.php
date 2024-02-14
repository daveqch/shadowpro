@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="{{ asset('js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/buttons.print.min.js') }}"></script>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Loan List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Employee Wise Attendance') </h3>
            </div>
            <div class="card-body">
        
                    <div class="card-body border mb-3">
                        <form action="" method="get" role="form" autocomplete="off">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="receive_loan"><h4>@lang('Branch Name') <b class="ambitious-crimson">*</b></h4></label>
                                    <select class="form-control" id="branch" name="branch_id" required>
                                        <option value="">{{ __('Select Branch') }}</option>
                                        @foreach ($branches as $branch)
                                            <option {{request()->branch_id==$branch->id?'selected':''}} value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="receive_loan"><h4>@lang('Employee Name') <b class="ambitious-crimson">*</b></h4></label>
                                    <select class="form-control" id="employee_id" name="employee_id" required>
                                        @if($employeeList->isEmpty())
                                            <option value="">{{ __('Select Branch First') }}</option>
                                        @endif
                                        @foreach ($employeeList as $item)
                                            <option {{$item->id==request()->employee_id?'selected':''}} value="{{$item->id}}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="receive_loan"><h4>@lang('Date Range') <b class="ambitious-crimson">*</b></h4></label>
                                    <input id="attendance_date" value="{{request()->attendance_date}}" class="form-control datepicker" name="attendance_date" type="text" placeholder="{{ __('Select Attendance Date') }}" class="ambitious-background-white">
                                </div>

                                <div class="col-sm-3 d-flex align-items-end">
                                    <div class="input-group">
                                        <button type="submit" class="btn btn-info">Submit</button>
                                         @if(request()->branch_id)
                                            <a href="{{ route('attendance.employeeWiseAttendance') }}" class="btn btn-info mx-1">Clear</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
               
                <table id="date_wise_attendance_datatable" class="table table-striped text-center compact table-width">
                    <thead>
                        <tr>
                             <th>@lang('ID')</th>
                             <th>@lang('Date')</th>
                             <th>@lang('Status')</th>
                             <th>@lang('In Time')</th>
                             <th>@lang('Out Time')</th>
                             <th>@lang('Working Time')</th>
                             <th>@lang('Leave Type')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendance as $attendance)
                            <tr>
                            <td>{{ $attendance->id }}</td>
                            <td>{{ date("d M, Y", strtotime($attendance->current_date)) }}</td>
                            <td>{!! $attendance?($attendance->present == 1? '<span class="badge badge-success">Present</span>': '<span class="badge badge-danger">Absent</span>' ) : '<span class="badge badge-warning">No Attendace</span>'; !!}</td>
                            <td>{{ $attendance->in_time }}</td>
                            <td>{{ $attendance->out_time }}</td>
                            <td>{{ $attendance->working_time }}</td>
                             <td>{{ \App\Models\Attendance::leaveType($attendance, 'employee_attendance') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- {{ $loans->links() }} --}}
            </div>
        </div>
    </div>
</div>
@include('script.items.index.js')
<script>
    
    $(document).ready(function(){
        $(".datepicker").flatpickr({
            dateFormat: "Y-m-d",
            mode: "range"
        });

        $('#date_wise_attendance_datatable').DataTable(
        {
            dom: 'Bfrtip',
            searching: false, 
            paging: false,
            /* lengthMenu: [
                [ 25, 50],
                [ '25 rows', '50 rows' ]
            ], */ 
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
        });

        $('#branch').on('change', function () {
            var branchId = $(this).val();
            if(branchId){
                $.ajax({
                    url: '{{ url('attendance/selectedEmployeeData') }}',
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
@endsection