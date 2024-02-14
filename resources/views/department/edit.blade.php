@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('department.index') }}">{{ __('Department List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit Department') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('Edit Department') }}</h3>
            </div>
            <form class="form-material form-horizontal" action="{{ route('department.update', $department) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="department_name"><h4>@lang('Name') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('department_name') is-invalid @enderror" name="department_name" value="{{ $department->department_name }}" id="department_name" type="text" required placeholder="{{ __('Type Your Department Name') }}" >
                                    @error('department_name')
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
                                    <select name="enabled" id="enabled" required class="form-control">
                                        <option value="">@lang('Select Status')</option>
                                        <option {{$department->enabled === 1?'selected':''}} value="1">@lang('Enable')</option>
                                        <option {{$department->enabled === 0?'selected':''}} value="0">@lang('Disable')</option>
                                </select>
                                </div>
            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
                            <input type="submit" value="@lang('Submit')" id="submit" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('department.index') }}" class="btn btn-default float-right btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection