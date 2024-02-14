@extends('layouts.layout')
@section('content')
<section class="content-header pl-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 pl-0">
                @can('tax-rate-create')
                    <h3><a href="{{ route('tax.create') }}" class="btn btn-outline btn-info">+ @lang('Add New')</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">@lang('Tax Rates')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@include('partials.errors')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Tax Rates') </h3>
                <div class="card-tools">
                    @can('tax-rate-export')
                        <a class="btn btn-primary" target="_blank" href="{{ route('tax.index') }}?export=1">
                            <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                        </a>
                    @endcan
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
                                        <label>@lang('Tax Name')</label>
                                        <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Tax Name')">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Tax Type')</label>
                                        <select class="form-control" name="type">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="inclusive" {{ old('type', request()->type) === 'inclusive' ? 'selected' : ''  }}>@lang('Inclusive')</option>
                                            <option value="exclusive" {{ old('type', request()->type) === 'exclusive' ? 'selected' : ''  }}>@lang('Exclusive')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('tax.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="laravel_datatable" class="table table-striped compact table-width">
                    <thead>
                        <tr>
                            <th>@lang('Tax Name')</th>
                            <th>@lang('Tax Rate(%)')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Status')</th>
                            @canany(['tax-rate-update', 'tax-rate-delete'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxes as $tax)
                            <tr>
                                <td>{{ $tax->name }}</td>
                                <td>{{ $tax->rate }}</td>
                                <td>
                                    @if($tax->type == 'inclusive')
                                        <span class="badge badge-pill badge-info">@lang('Inclusive')</span>
                                    @else
                                        <span class="badge badge-pill badge-warning">@lang('Exclusive')</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tax->enabled == '1')
                                        <span class="badge badge-pill badge-success">@lang('Enabled')</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">@lang('Disabled')</span>
                                    @endif
                                </td>
                                @canany(['tax-rate-update', 'tax-rate-delete'])
                                    <td>
                                        @can('tax-rate-update')
                                            <a href="{{ route('tax.edit', $tax) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><span class="mdi mdi-pencil ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                        @endcan
                                        @can('tax-rate-delete')
                                            <a href="#" data-href="{{ route('tax.destroy', $tax) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><span class="mdi mdi-delete ambitious-padding-btn"></span></a>
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $taxes->links() }}
            </div>
      </div>
    </div>
</div>

@include('layouts.delete_modal')
@include('script.tax.index.js')
@endsection

