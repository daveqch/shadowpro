@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('employee.index') }}">{{ __('Employee List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Employee Detalis') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card card-success card-outline">
            <div class="card-body">
                <div class="row">
                <div class="col-xs-12 mx-auto">
                    <img src="{{ asset($employee->photo?$employee->photo:($employee->gender=='Male'?'img/profile/male.png':'img/profile/female.png'))}}" class="img-circle ambitious-employee-image-list center-block" id="view_photo">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                <hr>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Employee Id') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_e_id">{{$employee->e_id}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Full Name') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_name">{{$employee->name}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Email') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_email">{{$employee->email}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Number') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_number">{{$employee->phone}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Gender') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_gender">{{$employee->gender}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Birthday') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_birthday">{{$employee->birth_date}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Present Address') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_present_address">{{$employee->present_address}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Permanent Address') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_permanent_address">{{$employee->permanent_address}}</p></div>
                </div>
                </div>
                <div class="col-md-6">
                <hr>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Company') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_company">{{$employee->company->settings->where('company_id', session()->get('company_id'))->first()->value}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Branch') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_branch">{{$employee->branch->branch_name}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Department') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_department">{{$employee->department->department_name}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Designation') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_designation">{{$employee->designation->designation_name}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Joining Date') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_joining_date">{{$employee->joining_date}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Job Type') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_job_type">{{$employee->job_type}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Salary') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_sallary">{{$employee->salary->basic_salary}}</p></div>
                </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                <hr>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Emergency Contact Name') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_emergency_contact_name">{{$employee->emergency_contact_name}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Emergency Contact Number') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_emergency_contact_number">{{$employee->emergency_contact_number}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Emergency Contact Relation') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"> <p class="view-employee" id="view_emergency_contact_relation">{{$employee->emergency_contact_relation}}</p></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Emergency Contact Note') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"><p class="view-employee" id="view_emergency_contact_note">{{$employee->emergency_contact_note}}</p></div>
                </div>
                </div>
                <div class="col-md-6">
                <hr>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Resume') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"><a href="{{$employee->resume?asset($employee->resume):'javascript:void(0)'}}" target="_blank" id="view_resume">{{ __('Show Resume') }}</a></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Offer Letter') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"><a href="{{$employee->offer_letter?asset($employee->offer_letter):'javascript:void(0)'}}" target="_blank" id="view_offer_letter">{{ __('Show Offer Letter') }}</a></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Joining Letter') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"><a href="{{$employee->joining_letter?asset($employee->joining_letter):'javascript:void(0)'}}" target="_blank" id="view_joining_letter">{{ __('Show Joining Letter') }}</a></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Contract Agreement') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"><a href="{{$employee->contract_and_agreement?asset($employee->contract_and_agreement):'javascript:void(0)'}}" target="_blank" id="view_contract_and_agreement">{{ __('Show Contract Agreement') }}</a></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><p class="text-center">{{ __('Identity Proof') }}</p></div>
                    <div class="col-xs-1 ambitious-employee-list">:</div>
                    <div class="col-xs-8"><a href="{{$employee->identity_proof?asset($employee->identity_proof):'javascript:void(0)'}}" target="_blank" id="view_identity_proof">{{ __('View Identity Proof') }}</a></div>
                </div>
                </div>
            </div>
            <div class="row">
                @if ($employee->bank !== null)
                <div class="col-md-6">
                    <hr>
                    <div class="row">
                        <div class="col-xs-3"><p class="text-center">{{ __('Bank Name') }}</p></div>
                        <div class="col-xs-1 ambitious-employee-list">:</div>
                        <div class="col-xs-8"><p class="view-employee" id="view_bank_name">{{$employee->bank->bank_name}}</p></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"><p class="text-center">{{ __('Account Name') }}</p></div>
                        <div class="col-xs-1 ambitious-employee-list">:</div>
                        <div class="col-xs-8"><p class="view-employee" id="view_account_name">{{$employee->bank->account_name}}</p></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"><p class="text-center">{{ __('Branch Name') }}</p></div>
                        <div class="col-xs-1 ambitious-employee-list">:</div>
                        <div class="col-xs-8"><p class="view-employee" id="view_branch_name">{{$employee->bank->branch_name}}</p></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"><p class="text-center">{{ __('Account Number') }}</p></div>
                        <div class="col-xs-1 ambitious-employee-list">:</div>
                        <div class="col-xs-8"><p class="view-employee" id="view_account_number">{{$employee->bank->account_number}}</p></div>
                    </div>
                </div>
                @endif
                <div class="col-md-{{$employee->bank !== null?'6':'12'}}">
                <hr>
                    <div class="row">
                        <div class="col-xs-3"><p class="text-center">{{ __('Note') }}</p></div>
                        <div class="col-xs-1 ambitious-employee-list">:</div>
                        <div class="col-xs-8"><p class="view-employee" id="view_note">{{$employee->note}}</p></div>
                    </div>
                </div>
            </div>
            </div>
        </div>

    </div>
</div>
@endsection