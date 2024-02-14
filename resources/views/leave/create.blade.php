@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('leave.index') }}">{{ __('leave List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Create Leave') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('New Leave') }}</h3>
            </div>
            <form  class="form-material form-horizontal" action="{{ route('leave.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type"><h4>@lang('Type') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('type') is-invalid @enderror" name="type" value="{{ old('type') }}" id="type" type="text" required placeholder="{{ __('Type Your Leave Type') }}" >
                                    @error('type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="days"><h4>@lang('Leave Days') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                    </div>
                                    <select name="days" class="form-control  @error('days') is-invalid @enderror" id="days" required>
                                        <option value="">{{ __('Select Days') }}</option>
                                            @for ($i = 1; $i <= 31; $i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                    </select>
                                    @error('days')
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
                                        <option value="1">@lang('Enable')</option>
                                        <option value="0">@lang('Disable')</option>
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
                            <a href="{{ route('leave.index') }}" class="btn btn-default float-right btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection