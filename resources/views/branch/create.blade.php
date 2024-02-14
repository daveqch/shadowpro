@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('branch.index') }}">{{ __('Branch List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Create Branch') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('New Branch') }}</h3>
            </div>
            <form  class="form-material form-horizontal" action="{{ route('branch.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="location_id"><h4>@lang('Location') <b class="ambitious-crimson">*</b></h4></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-globe-europe"></i></i></span>
                                </div>
                                <select class="form-control @error('location_id') is-invalid @enderror" name="location_id" id="location_id" required>
                                    <option value="">Select Location</option>
                                    @foreach($locations as $locations)
                                        <option {{old('location_id')==$locations->id?'selected':''}} value="{{$locations->id}}">{{$locations->location_name}}</option>
                                    @endforeach
                                </select>
                                 @error('location_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="branch_name"><h4>@lang('Name') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('branch_name') is-invalid @enderror" name="branch_name" value="{{ old('branch_name') }}" id="branch_name" type="text"  placeholder="{{ __('Type Your Branch Name') }}" required>
                                    @error('branch_name')
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
                                    <select name="enabled" id="enabled" class="form-control @error('enabled') is-invalid @enderror" required>
                                        <option value="">@lang('Select Status')</option>
                                        <option {{old('enabled')=='1'?'selected':''}} value="1">@lang('Enable')</option>
                                        <option {{old('enabled')=='0'?'selected':''}} value="0">@lang('Disable')</option>
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
                    <div class="row">
                        <div class="col-md-12 px-0">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>@lang('Note')</h4></label>
                                <div class="col-md-12">
                                    <div id="input_note" class="@error('note') is-invalid @enderror" style="min-height: 55px;">
                                    </div>
                                    <input type="hidden" name="note" value="{{ old('note') }}" id="note">
                                    @error('note')
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
                            <a href="{{ route('branch.index') }}" class="btn btn-default float-right btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('script.branch.create.js')
@endsection