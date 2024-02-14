@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('company.index') }}">@lang('Company List')</a></li>
                    <li class="breadcrumb-item active">@lang('Edit Company')</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>@lang('Edit Company')</h3>
            </div>
            <form class="form-material form-horizontal" action="{{ route('company.update', $company) }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-md-12">
                                <h4>@lang('Company Name') <b class="ambitious-crimson">*</b></h4>
                            </label>
                            <div class="col-md-12">
                                <input class="form-control ambitious-form-loading @error('company_name') is-invalid @enderror" name="company_name" id="company_name" type="text" value="{{ old('company_name',$company->company_name) }}" placeholder="@lang('Enter Name')" required>
                                @error('company_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-md-12">
                                <h4>@lang('Company Email') <b class="ambitious-crimson">*</b></h4>
                            </label>
                            <div class="col-md-12">
                                <input class="form-control ambitious-form-loading @error('company_email') is-invalid @enderror" name="company_email" id="company_email" type="email" value="{{ old('company_email', $company->company_email) }}" placeholder="@lang('Enter Company Email')" required>
                                @error('company_email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-12">
                                <h4>@lang('Domain') <b class="ambitious-crimson">*</b></h4>
                            </label>
                            <div class="col-md-12">
                                <input class="form-control ambitious-form-loading @error('domain') is-invalid @enderror" name="domain" id="domain" type="text" value="{{ old('domain', $company->domain) }}" placeholder="@lang('Enter Domain')" required>
                                @error('domain')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-md-12"><h4>@lang('Photo') </h4></label>
                            <div class="col-md-12">
                                <input id="photo" class="dropify @error('photo') is-invalid @enderror" name="photo" value="{{ old('photo') }}" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="1024K"/>
                                <small id="name" class="form-text text-muted">@lang('Leave Blank For Remain Unchanged')
                                </small>
                                <p>@lang('Max Size: 1000kb, Allowed Format: png, jpg, jpeg')</p>
                                @error('photo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-12"><h4>@lang('Address')</h4></label>
                            <div class="col-md-12">
                                <div id="edit_input_address" class="form-control @error('address') is-invalid @enderror" style="min-height: 55px;">
                                </div>
                                <input type="hidden" name="address" id="address" value="{{ old('address',$company->company_address) }}">
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-md-12"><h4>@lang('Enabled') <b class="ambitious-crimson">*</b></h4></label>
                            <div class="col-md-12">
                                <select class="form-control ambitious-form-loading" name="enabled" id="enabled">
                                    <option value="1" {{ old('enabled', $company->enabled) == 1 ? 'selected' : '' }}>@lang('Yes')</option>
                                    <option value="0" {{ old('enabled', $company->enabled) == 0 ? 'selected' : '' }}>@lang('No')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" value="@lang('Submit')" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-secondary btn-lg float-right">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('script.company.edit.js')
@endsection
