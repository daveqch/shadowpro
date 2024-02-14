@extends('layouts.layout')
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
                <h3 class="card-title">@lang('Loan Receive') </h3>
            </div>
            <div class="card-body">
        
                    <div class="card-body border">
                        <form action="" method="get" role="form" autocomplete="off">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="receive_loan"><h4>@lang('Branch Name') <b class="ambitious-crimson">*</b></h4></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-code-branch"></i></i></span>
                                        </div>
                                        <select class="form-control" id="branch_id" name="branch_id" >
                                            <option value="">{{ __('Select Branch') }}</option>
                                            @foreach ($branches as $branch)
                                                <option {{request()->branch_id==$branch->id?'selected':''}} value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 d-flex align-items-end">
                                    <div class="input-group">
                                        <button type="submit" class="btn btn-info">Submit</button>
                                        @if(request()->branch_id)
                                            <a href="{{ route('loan-receive') }}" class="btn btn-info mx-1">Clear</a>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        </form>
                    </div>
               
                <table id="laravel_datatable" class="table table-striped text-center compact table-width">
                    <thead>
                        <tr>
                             <th>@lang('ID')</th>
                             <th>@lang('Employee Name')</th>
                             <th>@lang('Loan Name')</th>
                             <th>@lang('Loan Amount')</th>
                             <th>@lang('Due Amount')</th>
                             <th>@lang('Installment')</th>
                            <th>@lang('Status')</th>
                            <th data-orderable="false">@lang('Actions')</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $loan)
                            <tr>
                            <td>{{ $loan->id }}</td>
                            <td>{{ $loan->employee_name}} ({{ $loan->employee_id}})</td>
                            <td>{{ $loan->name }}</td>
                            <td>{{ $loan->loan_amount }}</td>
                            <td>{{ $loan->due_amount }}</td>
                            <td>{{ $loan->due_installment }} / {{$loan->loan_installment}}</td>
                            <td>{!! $loan->due_loan_amount > 0 ?"<span class='badge badge-info'>Processing</span>":"<span class='badge badge-success'>Paid</span>" !!}</td>
                            
                            <td>
                                @if ($loan->due_loan_amount > 0)
                                <a href="{{ route('loan-receive.adjustment', $loan->id) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Adjustment Loan"><i class="fas fa-dollar-sign ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                @endif

                                <a href="{{ route('loan.viewLoanAction', $loan->id) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Details Adjustment Loan"><i class="fas fa-eye  ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                
                            </td>
                           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.items.index.js')
@endsection