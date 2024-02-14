@extends('layouts.layout')
@section('content')
<section class="content-header pl-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 pl-0">
                @can('policy-create')
                    <h3><a href="{{ route('policy.create') }}" class="btn btn-outline btn-info">+ {{ __('Add New Policy') }}</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Policy List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Policy') </h3>
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
                                        <label>@lang('Title')</label>
                                        <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Type Your Policy Title')">
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
                                        <a href="{{ route('policy.index') }}" class="btn btn-secondary">Clear</a>
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
                             <th>@lang('Title')</th>
                             <th>@lang('Description')</th>
                            <th>@lang('Status')</th>
                            @canany(['policy-update', 'policy-delete'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($policies as $policy)
                            <tr>
                            <td>{{ $policy->id }}</td>
                            <td>{{ $policy->title }}</td>
                            <td>{!! \Illuminate\Support\Str::limit( $policy->description, 80, $end='...') !!}</td>
                            <td>{!! $policy->enabled?"<span class='badge badge-success'>Active</span>":"<span class='badge badge-danger'>Inactive</span>" !!}</td>
                            @canany(['policy-update','policy-delete'])
                            <td>
                                @can('policy-read')
                                <a href="{{ route('policy.show', $policy) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Show"><span class="mdi mdi-eye ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                @endcan

                                @can('policy-update')
                                <a href="{{ route('policy.edit', $policy) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="tooltip" title="Edit"><span class="mdi mdi-pencil ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                @endcan
                                @can('policy-delete')
                                <a href="#" data-href="{{ route('policy.destroy', $policy) }}" class="btn btn-info btn-outline btn-circle btn-lg mb-2" data-toggle="modal" data-target="#myModal" title="Delete"><span class="mdi mdi-delete ambitious-padding-btn"></span></a>
                                @endcan
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $policies->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.items.index.js')
@endsection