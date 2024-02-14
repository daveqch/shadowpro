@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('loan-receive') }}">{{ __('Loan Receive List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Loan Receive Detalis') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card card-success card-outline">
           <div class="modal-body">
                <h5 style="text-align: center" id="view_company_name">Comapny Name : ShadowPro</h5>
                <h6 style="text-align: center" id="view_branch_name">Branch Name : {{$loan->branch->branch_name}}</h6>
                <hr>
                <h6 id="view_employee_name">Employee Name : {{$loan->employee->name}}</h6>
                <h6 id="view_designation_name">Designation : {{$loan->employee->designation->designation_name}}</h6>
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th scope="col">Loan Name</th>
                            <th scope="col">Loan Amount</th>
                            <th scope="col">Receive Type</th>
                            <th scope="col">Installment</th>
                            <th scope="col">Installment Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="view_loan_name">{{$loan->name}}</td>
                            <td id="view_loan_amount">{{$loan->loan_installment * $loan->loan_installment_amount}}</td>
                            <td id="view_receive_type">{{$loan->receive_loan}}</td>
                            <td id="view_installment">{{$loan->loan_installment}}</td>
                            <td id="view_installment_amount">{{ $loan->loan_installment_amount}}</td>
                        </tr>
                    </tbody>
                </table>
                <div id="loan_pay_info"><table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Pay Amount</th>
                            <th scope="col">Description</th>
                        </tr>
                    </thead>
                        <tbody>
                            @php
                                $total_pay_amount = 0;
                                $due_amount = $loan->loan_installment * $loan->loan_installment_amount;
                            @endphp
                            @foreach ($loan->loansPay as $key => $item)
                            @php
                                $total_pay_amount = $total_pay_amount + $item->pay_amount;
                            @endphp
                            <tr>
                                <th scope="row">{{$key}}</th>
                                <td>{{$item->pay_date}}</td>
                                <td>{{$item->pay_amount}}</td>
                                <td>{!! $item->description !!}</td>
                            </tr>
                             @endforeach
                            
                            <tr>
                                <th colspan="2" style="text-align: center"> Total Paid Amount</th>
                                <th>{{ $total_pay_amount }}</th>
                                <th> Due : {{ (int) $due_amount - (int)$total_pay_amount}}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('script.branch.create.js')
@endsection