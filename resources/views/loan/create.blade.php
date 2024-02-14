@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('loan.index') }}">{{ __('Loan List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Create Loan') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('New Loan') }}</h3>
            </div>
            <form  class="form-material form-horizontal" action="{{ route('loan.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name"><h4>@lang('Loan Name') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" type="text"  placeholder="{{ __('Type Your Loan Name') }}" required />
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="receive_loan"><h4>@lang('Receive Type') <b class="ambitious-crimson">*</b></h4></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-globe-europe"></i></i></span>
                                </div>
                                <select name="receive_loan" class="form-control" id="receive_loan" required >
                                    <option value="">{{ __('Select Receive Type') }}</option>
                                    <option {{old('receive_loan')=='salary'?'selected':''}} value="salary">{{ __('Salary') }}</option>
                                    <option {{old('receive_loan')=='cash'?'selected':''}} value="cash">{{ __('Cash') }}</option>
                                    <option {{old('receive_loan')=='bank cheque'?'selected':''}} value="bank cheque">{{ __('Bank Cheque') }}</option>
                                </select>
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
                                        <option {{old('branch_id')==$branch->id?'selected':''}} value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
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
                                    <option value="">{{ __('Select Branch First') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="loan_amount"><h4>@lang('Loan Amount')<b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('loan_amount') is-invalid @enderror" name="loan_amount" id="loan_amount" type="number" value="{{old('loan_amount')}}" placeholder="@lang('Type Your Loan Amount')" min="1" required>
                                    @error('loan_amount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interest"><h4>@lang('Interest') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('interest') is-invalid @enderror" name="interest" value="{{ old('interest') }}" id="interest" type="number" placeholder="{{ __('Type Your Loan Percentage') }}" required>
                                    @error('interest')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="loan_installment"><h4>@lang('Loan Installment') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('loan_installment') is-invalid @enderror" name="loan_installment"  id="loan_installment" type="text" placeholder="@lang('Type Your Loan Installment')" value="{{old('loan_installment')}}"  required min="1">
                                    @error('loan_installment')
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
                            <div class="form-group">
                                <label for="actual_loan_amount"><h4>@lang('Actual Loan Amount') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('actual_loan_amount') is-invalid @enderror" name="actual_loan_amount" id="actual_loan_amount" value="{{old('actual_loan_amount')}}" type="number" min="1" disabled required>
                                    @error('actual_loan_amount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="loan_installment_amount"><h4>@lang('Per Installment Amount') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('loan_installment_amount') is-invalid @enderror" name="loan_installment_amount" value="{{old('loan_installment_amount')}}"  id="loan_installment_amount" type="text" disabled required>
                                    @error('loan_installment_amount')
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
                            <div class="form-group">
                                <label for="loan_date"><h4>@lang('Apply Date') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('loan_date') is-invalid @enderror" name="loan_date" value="{{old('loan_date')}}"  id="loan_date" type="text" autocomplete="off" required>
                                    @error('loan_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="from_date"><h4>@lang('Loan From Date') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('from_date') is-invalid @enderror" name="from_date" value="{{old('from_date')}}" id="from_date" type="text" autocomplete="off" required>
                                    @error('from_date')
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
                                    <input type="hidden" name="note" value="{{old('note')}}" id="note">
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
                            <a href="{{ route('loan.index') }}" class="btn btn-default float-right btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('script.loan.create.js')
@endsection