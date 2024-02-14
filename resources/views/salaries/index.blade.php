@extends('layouts.layout')
@section('content')
<section class="content-header pl-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 pl-0">
                @can('salary-create')
                    <h3><a href="{{ route('salary.create') }}" class="btn btn-outline btn-info">+ {{ __('Add New Salary') }}</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Salary List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Salaries') </h3>
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
                                        <label>@lang('Name')</label>
                                        <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Name')">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Status')</label>
                                        <select name="enabled" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="1" {{ old('enabled', request()->enabled) === '1' ? 'selected' : ''  }}>@lang('Enable')</option>
                                            <option value="0" {{ old('enabled', request()->enabled) === '0' ? 'selected' : ''  }}>@lang('Disable')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('salary.index') }}" class="btn btn-secondary">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-striped" id="laravel_datatable">
                    <thead>
                        <tr>
                            <th>@lang('ID')</th>
                            <th>{{ __('Salary Name') }}</th>
                            <th>{{ __('Basic Salary') }}</th>
                            <th>{{ __('House Rent') }}</th>
                            <th>{{ __('Medical') }}</th>
                            <th>{{ __('Conveyance') }}</th>
                            <th>{{ __('Food') }}</th>
                            <th>{{ __('Communication') }}</th>
                            <th>{{ __('Other') }}</th>
                            @canany(['salary-update','salary-delete'])
                            <th width="120" data-orderable="false" data-searchable="false">{{ __('Actions') }}</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salaries as $salary)
                        <tr>
                            <td>{{ $salary->id }}</td>
                            <td>{{ $salary->salary_name }}</td>
                            <td>{{ $salary->basic_salary }}</td>
                            <td>{{ $salary->house_rent_amount }}</td>
                            <td>{{ $salary->medical_allowance_amount }}</td>
                            <td>{{ $salary->conveyance_allowance_amount }}</td>
                            <td>{{ $salary->food_allowance_amount }}</td>
                            <td>{{ $salary->communication_allowance_amount }}</td>
                            <td>{{ $salary->other_amount }}</td>
                            @canany(['salary-update','salary-delete'])
                            <td>
                                @can('salary-update')
                                <a href="{{ route('salary.edit', $salary) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><span class="mdi mdi-pencil ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                @endcan
                                @can('salary-delete')
                                <a href="#" data-href="{{ route('salary.destroy', $salary) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><span class="mdi mdi-delete ambitious-padding-btn"></span></a>
                                @endcan
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $salaries->links() }}
            </div>
        </div>
    </div>
</div>
@include('script.items.index.js')
@endsection