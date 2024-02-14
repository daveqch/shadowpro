@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('designation.index') }}">{{ __('Designation List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit Designation') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('Edit Designation') }}</h3>
            </div>
            <form class="form-material form-horizontal" action="{{ route('designation.update', $designation) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="designation_name"><h4>@lang('Name') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('designation_name') is-invalid @enderror" name="designation_name" value="{{ $designation->designation_name }}" id="designation_name" type="text"  placeholder="{{ __('Type Your Designation Name') }}" required >
                                    @error('designation_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="enabled"><h4>@lang('Status') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-thermometer-three-quarters"></i></span>
                                    </div>
                                    <select name="enabled" id="enabled" class="form-control  @error('enabled') is-invalid @enderror" required>
                                        <option value="">@lang('Select Status')</option>
                                        <option {{$designation->enabled === 1?'selected':''}} value="1">@lang('Enable')</option>
                                        <option {{$designation->enabled === 0?'selected':''}} value="0">@lang('Disable')</option>
                                </select>
                                @error('enabled')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
                            <input type="submit" value="@lang('Submit')" id="submit" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('designation.index') }}" class="btn btn-default float-right btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection