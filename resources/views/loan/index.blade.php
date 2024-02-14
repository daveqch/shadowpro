@extends('layouts.layout')
@section('content')
<section class="content-header pl-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 pl-0">
                @can('loan-create')
                    <h3><a href="{{ route('loan.create') }}" class="btn btn-outline btn-info">+ {{ __('Add New Loan') }}</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
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
                <h3 class="card-title">@lang('Loan') </h3>
                <div class="card-tools">
                    <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
                </div>
            </div>
            <div class="card-body">
                <div id="filter" class="collapse @if(request()->isFilterActive) show @endif">
                    <div class="card-body border">
                        <form action="" method="get" role="form" autocomplete="off">
                            <input type="hidden" name="isFilterActive" value="true">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="receive_loan">@lang('Branch Name')</label>
                                    <select class="form-control" id="branch_id" name="branch_id" required>
                                        <option value="">{{ __('Select Branch') }}</option>
                                        @foreach ($branches as $branch)
                                            <option {{request()->branch_id == $branch->id?'selected':''}} value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Name')</label>
                                        <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Type Your Loan Name')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Status')</label>
                                        <select name="loan_status" id="loan_status" class="form-control">
                                        <option value="">--@lang('Select')--</option>
                                        <option {{request()->loan_status == 1?'selected':''}} value="1">@lang('Pedding')</option>
                                        <option {{request()->loan_status == 3?'selected':''}} value="3">@lang('Approve')</option>
                                        <option {{request()->loan_status == 2?'selected':''}} value="2">@lang('Not Approve')</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('loan.index') }}" class="btn btn-secondary">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="laravel_datatable" class="table table-striped text-center compact table-width">
                    <thead>
                        <tr>
                             <th>@lang('Employee Name')</th>
                             <th>@lang('Loan Name')</th>
                             <th>@lang('Receive Type')</th>
                             <th>@lang('Loan Amount')</th>
                             <th>@lang('Installment')</th>
                            <th>@lang('Status')</th>
                            @canany(['loan-update', 'loan-delete'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $loan)
                            <tr>
                            <td>{{ $loan->employee->name}}({{$loan->employee->e_id}})</td>
                            <td>{{ $loan->name }}</td>
                            <td>{{ $loan->receive_loan }}</td>
                            <td>{{ $loan->loan_amount }}</td>
                            <td>{{ $loan->loan_installment }}</td>
                            <td>{!! $loan->loan_status==1?"<span class='badge badge-warning'>Pending</span>":( $loan->loan_status==2?"<span class='badge badge-success'>Not Approve</span>":"<span class='badge badge-success'>Approve</span>") !!}</td>
                            @canany(['loan-update','loan-delete','loan-show','loan-approve'])
                            <td class="text-left">
                                 @if ($loan->loan_status != 3)
                                    @can('loan-approve')
                                    <a href="#" data-id={{$loan->id}} data-status={{$loan->loan_status}} class="btn btn-info btn-outline btn-circle btn-lg mb-2 loanId" data-toggle="modal" data-target="#approveModal" title="Approve"><i class="fab fa-atlassian ambitious-padding-btn"></i></a>
                                    @endcan

                                    @can('loan-read')
                                    <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Show"><span class="mdi mdi-eye ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                    @endcan

                                     @can('loan-update')
                                    <a href="{{ route('loan.edit', $loan->id) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Edit"><span class="mdi mdi-pencil ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                    @endcan

                                    @can('loan-delete')
                                    <a href="#" data-href="{{ route('loan.destroy', $loan) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="modal" data-target="#myModal" title="Delete"><span class="mdi mdi-delete ambitious-padding-btn"></span></a>
                                    @endcan
                                @else
                                    @can('loan-read')
                                    <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Show"><span class="mdi mdi-eye ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                    @endcan
                                    
                                @endif
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $loans->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('loan.approve_modal')
@include('script.items.index.js')
@endsection