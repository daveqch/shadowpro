@extends('layouts.layout')
@section('content')
<section class="content-header pl-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 pl-0">
                @can('advanceSalary-create')
                    <h3><a href="{{ route('advance-salary.create') }}" class="btn btn-outline btn-info">+ {{ __('Add Advance Salary') }}</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Advance Salary List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Advance Salary') </h3>
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
                              
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Reasion')</label>
                                        <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Type Advance Salary Reason')">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Status')</label>
                                        <select name="status" id="status" class="form-control">
                                        <option value="">--@lang('Select')--</option>
                                        <option {{request()->status == 1?'selected':''}} value="1">@lang('Pedding')</option>
                                        <option {{request()->status == 3?'selected':''}} value="3">@lang('Approve')</option>
                                        <option {{request()->status == 2?'selected':''}} value="2">@lang('Not Approve')</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('advance-salary.index') }}" class="btn btn-secondary">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="laravel_datatable" class="table table-striped text-center compact table-width">
                    <thead>
                        <tr>
                             <th>@lang('ID')</th>
                             <th>@lang('Employee Name')</th>
                             <th>@lang('Branch Name')</th>
                             <th>@lang('Reason')</th>
                             <th>@lang('Amount')</th>
                             <th>@lang('Date')</th>
                            <th>@lang('Status')</th>
                            @canany(['advanceSalary-update', 'advanceSalary-delete','advanceSalary-show', 'advanceSalary-approve'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($advanceSalaries as $advanceSalary)
                            <tr>
                            <td>{{ $advanceSalary->id }}</td>
                            <td>{{ $advanceSalary->employee->name}}</td>
                            <td>{{ $advanceSalary->branch->branch_name }}</td>
                            <td>{{ $advanceSalary->reason }}</td>
                            <td>{{ $advanceSalary->amount }}</td>
                            <td>{{ $advanceSalary->date }}</td>
                            <td>{!! $advanceSalary->status==1?"<span class='badge badge-warning'>Pending</span>":( $advanceSalary->status==2?"<span class='badge badge-success'>Not Approve</span>":"<span class='badge badge-success'>Approve</span>") !!}</td>
                            @canany(['advanceSalary-update', 'advanceSalary-delete','advanceSalary-show', 'advanceSalary-approve'])
                            <td>
                                 @if ($advanceSalary->status != 3)
                                 @can('advanceSalary-approve')
                                    <a href="#" data-id={{$advanceSalary->id}} data-status="{{$advanceSalary->status}}" class="btn btn-info btn-outline btn-circle btn-lg mb-2 advanceSalaryId" data-toggle="modal" data-target="#approveModal" title="Approve"><i class="fab fa-atlassian ambitious-padding-btn"></i></a>
                                    @endcan

                                    @can('advanceSalary-read')
                                    <a href="{{ route('advance-salary.show', $advanceSalary->id) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Show"><span class="mdi mdi-eye ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                    @endcan

                                    @can('advanceSalary-update')
                                    <a href="{{ route('advance-salary.edit', $advanceSalary->id) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Edit"><span class="mdi mdi-pencil ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                    @endcanany

                                    @can('advanceSalary-delete')
                                    <a href="#" data-href="{{ route('advance-salary.destroy', $advanceSalary) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="modal" data-target="#myModal" title="Delete"><span class="mdi mdi-delete ambitious-padding-btn"></span></a>
                                    @endcan
                                @else
                                    @can('advanceSalary-read')
                                    <a href="{{ route('advance-salary.show', $advanceSalary->id) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Show"><span class="mdi mdi-eye ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                    @endcan
                                    
                                @endif
                                
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $advanceSalaries->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('advance_salary.approve_modal')
@include('script.items.index.js')
@endsection