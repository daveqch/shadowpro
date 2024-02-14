@extends('layouts.layout')
@section('one_page_js')
    <!-- Bootstrap Multiselect -->
    <script src="{{ asset('plugins/air-datepicker/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/air-datepicker/js/i18n/datepicker.en.js') }}"></script>
@endsection

@section('one_page_css')
    <link href="{{ asset('plugins/air-datepicker/css/datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ __('Branch Wise Salary') }}
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('Branch Wise Salary') }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('salary.actionbranchSalary') }}" method="post">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $userId }}">
                    <input type="hidden" name="company_id" id="company_id" value="{{ Session::get('companyInfo') }}">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="branch">{{ __('Branch Name') }} <b class="ambitious-crimson">*</b></label>
                            <select name="branch" class="form-control" id="branch" required>
                                <option value="">{{ __('Select Branch') }}</option>
                                @foreach ($branchs as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="attendance_date">{{ __('Select Month') }} <b class="ambitious-crimson">*</b></label>
                            <input id="attendance_date" class="form-control datepicker-here ambitious-background-white" data-language='en' data-min-view="months" data-view="months" data-date-format="MM yyyy" name="attendance_date" type="text" placeholder="{{ __('Select Attendance Date') }}" autocomplete="off" required>
                        </div>

                        <div class="col-md-3"></div>

                        <div class="col-md-3">
                            <label for="attendance_date"><b class="ambitious-white">*</b></label>
                            <input target="_blank" id="submit_attendance" value="{{ __('Submit') }}" type="submit" class="form-control btn btn-outline btn-info"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('script.salary_branch.js')
@endsection
