@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('plugins/steps/js/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('plugins/steps/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
@endsection

@section('one_page_css')
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/steps/css/jquery.steps.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/steps/css/steps.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <link href="{{ asset('css/employee.css') }}" rel="stylesheet"> 
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('employee.index') }}">{{ __('Employee List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit Employee') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="panel panel-default">
            <div class="card">
            <div class="card-header">
                <h3>{{ __('Edit Employee') }}</h3>
            </div>
                <div class="card-body wizard-content  ">
                     <form  class="form-material form-horizontal validation-wizard wizard-circle employee_update_form" action="{{ route('employee.update', $employee->id) }}" id="employee_update_form" method="post" enctype="multipart/form-data">
                        @csrf
                         @method('PUT')
                         <input type="hidden" value="{{$employee->user->id}}" name="user_id">
                         <input type="hidden" value="{{$employee->bank?->id}}" name="bank_id">
                        <!-- Step 1 -->
                        <h6>{{ __('Personal Information') }}</h6>
                        <section>                                
                            <div class="row">
                                <div class="col-sm-6 form-group ">
                                    <label for="full_name"><h4>@lang('Full Name') <b class="ambitious-crimson">*</b></h4></label>
                                    <div class="input-group mb-3 ">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input id="full_name" placeholder="{{__('Type Your Full Name')}}" class="form-control @if($errors->has('full_name')) is-invalid @endif" name="full_name" type="text" value="{{ $employee->name }}" required>
                                        <div class="errordiv"></div>
                                        @error('full_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6 form-group ">
                                        <label for="phone"><h4>@lang('Phone') <b class="ambitious-crimson">*</b></h4></label>
                                        <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input id="phone" placeholder="{{__('Type Your Phone Number')}}" class="form-control @if($errors->has('phone')) is-invalid @endif" name="phone" type="text" value="{{ $employee->phone }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label for="birth_date"><h4>@lang('Birth Date') <b class="ambitious-crimson">*</b></h4></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                        </div>
                                        <input class="form-control datepicker ambitious-form-loading @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ $employee->birth_date }}" id="birth_date" type="text" required>
                                        @error('birth_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6 form-group">
                                    <label for="gender"><h4>@lang('Gender') <b class="ambitious-crimson">*</b></h4></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select id="gender" class="custom-select form-control required" name="gender" value="{{ old('gender') }}">
                                                <option value="">{{ __('Select Gender') }}</option>
                                                <option {{$employee->gender === 'Male'?'selected':''}} value="Male">{{ __('Male') }}</option>
                                                <option {{$employee->gender === 'Female'?'selected':''}} value="Female">{{ __('Female') }}</option>
                                        </select>
                                        @error('gender')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                            <label for="present_address"><h4>@lang('Present Address') <b class="ambitious-crimson">*</b></h4></label>
                                        <div class="input-group mb-3">
                                            <textarea id="present_address" class="form-control @error('present_address') is-invalid @enderror" name="present_address" rows="5" required>{{ $employee->present_address }}</textarea>
                                            @error('present_address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                            <label for="permanent_address"><h4>@lang('Permanent Address') <b class="ambitious-crimson">*</b></h4></label>
                                        <div class="input-group mb-3">
                                            <textarea id="permanent_address" class="form-control @error('permanent_address') is-invalid @enderror" name="permanent_address" rows="5" required>{{ $employee->permanent_address }}</textarea>
                                            @error('permanent_address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="photo"><h4>@lang('Photo') </h4></label>
                                        <div class="input-group">
                                            <input id="photo" class="dropify @error('photo') is-invalid @enderror" name="photo" value="{{ old('photo') }}" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="5120K"/>
                                            @error('photo')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="note"><h4>@lang('Note')</h4></label>
                                        <div class="input-group">
                                            <textarea id="note" rows="3" class="form-control @error('note') is-invalid @enderror" name="note" rows="5">{{ $employee->note }}</textarea>
                                            @error('note')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                            <!-- Step 1 end  -->
                        <!-- Step 2 -->
                        <h6>{{ __('Company Information') }}</h6>
                        <section>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="branch_id"><h4>@lang('Branch') <b class="ambitious-crimson">*</b></h4></label>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                            </div>
                                            <select id="branch_id" class="custom-select form-control required @error('branch') is-invalid @enderror" name="branch_id">
                                                <option value="">{{ __('Select Branch') }}</option>
                                                @foreach($branches as  $branch)
                                                    <option {{$employee->branch_id === $branch->id?'selected':''}} value="{{$branch->id}}">{{$branch->branch_name}}</option>
                                                @endforeach
                                            </select>
                                            @error('branch')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>    
                                        </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="department_id"><h4>@lang('Department') <b class="ambitious-crimson">*</b></h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            </div>
                                            <select id="department_id" class="custom-select form-control required @error('department_id') is-invalid @enderror" name="department_id">
                                                <option value="">{{ __('Select department') }}</option>
                                                @foreach($departments as  $department)
                                                    <option {{$employee->department_id === $department->id?'selected':'' }} value="{{$department->id}}">{{$department->department_name}}</option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>    
                                        </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="designation_id"><h4>@lang('Designation') <b class="ambitious-crimson">*</b></h4></label>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                            </div>
                                            <select id="designation_id" class="custom-select form-control required @error('designation_id') is-invalid @enderror" name="designation_id">
                                                <option value="">{{ __('Select Designation') }}</option>
                                                @foreach($designations as $designation)
                                                    <option {{$employee->designation_id === $designation->id?'selected':'' }} value="{{$designation->id}}">{{$designation->designation_name}}</option>
                                                @endforeach
                                            </select>
                                            @error('designation_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>    
                                        </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="e_id"><h4>@lang('Employee Id') <b class="ambitious-crimson">*</b></h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('e_id') is-invalid @enderror" name="e_id" value="{{ $employee->e_id }}" id="e_id" type="text"  placeholder="{{ __('Type Employee ID') }}" required >
                                            @error('e_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="job_type"><h4>@lang('Job Type') <b class="ambitious-crimson">*</b></h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                            </div>
                                            <select id="job_type" class="custom-select form-control required @error('job_type') is-invalid @enderror" name="job_type" required>
                                                <option {{$employee->job_type === 'Permanent'?'selected':''}} value="Permanent">{{ __('Permanent') }}</option>
                                                <option {{$employee->job_type === 'Part Time'?'selected':''}} value="Part Time">{{ __('Part Time') }}</option>
                                                <option {{$employee->job_type === 'Contract'?'selected':''}} value="Contract">{{ __('Contract') }}</option>
                                            </select>
                                            @error('job_type')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>    
                                        </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for=""><h4>@lang('Joining Date') <b class="ambitious-crimson">*</b></h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                            </div>
                                            <input class="form-control datepicker ambitious-form-loading @error('joining_date') is-invalid @enderror" name="joining_date" value="{{ $employee->joining_date }}" id="joining_date" type="text" required>
                                            @error('joining_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                    <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="salary_id"><h4>@lang('Salary') <b class="ambitious-crimson">*</b></h4></label>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <select id="salary_id" class="custom-select form-control required @error('salary_id') is-invalid @enderror" name="salary_id">
                                                <option value="">{{ __('Select Salary') }}</option>
                                                @foreach($salaries as $salary)
                                                    <option {{$employee->salary_id === $salary->id?'selected':''}} value="{{$salary->id}}">{{$salary->salary_name}}</option>
                                                @endforeach
                                            </select>
                                            @error('salary_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>    
                                        </div>
                                </div>
                            </div>
                        </section>
                        <!-- Step 2 end -->
                        
                        <!-- Step 3 -->
                        <h6>{{ __('Emergency Contact') }}</h6>
                        <section>
                            <div class="row">
                                <div class="col-sm-12">  
                                    <div class="form-group">
                                        <label for="emergency_contact_name"><h4>@lang('Contact Name') </h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('emergency_contact_name') is-invalid @enderror" name="emergency_contact_name" value="{{ $employee->emergency_contact_name }}" id="emergency_contact_name" type="text"  placeholder="{{ __('Type Contact Name') }}" >
                                            @error('emergency_contact_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="emergency_contact_number"><h4>@lang('Contact Number') </h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('emergency_contact_number') is-invalid @enderror" name="emergency_contact_number" value="{{ $employee->emergency_contact_number }}" id="emergency_contact_number" type="text"  placeholder="{{ __('Type Contact Number') }}" >
                                            @error('emergency_contact_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label for="emergency_contact_relation"><h4>@lang('Contact Relation') </h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('emergency_contact_relation') is-invalid @enderror" name="emergency_contact_relation" value="{{ $employee->emergency_contact_relation }}" id="emergency_contact_relation" type="text"  placeholder="{{ __('Type Contact Relation') }}" >
                                            @error('emergency_contact_relation')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                            <label for="emergency_contact_note"><h4>@lang('Contact Note') </h4></label>
                                        <div class="input-group mb-3">
                                            <textarea id="emergency_contact_note" class="form-control @error('emergency_contact_note') is-invalid @enderror" name="emergency_contact_note" rows="5">{{ $employee->emergency_contact_note }}</textarea>
                                            @error('emergency_contact_note')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </section>


                        <h6>{{ __('Files') }}</h6>
                        <section>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="resume"><h4>@lang('Resume') </h4></label>
                                        <div class="input-group">
                                            <input id="resume" class="dropify @error('resume') is-invalid @enderror" name="resume" value="{{ old('resume') }}" type="file" 
                                            data-allowed-file-extensions="pdf doc docx" data-max-file-size="250K"/>
                                            @error('resume')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_letter"><h4>@lang('Offer Letter') </h4></label>
                                        <div class="input-group">
                                            <input id="offer_letter" class="dropify @error('offer_letter') is-invalid @enderror" name="offer_letter" value="{{ old('offer_letter') }}" type="file" 
                                            data-allowed-file-extensions="pdf doc docx" data-max-file-size="250K"/>
                                            @error('offer_letter')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="joining_letter"><h4>@lang('Joining Letter')</h4></label>
                                        <div class="input-group">
                                            <input id="joining_letter" class="dropify @error('joining_letter') is-invalid @enderror" name="joining_letter" value="{{ $employee->joining_letter }}" type="file" 
                                            data-allowed-file-extensions="pdf doc docx" data-max-file-size="250K"/>
                                            @error('joining_letter')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="contract_and_agreement"><h4>@lang('Contract Agreement') </h4></label>
                                        <div class="input-group">
                                            <input id="contract_and_agreement" class="dropify @error('contract_and_agreement') is-invalid @enderror" name="contract_and_agreement" value="{{ old('contract_and_agreement') }}" type="file" 
                                            data-allowed-file-extensions="pdf doc docx" data-max-file-size="250K"/>
                                            @error('contract_and_agreement')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="identity_proof"><h4>@lang('Identity Proof') </h4></label>
                                        <div class="input-group">
                                            <input id="identity_proof" class="dropify @error('identity_proof') is-invalid @enderror" name="identity_proof" value="{{ old('identity_proof') }}" type="file" 
                                            data-allowed-file-extensions="pdf doc docx" data-max-file-size="250K"/>
                                            @error('identity_proof')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </section>

                        <!-- Step 5 -->
                        <h6>{{ __('Account Information') }}</h6>
                        <section>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email"><h4>@lang('Email') <b class="ambitious-crimson">*</b></h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('email') is-invalid @enderror" name="email" value="{{ $employee->email }}" id="email" type="email"  placeholder="{{ __('Type Email Address') }}" >
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="password"><h4>@lang('Password') </h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" id="password" type="password"  placeholder="{{ __('Type Password') }}" >
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="confirm_password"><h4>@lang('Confirm Password') </h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('confirm_password') is-invalid @enderror" name="confirm_password" value="{{ old('confirm_password') }}" id="confirm_password" type="password"  placeholder="{{ __('Type Confrom Password') }}" >
                                            @error('confirm_password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="role_id"><h4>@lang('Role') <b class="ambitious-crimson">*</b></h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                            </div>
                                            <select id="role_id" class="custom-select form-control required @error('role_id') is-invalid @enderror" name="role_id">
                                                <option value="">{{ __('Select role') }}</option>
                                                @foreach($roles as $role)
                                                    <option {{$employee->user->roles->first()->id === $role->id?'selected':''}} value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>    
                                        </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="bank_name"><h4>@lang('Bank Name')</h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-university"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('bank_name') is-invalid @enderror" name="bank_name" value="{{ $employee->bank?->bank_name }}" id="bank_name" type="text"  placeholder="{{ __('Type Bank name') }}" >
                                            @error('bank_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="branch_name"><h4>@lang('Branch Name') </h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('branch_name') is-invalid @enderror" name="branch_name" value="{{ $employee->bank?->branch_name }}" id="branch_name" type="text"  placeholder="{{ __('Type Branch Name') }}" >
                                            @error('branch_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="account_name"><h4>@lang('Account Name')</h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('account_name') is-invalid @enderror" name="account_name" value="{{  $employee->bank?->account_name }}" id="account_name" type="text"  placeholder="{{ __('Type Account Name') }}" >
                                            @error('account_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="account_number"><h4>@lang('Account Number')</h4></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                            </div>
                                            <input class="form-control ambitious-form-loading @error('account_number') is-invalid @enderror" name="account_number" value="{{ $employee->bank?->account_number }}" id="account_number" type="text" placeholder="{{ __('Enter Account NUmber') }}" >
                                            @error('account_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
        </div>
        </div>
    </div>
</div>

@include('script.employee.edit.js')
@endsection