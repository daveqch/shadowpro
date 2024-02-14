@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('notice.index') }}">{{ __('Notice List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Notice Detalis') }}</li>
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
                    <td>{{$notice->title}}</td>
                </tr>
                <tr>
                    <th>Branch : </th>
                    <td>{{$branch->branch->branch_name?? 'All Branch' }}</td>
                </tr>
                <tr>
                    <th>Start Date : </th>
                    <td>{{$notice->start_date }}</td>
                </tr>
                <tr>
                    <th>End Date : </th>
                    <td>{{$notice->end_date }}</td>
                </tr>
                <tr>
                    <th>Status : </th>
                    <td>{!! $notice->enabled?"<span class='badge badge-success'>Active</span>":"<span class='badge badge-danger'>Inactive</span>" !!}</td>
                </tr>
                <tr>
                    <th>Description: </th>
                    <td>{!!$notice->description!!}</td>
                </tr>
            </table>    
        </div>
    </div>
</div>
@include('script.branch.create.js')
@endsection