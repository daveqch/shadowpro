@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('advance-salary.index') }}">{{ __('
                    Advance Salary List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit Advance Salary') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('Edit Advance Salary') }}</h3>
            </div>
            <form class="form-material form-horizontal" action="{{ route('advance-salary.update', $advanceSalary) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="reason"><h4>@lang('Reason') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('reason') is-invalid @enderror" name="reason" value="{{ $advanceSalary->reason }}" id="reason" type="text"  placeholder="{{ __('Please Type Advance Salary Reason') }}" requred >
                                    @error('reason')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="receive_loan"><h4>@lang('Branch Name') <b class="ambitious-crimson">*</b></h4></label>
                            <div class="input-group mb-3 mt-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-code-branch"></i></i></span>
                                </div>
                                 <select class="form-control" id="branch_id" name="branch_id" required>
                                    <option value="">{{ __('Select Branch') }}</option>
                                    @foreach ($branches as $branch)
                                        <option {{$advanceSalary->branch_id==$branch->id?'selected':''}} value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="receive_loan"><h4>@lang('Employee Name') <b class="ambitious-crimson">*</b></h4></label>
                            <div class="input-group mb-3 mt-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></i></span>
                                </div>
                                 <select class="form-control" id="employee_id" name="employee_id" required>
                                    @foreach ($employees as $employee)
                                        <option {{$advanceSalary->employee_id==$employee->id?'selected':''}} value="{{$employee->id}}">{{$employee->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount"><h4>@lang('Amount') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('amount') is-invalid @enderror" value="{{$advanceSalary->amount}}" name="amount" id="amount" placeholder="{{ __('Type Advance Salary Amount') }}" type="number" min="1" required>
                                    @error('amount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>  
                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date"><h4>@lang('Date') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('date') is-invalid @enderror" value="{{$advanceSalary->date}}" name="date"  id="date" type="text" autocomplete="off" required>
                                    @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 px-0">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>@lang('Remarks')</h4></label>
                                <div class="col-md-12">
                                    <div id="input_note" class="@error('note') is-invalid @enderror" style="min-height: 55px;">
                                    </div>
                                    <input type="hidden" name="note" value="{{$advanceSalary->note}}" id="note">
                                    @error('note')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
                            <input type="submit" value="@lang('Submit')" id="submit" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('advance-salary.index') }}" class="btn btn-default float-right btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('script.loan.create.js')
@endsection