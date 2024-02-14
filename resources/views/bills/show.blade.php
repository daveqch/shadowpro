
@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('bill.index') }}">@lang('Bills List')</a></li>
                    <li class="breadcrumb-item active">@lang('Show Bill')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<img class="d-none" src="/pre-loader/loading.gif" alt="hidden-img">
<div class="progress-bar" id="progress-bar"></div>
<div class="row">
    <div class="col-12">
        @php
            switch ($bill->bill_status_code) {
                case 'paid':
                    $badge = 'success';
                    break;
                case 'delete':
                    $badge = 'danger';
                    break;
                case 'partial':
                case 'sent':
                    $badge = 'warning';
                    break;
                default:
                    $badge = 'primary';
                    break;
            }
        @endphp
        <div id="print-area" class="bill p-3 mb-3 card card-{{$badge}} card-outline">
            <div class="row">
                <div class="ribbon-wrapper ribbon-lg">
                    <div class="ribbon bg-{{$badge}}">{{Str::ucfirst($bill->bill_status_code) }}</div>
                </div>
                <div class="col-12 ">
                    <h4><i class="fas fa-globe"></i> {{ $company->company_name ?? '' }}</h4>
                </div>
            </div>
            <div class="row bill-info">
                <div class="col-sm-4 bill-col">
                    From
                    <address>
                        <strong>{{ $salesMan->name}}</strong><br>
                        @if ($company->company_address)
                        {{ strip_tags($company->company_address) }}<br>
                        @endif
                        @if ($company->company_phone)
                        Phone: {{ $company->company_phone }}<br>
                        @endif
                        @if ($company->company_phone)
                        Email: {{ $company->company_email }}
                        @endif
                    </address>
                </div>
                <div class="col-sm-4 bill-col">
                    To
                    <address>
                        <strong>{{ $bill->vendor_name}}</strong><br>
                        @if ($bill->vendor_address)
                        {{ strip_tags($bill->vendor_address) }}<br>
                        @endif
                        @if ($bill->vendor_phone)
                        Phone: {{ $bill->vendor_phone }}<br>
                        @endif
                        @if ($bill->vendor_email)
                        Email: {{ $bill->vendor_email }}
                        @endif
                    </address>
                </div>
                <div class="col-sm-4 bill-col">
                    <b>Bill #{{$bill->bill_number}}</b><br>
                    <br>
                    <b>Order Number:</b> {{$bill->order_number}}<br>
                    <b>Bill Date:</b> {{ date($company->date_format, strtotime($bill->billed_at)) }}<br>
                    <b>Payment Due:</b> {{ date($company->date_format, strtotime($bill->due_at)) }}
                </div>
            </div>
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bill->items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>@money($item->price, $bill->currency_code, true)</td>
                                <td>@money($item->total, $bill->currency_code, true)</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                </div>
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table">
                            @foreach ($bill->totals as $total)
                                @php
                                    $totalName = explode(".",$total->name);
                                    $countNameArray = count($totalName);
                                    if($countNameArray == '1') {
                                        $name = $totalName[0];
                                    } else {
                                        $explodeWithunder = explode("_",$totalName[1]);
                                        $name = ucwords(implode(" ",$explodeWithunder));
                                    }
                                @endphp
                                @if ($total->code != 'total')
                                    <tr>
                                        <th style="width:50%">{{ $name }}:</th>
                                        <td>@money($total->amount, $bill->currency_code, true)</td>
                                    </tr>
                                @else
                                    @if ($bill->paid)
                                        <tr>
                                            <th class="text-success" style="width:50%">@lang('Paid')</th>
                                            <td>- @money($bill->paid, $bill->currency_code, true)</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th style="width:50%">{{ $name }}:</th>
                                        <td>@money($total->amount - $bill->paid, $bill->currency_code, true)</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <br>
            <div class="row no-print">
                <div class="col-12">
                    <a href="#" data-href="{{ route('bill.destroy', $bill) }}" class="btn btn-danger btn-lg float-right" data-toggle="modal" data-target="#myModal">
                        <span class="mdi mdi-delete ambitious-padding-btn"></span> Delete
                    </a>

                    <a class="btn btn-secondary btn-lg float-right" style="margin-right: 5px;" href="{{ route('bill.index') }}">
                        <i class="fas fa-ban"></i> Cancel
                    </a>

                    @if ($bill->bill_status_code != 'paid')
                        <a class="btn btn-lg btn-success" id="addPaymentModel" i_id="{{$bill->id}}" href="javascript:void(0)"> <i class="fas fa-money-check-alt mr-2"></i>Add Payment</a>
                    @endif

                    <button type="button" id="doPrint" class="btn btn-lg btn-info" style="margin-right: 5px;">
                        <i class="fas fa-print"></i> Print
                    </button>

                    <a href="{{ route('bill.edit', $bill) }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-pen"></i> Edit
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade modal-print" id="addPaymentModalView" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-inline">New Payment</h5>
                    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-material form-horizontal" action="#" method="POST" enctype="multipart/form-data">
                        @method('PATCH')
                        <input type="hidden" name="bill_id" id="bill_id" value="{{ $bill->id }}">
                        <input type="hidden" name="currency_code" id="currency_code" value="{{ $bill->currency_code }}">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="payment_date">@lang('Date') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-calendar"></i>
                                    </div>
                                    <input type="text" name="payment_date" id="payment_date" class="form-control" autofocus required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="payment_amount">@lang('Amount') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <input type="text" name="payment_amount" id="payment_amount" class="form-control" autofocus required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="payment_account">@lang('Account') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fa fa-university"></i></span>
                                    </div>
                                    <select class="form-control" id="payment_account" name="payment_account" required>
                                        <option value="">@lang('Select Account')</option>
                                        @foreach ($accounts as $key => $value)
                                            <option value="{{ $key }}" {{ old('account_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="payment_method">@lang('Payment Method') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="">@lang('Select Method')</option>
                                        @foreach ($payment_methods as $key => $value)
                                            <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md-12"><h4>@lang('Description')</h4></label>
                                <div class="col-md-12">
                                    <div id="payment_description" style="min-height: 55px;">
                                    </div>
                                    <input type="hidden" name="description" id="description">
                                </div>
                                @if ($errors->has('description'))
                                    {{ Session::flash('error',$errors->first('description')) }}
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer no-print">
                    <input id="add_payment_button" type="submit" value="@lang('Submit')" class="btn btn-primary"/>
                    <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-times"></i> @lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    @include('script.bill.show.js')
    @include('layouts.delete_modal')
@endsection
