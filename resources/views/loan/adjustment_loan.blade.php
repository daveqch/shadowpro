@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('loan-receive') }}">{{ __('Loan Receive') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Adjustment Loan') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('Adjustment Loan') }}</h3>
            </div>
            <form  class="form-material form-horizontal" action="{{ route('loan.receiveLoanAction') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <input type="hidden" value="{{$loan->id}}" name="loan_id" />
                    <input type="hidden" value="{{$loan->branch->id}}" name="branch_id" />
                    <input type="hidden" value="{{$loan->employee->id}}" name="employee_id" />
                    <input type="hidden" value="{{$loan->installment_amount}}" name="installment_amount" />
                    <input type="hidden" value="{{$loanAmount}}" name="loanAmount" />
                    <input type="hidden" value="{{$paySum}}" name="paySum" />
                    <input type="hidden" value="{{$dueLoanAmount}}" name="dueLoanAmount" />

                    <div class="row">
                        <div class="col-md-12">
                            <label for="adjustment_type"><h4>@lang('Adjustment Type') <b class="ambitious-crimson">*</b></h4></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-globe-europe"></i></i></span>
                                </div>
                                <select name="adjustment_type" class="form-control" id="adjustment_type" required>
                                    <option value="cheque">{{ __('Cheque') }}</option>
                                    <option  value="cash">{{ __('Cash') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>


                     <div id="cheque_block">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name"><h4>@lang('Bank Name') </h4></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror" name="bank_name" value="{{ old('bank_name') }}" id="bank_name" type="text"  placeholder="{{ __('Type Your Bank Name') }}" >
                                        @error('bank_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="branch_name"><h4>@lang('Branch Name') </h4></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror" name="branch_name" value="{{ old('branch_name') }}" id="branch_name" type="text"  placeholder="{{ __('Type Your Branch Name') }}" >
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_name"><h4>@lang('Account Name') </h4></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror" name="account_name" value="{{ old('account_name') }}" id="account_name" type="text"  placeholder="{{ __('Type Your Account Name') }}" >
                                        @error('account_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cheque_number"><h4>@lang('Cheque Number')</h4></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror" name="cheque_number" value="{{ old('cheque_number') }}" id="cheque_number" type="text"  placeholder="{{ __('Type Your Cheque Number') }}" >
                                        @error('cheque_number')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pay_amount"><h4>@lang('Pay Amount') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror" name="pay_amount" value="{{ old('pay_amount') }}" id="pay_amount" type="number"  placeholder="{{ __('Type Your Pay Amount') }}" required >
                                    @error('pay_amount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pay_date"><h4>@lang('Pay Date') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input class="pay_date form-control ambitious-form-loading @error('name') is-invalid @enderror" name="pay_date" value="{{ old('pay_date') }}" id="pay_date" type="text" required >
                                    @error('pay_date')
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
                            <a href="{{ route('loan-receive') }}" class="btn btn-default float-right btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('script.loan.create.js')
<script>
    $(document).ready(function(){
        $(".pay_date").flatpickr({
            dateFormat: "Y-m-d",
            "locale": {
                "firstDayOfWeek": 1
            }
        });

        $('#adjustment_type').on('change', function(){
            var adjustmentType = $(this).val();
            if (adjustmentType == 'cash'){
                $("#cheque_block").hide();
            } else {
                $("#cheque_block").show();
            }
        });
    });
    
</script>
@endsection