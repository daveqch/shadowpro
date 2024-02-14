@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('loan.index') }}">{{ __('Loan List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Loan Detalis') }}</li>
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
                    <th>Name : </th>
                    <td>{{$loan->name}}</td>
                </tr>

                 <tr>
                    <th>Branch : </th>
                    <td>{{$loan->branch->branch_name}}</td>
                </tr>
                <tr>
                    <th>Employee : </th>
                    <td>{{$loan->employee->name}}</td>
                </tr>
                <tr>
                    <th>Loan Type : </th>
                    <td>{{$loan->receive_loan}}</td>
                </tr>
                <tr>
                    <th>Apply Date : </th>
                    <td>{{$loan->loan_date}}</td>
                </tr>
                <tr>
                    <th>Loan From Date  : </th>
                    <td>{{$loan->from_date}}</td>
                </tr>
                <tr>
                    <th>Loan Amount  : </th>
                    <td>{{$loan->loan_amount}}</td>
                </tr>
                <tr>
                    <th>Loan Installment  : </th>
                    <td>{{$loan->loan_installment}}</td>
                </tr>
                <tr>
                    <th>Loan Installment Amount  : </th>
                    <td>{{$loan->loan_installment_amount}}</td>
                </tr>
                <tr>
                    <th>Status : </th>
                    <td>{{$loan->enabled?'Active':'Inactive'}}</td>
                </tr>
                <tr>
                    <th>Description: </th>
                    <td>{!!$loan->note!!}</td>
                </tr>
            </table>    
        </div>
    </div>
</div>
@include('script.branch.create.js')
@endsection