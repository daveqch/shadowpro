@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('advance-salary.index') }}">{{ __('Advance Salary List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Advance Salary Detalis') }}</li>
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
                    <th>Employee : </th>
                    <td>{{$advanceSalary->employee->name}}</td>
                </tr>

                <tr>
                    <th>Branch Name : </th>
                    <td>{{$advanceSalary->branch->branch_name}}</td>
                </tr>

                <tr>
                    <th>Reason : </th>
                    <td>{{$advanceSalary->reason}}</td>
                </tr>
                <tr>
                    <th>Date : </th>
                    <td>{{$advanceSalary->date}}</td>
                </tr>
             
                <tr>
                    <th>Amount  : </th>
                    <td>{{$advanceSalary->amount}}</td>
                </tr>
                <tr>
                    <th>Status : </th>
                    <td>{!! $advanceSalary->status==1?"<span class='badge badge-warning'>Pending</span>":( $advanceSalary->status==2?"<span class='badge badge-success'>Not Approve</span>":"<span class='badge badge-success'>Approve</span>") !!}</td>
                </tr>
                <tr>
                    <th>Description: </th>
                    <td>{!!$advanceSalary->remarks!!}</td>
                </tr>
            </table>    
        </div>
    </div>
</div>
@include('script.branch.create.js')
@endsection