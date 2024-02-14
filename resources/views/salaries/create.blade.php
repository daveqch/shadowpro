@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('salary.index') }}">{{ __('Salary List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Create Salary') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('New Salary') }}</h3>
            </div>
            <form id="salaryQuickForm" class="form-material form-horizontal" action="{{ route('salary.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="salary_name"><h4>@lang('Salary Name') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('salary_name') is-invalid @enderror" name="salary_name" value="{{ old('salary_name') }}" id="salary_name" type="text" placeholder="{{ __('Type Your Salary Name Here') }}" required>
                                    @error('salary_name')
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
                                <label for="basic_salary"><h4>@lang('Basic Salary') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-stripe-s"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('basic_salary') is-invalid @enderror" name="basic_salary" value="{{ old('basic_salary') }}" id="basic_salary" type="number" placeholder="{{ __('Type Your Basic Salary Here') }}" required>
                                    @error('basic_salary')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_salary"><h4>@lang('Total Salary')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('total_salary') is-invalid @enderror" name="total_salary" value="{{ old('total_salary',0.00) }}" id="total_salary" type="test" value="0.00" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="house_rent"><h4>@lang('House Rent')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('house_rent') is-invalid @enderror" name="house_rent" value="{{ old('house_rent') }}" id="house_rent" type="number" placeholder="{{ __('Type Your House Rent Here') }}">
                                    @error('house_rent')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="house_rent"><h4>@lang('Amount')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading" name="house_rent_amount" value="{{ old('house_rent_amount',0.00) }}" id="house_rent_amount" type="test" value="0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medical_allowance"><h4>@lang('Medical Allowance')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-briefcase-medical"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('medical_allowance') is-invalid @enderror" name="medical_allowance" value="{{ old('medical_allowance') }}" id="medical_allowance" type="number" placeholder="{{ __('Type Your Medical Allowance Here') }}">
                                    @error('medical_allowance')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medical_allowance_amount"><h4>@lang('Amount')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading" name="medical_allowance_amount" value="{{ old('medical_allowance_amount',0.00) }}" id="medical_allowance_amount" type="test" value="0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="conveyance_allowance"><h4>@lang('Conveyance Allowance')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bus"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('conveyance_allowance') is-invalid @enderror" name="conveyance_allowance" value="{{ old('conveyance_allowance') }}" id="conveyance_allowance" type="number" placeholder="{{ __('Type Your Conveyance Allowance Here') }}">
                                    @error('conveyance_allowance')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="conveyance_allowance_amount"><h4>@lang('Amount')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading" name="conveyance_allowance_amount" value="{{ old('conveyance_allowance_amount',0.00) }}" id="conveyance_allowance_amount" type="test" value="0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="food_allowance"><h4>@lang('Food Allowance')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-hamburger"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('food_allowance') is-invalid @enderror" name="food_allowance" value="{{ old('food_allowance') }}" id="food_allowance" type="number" placeholder="{{ __('Type Your Food Allowance Here') }}">
                                    @error('food_allowance')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="food_allowance_amount"><h4>@lang('Amount')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading" name="food_allowance_amount" value="{{ old('food_allowance_amount',0.00) }}" id="food_allowance_amount" type="test" value="0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="communication_allowance"><h4>@lang('Communication Allowance')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-tty"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('communication_allowance') is-invalid @enderror" name="communication_allowance" value="{{ old('communication_allowance') }}" id="communication_allowance" type="number" placeholder="{{ __('Type Your Communication Allowance Here') }}">
                                    @error('communication_allowance')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="communication_allowance_amount"><h4>@lang('Amount')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading" name="communication_allowance_amount" value="{{ old('communication_allowance_amount',0.00) }}" id="communication_allowance_amount" type="test" value="0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="other"><h4>@lang('Other')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-thermometer-full"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('other') is-invalid @enderror" name="other" value="{{ old('other') }}" id="other" type="number" placeholder="{{ __('Type Your Other Allowance Here') }}">
                                    @error('other')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="other_amount"><h4>@lang('Amount')</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading" name="other_amount" value="{{ old('other_amount',0.00) }}" id="other_amount" type="test" value="0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
                            <input type="submit" value="@lang('Submit')" id="submit" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('dashboard') }}" class="btn btn-default float-right btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    "use strict";
    $(document).ready(function(){
        $('#basic_salary').on('change', function(){
            var basic_salary = $('#basic_salary').val();
            var basic_salary_number = Number(basic_salary);

            var house_rent = $('#house_rent').val();
            var house_rent_number = Number(house_rent);
            var amount = ((house_rent_number*basic_salary_number) / 100).toFixed(2);
            $("#house_rent_amount").val(amount);

            var medical_allowance = $('#medical_allowance').val();
            var medical_allowance_number = Number(medical_allowance);
            var mamount = ((medical_allowance_number*basic_salary_number) / 100).toFixed(2);
            $("#medical_allowance_amount").val(mamount);

            var conveyance_allowance = $('#conveyance_allowance').val();
            var conveyance_allowance_number = Number(conveyance_allowance);
            var camount = ((conveyance_allowance_number*basic_salary_number) / 100).toFixed(2);
            $("#conveyance_allowance_amount").val(camount);

            var food_allowance = $('#food_allowance').val();
            var food_allowance_number = Number(food_allowance);
            var famount = ((food_allowance_number*basic_salary_number) / 100).toFixed(2);
            $("#food_allowance_amount").val(famount);

            var communication_allowance = $('#communication_allowance').val();
            var communication_allowance_number = Number(communication_allowance);
            var coamount = ((communication_allowance_number*basic_salary_number) / 100).toFixed(2);
            $("#communication_allowance_amount").val(coamount);

            var other = $('#other').val();
            var other_number = Number(other);
            var oamount = ((other_number*basic_salary_number) / 100).toFixed(2);
            $("#other_amount").val(oamount);
        });

        $('#house_rent').on('change', function(){
            var basic_salary = $('#basic_salary').val();
            var house_rent = $('#house_rent').val();
            var basic_salary_number = Number(basic_salary);
            var house_rent_number = Number(house_rent);
            var amount = ((house_rent_number*basic_salary_number) / 100).toFixed(2);
            $("#house_rent_amount").val(amount);
        });

        $('#medical_allowance').on('change', function(){
            var basic_salary = $('#basic_salary').val();
            var medical_allowance = $('#medical_allowance').val();
            var basic_salary_number = Number(basic_salary);
            var medical_allowance_number = Number(medical_allowance);
            var amount = ((medical_allowance_number*basic_salary_number) / 100).toFixed(2);
            $("#medical_allowance_amount").val(amount);
        });

        $('#conveyance_allowance').on('change', function(){
            var basic_salary = $('#basic_salary').val();
            var conveyance_allowance = $('#conveyance_allowance').val();
            var basic_salary_number = Number(basic_salary);
            var conveyance_allowance_number = Number(conveyance_allowance);
            var amount = ((conveyance_allowance_number*basic_salary_number) / 100).toFixed(2);
            $("#conveyance_allowance_amount").val(amount);
        });

        $('#food_allowance').on('change', function(){
            var basic_salary = $('#basic_salary').val();
            var food_allowance = $('#food_allowance').val();
            var basic_salary_number = Number(basic_salary);
            var food_allowance_number = Number(food_allowance);
            var amount = ((food_allowance_number*basic_salary_number) / 100).toFixed(2);
            $("#food_allowance_amount").val(amount);
        });

        $('#communication_allowance').on('change', function(){
            var basic_salary = $('#basic_salary').val();
            var communication_allowance = $('#communication_allowance').val();
            var basic_salary_number = Number(basic_salary);
            var communication_allowance_number = Number(communication_allowance);
            var amount = ((communication_allowance_number*basic_salary_number) / 100).toFixed(2);
            $("#communication_allowance_amount").val(amount);
        });

        $('#other').on('change', function(){
            var basic_salary = $('#basic_salary').val();
            var other = $('#other').val();
            var basic_salary_number = Number(basic_salary);
            var other_number = Number(other);
            var amount = ((other_number*basic_salary_number) / 100).toFixed(2);
            $("#other_amount").val(amount);
        });

        $('#basic_salary, #house_rent, #medical_allowance, #conveyance_allowance, #food_allowance, #communication_allowance, #other').on('change', function(){
            var basic_salary = Number($('#basic_salary').val());
            var house_rent_number = Number($('#house_rent_amount').val());
            var medical_allowance = Number($('#medical_allowance_amount').val());
            var conveyance_allowance = Number($('#conveyance_allowance_amount').val());
            var food_allowance = Number($('#food_allowance_amount').val());
            var communication_allowance = Number($('#communication_allowance_amount').val());
            var other = Number($('#other_amount').val());
            $("#total_salary").val(basic_salary+house_rent_number+medical_allowance+conveyance_allowance+food_allowance+communication_allowance+other);
        });


    });
</script>
@endsection