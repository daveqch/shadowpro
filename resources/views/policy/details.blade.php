@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('policy.index') }}">{{ __('Policy List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Policy Detalis') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card card-success card-outline">
            <table class="table table-resposive">
                <tr>
                    <th>Title : </th>
                    <td>{{$policy->title}}</td>
                </tr>
                <tr>
                    <th>Description: </th>
                    <td>{!!$policy->description!!}</td>
                </tr>
                <tr>
                    <th>Status: </th>
                    <td>{!! $policy->enabled?"<span class='badge badge-success'>Active</span>":"<span class='badge badge-danger'>Inactive</span>" !!}</td>
                </tr>
            </table>    
        </div>
    </div>
</div>
@include('script.branch.create.js')
@endsection