@extends('layouts.layout')
@section('content')
<section class="content-header pl-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 pl-0">
                @can('leave-create')
                    <h3><a href="{{ route('leave.create') }}" class="btn btn-outline btn-info">+ {{ __('Add New Leave') }}</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Leave List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Leave') </h3>
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
                                        <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Type Your Leave Type')">
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
                                        <a href="{{ route('leave.index') }}" class="btn btn-secondary">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="laravel_datatable" class="table table-striped compact table-width text-center">
                    <thead>
                        <tr>
                             <th>@lang('ID')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Days')</th>
                            <th>@lang('Status')</th>
                            @canany(['leave-update', 'leave-delete'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaves as $leave)
                            <tr>
                            <td>{{ $leave->id }}</td>
                            <td>{{ $leave->type }}</td>
                            <td>{{ $leave->days }}</td>
                            <td>{!! $leave->enabled?"<span class='badge badge-success'>Active</span>":"<span class='badge badge-danger'>Inactive</span>" !!}</td>
                            @canany(['leave-update','leave-delete'])
                            <td>
                                @can('leave-update')
                                <a href="{{ route('leave.edit', $leave) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Edit"><span class="mdi mdi-pencil ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                @endcan
                                @can('leave-delete')
                                <a href="#" data-href="{{ route('leave.destroy', $leave) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="modal" data-target="#myModal" title="Delete"><span class="mdi mdi-delete ambitious-padding-btn"></span></a>
                                @endcan
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.items.index.js')
@endsection