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
                <h3 class="card-title">@lang('Date Wise Attendance') </h3>
            </div>
            <div class="card-body">
        
                    <div class="card-body border mb-3">
                        <form action="" method="get" role="form" autocomplete="off">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="receive_loan"><h4>@lang('Branch Name') <b class="ambitious-crimson">*</b></h4></label>
                                    <select class="form-control" id="branch_id" name="branch_id" >
                                        <option value="">{{ __('Select Branch') }}</option>
                                        @foreach ($branches as $branch)
                                            <option {{request()->branch_id==$branch->id?'selected':''}} value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="date"><h4>@lang('Attendance Date ') <b class="ambitious-crimson">*</b></h4></label>
                                    <input id="date" class="form-control datepicker ambitious-background-white flatpickr-input active" name="attendance_date" value="{{request()->attendance_date}}" type="text" placeholder="Attendance Date" readonly="readonly">
                                </div>

                                <div class="col-sm-4 d-flex align-items-end">
                                    <div class="input-group ">
                                        <button type="submit" class="btn btn-info">Submit</button>
                                        @if(request()->branch_id)
                                            <a href="{{ route('attendance.dayWiseAttendance') }}" class="btn btn-info mx-1">Clear</a>
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
                             <th>@lang('Employee Id')</th>
                             <th>@lang('Employee Name')</th>
                             <th>@lang('Status')</th>
                             <th>@lang('In Time')</th>
                             <th>@lang('Out Time')</th>
                             <th>@lang('Working Time')</th>
                             <th>@lang('Leave Type')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->e_id}}</td>
                            <td>{{ $employee->name}}</td>
                            <td>{!! $employee->attendance->isNotEmpty()?($employee->attendance->first()->present == 1? '<span class="badge badge-success">Present</span>': '<span class="badge badge-danger">Absent</span>' ) : '<span class="badge badge-warning">No Attendace</span>'; !!}</td>
                            <td>{{ \App\Models\Attendance::inTime($employee->attendance) }}</td>
                            <td>{{ \App\Models\Attendance::outTime($employee->attendance) }}</td>
                            <td>{{ \App\Models\Attendance::workingTime($employee->attendance) }}</td>
                            <td>{{ \App\Models\Attendance::leaveType($employee->attendance) }}</td>
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
</script>
@endsection