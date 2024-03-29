@extends('layouts.layout')
@section('content')
<section class="content-header pl-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 pl-0">
                @can('currencies-create')
                    <h3><a href="{{ route('currency.create') }}" class="btn btn-outline btn-info">+ @lang('Add New Currency')</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">@lang('Currency List')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Currencies') }} </h3>
            <div class="card-tools">
                @can('currencies-export')
                    <a class="btn btn-primary" target="_blank" href="{{ route('currency.index') }}?export=1">
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
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Name')">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>@lang('Code')</label>
                                    <input type="text" name="code" class="form-control" value="{{ request()->code }}" placeholder="@lang('Code')">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>@lang('Symbol')</label>
                                    <input type="text" name="symbol" class="form-control" value="{{ request()->symbol }}" placeholder="@lang('Symbol')">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                @if(request()->isFilterActive)
                                    <a href="{{ route('currency.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <table id="laravel_datatable" class="table table-striped compact table-width">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Code')</th>
                        <th>@lang('Rate')</th>
                        <th class="text-center">@lang('Symbol')</th>
                        <th>@lang('Enabled')</th>
                        @canany(['currencies-update', 'currencies-delete'])
                            <th data-orderable="false">@lang('Actions')</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @foreach ($currencies as $currency)
                        <tr>
                            <td>{{ $currency->name }}</td>
                            <td>{{ $currency->code }}</td>
                            <td>{{ $currency->rate }}</td>
                            <td>
                                <p style="text-align : center; font-weight : bold; font-size : 18px">{{ $currency->symbol }}</p>
                            </td>
                            <td>
                                @if($currency->enabled == '1')
                                    <span class="badge badge-pill badge-success">@lang('Enabled')</span>
                                @else
                                    <span class="badge badge-pill badge-danger">@lang('Disabled')</span>
                                @endif
                            </td>
                            @canany(['currencies-update', 'currencies-delete'])
                                <td>
                                    @can('currencies-update')
                                        <a href="{{ route('currency.edit', $currency) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><span class="mdi mdi-pencil ambitious-padding-btn"></span></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('currencies-delete')
                                        <a href="#" data-href="{{ route('currency.destroy', $currency) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><span class="mdi mdi-delete ambitious-padding-btn"></span></a>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $currencies->links() }}
        </div>
      </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.currency.index.js')
@endsection
