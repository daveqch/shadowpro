@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('location.index') }}">{{ __('Location List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Create Location') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('New Location') }}</h3>
            </div>
            <form id="salaryQuickForm" class="form-material form-horizontal" action="{{ route('location.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location_name"><h4>@lang('Name') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('location_name') is-invalid @enderror" name="location_name" value="{{ old('location_name') }}" id="location_name" type="text" placeholder="{{ __('Type Your Location Name') }}" required>
                                    @error('location_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address"><h4>@lang('Address') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" id="address" type="text" required placeholder="{{ __('Type Your Location Address') }}" >
                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city"><h4>@lang('City') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" id="city" type="text" required placeholder="{{ __('Type Your Location City') }}" >
                                    @error('city')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state"><h4>@lang('State') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-usps"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('state') is-invalid @enderror" name="state" value="{{ old('state') }}" id="state" type="text" placeholder="{{ __('Type Your Location State') }}" required>
                                    @error('state')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="zip"><h4>@lang('Zip Code') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('zip') is-invalid @enderror" name="zip" value="{{ old('zip') }}" id="zip" type="number" required placeholder="{{ __('Type Your Location Zip Code') }}" >
                                    @error('zip')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                             <div class="form-group">
                                <label for="country"><h4>@lang('Country') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe-europe"></i></i></span>
                                    </div>
                                    <select class="form-control" name="country" id="country">
                                            @foreach($countryList as $key => $value)
                                                <option value="{{$value}}">{{$value}}</option>
                                            @endforeach
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
                            <a href="{{ route('location.index') }}" class="btn btn-default float-right btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection