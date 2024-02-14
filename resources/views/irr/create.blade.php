@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">{{ __('IRR') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Calculate IRR') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('New IRR Calculation') }}</h3>
            </div>
            <form id="itemQuickForm" class="form-material form-horizontal" action="{{ route('irr.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="intinal_investment"><h4>@lang('Intinal Investment') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <input class="form-control ambitious-form-loading @error('intinal_investment') is-invalid @enderror" name="intinal_investment" value="{{ old('intinal_investment', $request->intinal_investment) }}" id="intinal_investment" type="number" placeholder="{{ __('Type Your Intinal Investment Here') }}" required>
                                    @error('intinal_investment')
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
                                <label for="number_years"><h4>@lang('How Many Years') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <input class="form-control ambitious-form-loading @error('number_years') is-invalid @enderror" name="number_years" value="{{ old('number_years', $request->number_years) }}" id="number_years" type="number" placeholder="{{ __('Enter How Many Years') }}" required>
                                    @error('number_years')
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
                                <label for="first_year"><h4>@lang('First Year') <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <input class="form-control ambitious-form-loading @error('first_year') is-invalid @enderror" name="first_year" value="{{ old('first_year', $request->first_year) }}" id="first_year" type="number" placeholder="{{ __('Enter First Year') }}" required>
                                    @error('first_year')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="input_fields_wrap"></div>

                    @if(isset($request->amounts))
                    @php
                        $l = $request->first_year+$request->number_years;
                        $j = 0;
                    @endphp
                    @for($i = $request->first_year; $i < $l; $i++)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <h4>{{$i}}@lang(" Year Amounts") <b class="ambitious-crimson">*</b></h4>
                                    </label>
                                    <div class="input-group mb-3">
                                        <input class="form-control ambitious-form-loading @error('amounts') is-invalid @enderror" name="amounts[]" value="{{$request->amounts[$j]}}" type="number" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $j++;
                        @endphp
                    @endfor

                    
                        
                    @endif

                    @if(isset($irr))
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="intinal_investment"><h4>@lang('IRR is') </h4></label>
                                <div class="input-group mb-3">
                                    <input class="form-control ambitious-form-loading @error('intinal_investment') is-invalid @enderror" name="intinal_investment" value="{{ $irr }}" id="intinal_investment" type="number" readonly>
                                    @error('intinal_investment')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    
                </div>
                <div class="card-footer">
                    <input type="submit" value="@lang('Calculate')" class="btn btn-outline btn-info btn-lg"/>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    "use strict";

    $(document).ready(function() {

        $("#first_year").on("change", function(e) {

            let number_years = $('#number_years').val();
            
            let first_year = $('#first_year').val();

            var wrapper = $(".input_fields_wrap");

            let l = Number(number_years) + Number(first_year);

            if(number_years == ""){
                alert("Please Give How Many Years")
            }
            
            for (let i = first_year; i < l ; i++) {
                // console.log(i);
                $(wrapper).append('<div class="row"><div class="col-md-6"><div class="form-group"><label><h4>@lang("'+i+' Year Amounts") <b class="ambitious-crimson">*</b></h4></label><div class="input-group mb-3"><input class="form-control ambitious-form-loading @error('amounts') is-invalid @enderror" name="amounts[]" type="number" required></div></div></div></div>');
            }
        })
        
    });
</script>

@include('script.items.create.js')
@endsection