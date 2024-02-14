@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('branch.index') }}">{{ __('Branch List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Branch Detalis') }}</li>
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
                    <th>Name: </th>
                    <td>{{$branch->branch_name}}</td>
                </tr>
                <tr>
                    <th>Location: </th>
                    <td>{{$branch->location->location_name}}</td>
                </tr>
                <tr>
                    <th>Status : </th>
                    <td>{!! $branch->enabled?"<span class='badge badge-success'>Active</span>":"<span class='badge badge-danger'>Inactive</span>" !!}</td>
                </tr>
                <tr>
                    <th>Note : </th>
                    <td>{!!$branch->note!!}</td>
                </tr>
            </table>    
        </div>
    </div>
</div>
@include('script.branch.create.js')
@endsection