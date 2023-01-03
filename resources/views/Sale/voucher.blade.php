@extends('master')

@section('title','Shop Order Voucher')

@section('place')

<div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Shop Order Voucher</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">Back to Dashborad</a></li>
        <li class="breadcrumb-item active">Shop Order Voucher</li>
    </ol>
</div>

@endsection

@section('content')

{{-- <style>

    td{
        text-align:left;
        font-size:15px;
    }

    th{
        text-align:left;
        font-size:15px;
    }

    p{
        font-size:24px;
        font-weight:800;
    }
</style> --}}

    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-5 printableArea" style="width:45%;" id='printableArea'>
                    <div class="card card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div  style="text-align:center;">
                                    <address>
                                        <strong style="font-size:17px;font-weight:bold;">THAI POT</strong><br>
                                            <strong style="font-size:17px;font-weight:bold;"> Restaurant</strong><br>
                                            <strong style="font-size:17px;font-weight:bold;"> No (767), YayLe` Road, Maubin</strong><br>
                                            <strong style="font-size:17px;font-weight:bold;">Ayeyarwady Region, Myanmar</strong><br>
                                            <strong style="font-size:17px;font-weight:bold;"><i class="fas fa-mobile-alt"></i> 09 770 725084</strong><br>
                                    </address>
                                </div>
                                <div class="pull-right text-left" style="margin-top:20px;">
                                    <strong style="font-size:16px;font-weight:bold;">Cashier Name: {{$voucher->sale_by}}</strong><br>
                                        <strong style="font-size:16px;font-weight:bold;">Date : <i class="fa fa-calendar"></i> {{$voucher->voucher_date}}</strong><br>
                                        <strong style="font-size:16px;font-weight:bold;">Table Number : {{$voucher->shopOrder->table->table_number}}</strong><br>
                                        <strong style="font-size:16px;font-weight:bold;">Voucher Number : {{$voucher->voucher_code}}</strong><br>

                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top:12px;">
                                <div class="table-responsive" style="clear: both;">
                                    <table class="table">
                                        <thead>
                                            <tr style="text-align:left;">
                                                <th ><strong>Person</strong></th>
                                                <th ><strong>Price & Qty</strong></th>
                                                <th><strong>Tax (5%)</strong></th>
                                                <th ><strong>Total</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($order->adult_qty != 0)
                                            <tr style="text-align:left;">
                                                <th >Adult</th>
                                                <th >20900 * <span>{{$order->adult_qty}}</span></th>
                                                <th>{{$order->adult_qty * 1050}}</th>
                                                <th>{{$order->adult_qty * 21950}}</th>
                                            </tr>
                                            @endif
                                            @if ($order->child_qty != 0)
                                            <tr style="text-align:left;">
                                                <th >Children</th>
                                                <th >11000 * <span>{{$order->child_qty}}</span></th>
                                                <th>{{$order->child_qty * 550}}</th>
                                                <th>{{$order->child_qty * 11550}}</th>
                                            </tr>
                                            @endif
                                            @if ($order->kid_qty != 0)
                                            <tr style="text-align:left;">
                                                <th >Kids</th>
                                                <th >9000 * <span>{{$order->kid_qty}}</span></th>
                                                <th>{{$order->kid_qty * 450}}</th>
                                                <th>{{$order->kid_qty * 9450}}</th>
                                            </tr>
                                            @endif
                                            @if ($order->extrapot_qty != 0)
                                            <tr style="text-align:left;">
                                                <th >Extra Pot</th>
                                                <th >3000 * <span>{{$order->extrapot_qty}}</span></th>
                                                <th></th>
                                                <th>{{$order->extrapot_qty * 3000}}</th>
                                            </tr>
                                            @endif
                                            @if($voucher->extra_gram != 0)
                                            <tr style="text-align:left;">
                                                <th >Extra Gram</th>
                                                <th >{{$voucher->extra_gram}}(g)</span></th>
                                                <th></th>
                                                <th>{{$voucher->extra_amount}}</th>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    @if($voucher->discount_type == null)
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                         <strong>Voucher Total - {{($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount}}</strong><br>
                                         @if ($voucher->promotion == 'Cash Back' || $voucher->promotion == 'Discount Percentage')
                                         <strong>{{$voucher->promotion}} - {{$voucher->promotion_value}}</strong><br>
                                          @if (explode(' ',$voucher->promotion_value)[1] == '%')
                                          <strong>Total - {{($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount-(($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount*(explode(' ',$voucher->promotion_value)[0])/100)}}</strong><br>
                                          <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                          <strong>Change - {{$voucher->pay_value - (($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount-(($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount*(explode(' ',$voucher->promotion_value)[0])/100))}}</strong><br>
                                          @else
                                          <strong>Total - {{($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount - $voucher->promotion_value}}</strong><br>
                                          <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                          <strong>Change - {{$voucher->pay_value - (($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount - $voucher->promotion_value)}}</strong><br>
                                          @endif
                                          @else
                                          @if ($order->birth_qty != 0)
                                          <strong>Birthday Discount(20%) - {{$order->birth_qty * 4390}}</strong><br>
                                          <strong>Total - {{($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount - ($order->birth_qty * 4390)}}</strong><br>
                                          @endif
                                          <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                          <strong>Change - {{$voucher->change_value}}</strong><br>
                                         @endif

                                         @if ($voucher->promotion == 'FOC Items')
                                         <strong>{{$voucher->promotion}} - {{$voucher->promotion_value}}</strong><br>
                                         @endif
                                    </div>
                                    @elseif ($voucher->discount_type == 1)
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                        <strong>Voucher Total - {{$order->adult_qty * 21950+$order->child_qty * 11550+ $order->kid_qty * 9450+ $order->extrapot_qty *3000 + $voucher->extra_amount}}</strong><br>
                                        <strong>Discount - FOC(1 person)</strong><br>
                                        @if ($order->birth_qty != 0)
                                        <strong>Birthday Discount(20%) - {{$order->birth_qty * 4390}}</strong>
                                        @endif
                                        <strong>Total - {{($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount - 20900 - ($order->birth_qty * 4390)}}</strong><br>
                                        <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                        <strong>Change - {{$voucher->change_value}}</strong><br>
                                   </div>
                                   @elseif ($voucher->discount_type == 2)
                                   <?php $total = ($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount - ($voucher->discount_value/100) * ($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount ; ?>
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                        <strong>Voucher Total - {{$order->adult_qty * 21950+$order->child_qty * 11550+ $order->kid_qty * 9450+ $order->extrapot_qty *3000 + $voucher->extra_amount}}</strong><br>
                                        <strong>Discount - {{$voucher->discount_value}} %</strong><br>
                                        @if ($order->birth_qty != 0)
                                        <strong>Birthday Discount(20%) - {{$order->birth_qty * 4390}}</strong>
                                        @endif
                                        <strong>Total - {{$total - ($order->birth_qty * 4390)}}</strong><br>
                                        <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                         <strong>Change - {{$voucher->change_value}}</strong><br>
                                   </div>
                                   @elseif ($voucher->discount_type == 3)
                                   <?php $total = ($order->adult_qty * 21950)+($order->child_qty * 11550)+ ($order->kid_qty * 9450)+ ($order->extrapot_qty *3000) + $voucher->extra_amount - $voucher->discount_value; ?>
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                        <strong>Voucher Total - {{$order->adult_qty * 21950+$order->child_qty * 11550+ $order->kid_qty * 9450+ $order->extrapot_qty *3000 + $voucher->extra_amount}}</strong><br>
                                        <strong>Discount - {{$voucher->discount_value}} </strong><br>
                                        @if ($order->birth_qty != 0)
                                        <strong>Birthday Discount(20%) - {{$order->birth_qty * 4390}}</strong>
                                        @endif
                                        <strong>Total - {{$total - ($order->birth_qty * 4390)}}</strong><br>
                                        {{-- <strong>Total - {{$total}}</strong><br> --}}
                                        <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                         <strong>Change - {{$voucher->change_value}}</strong><br>
                                   </div>
                                    @endif
                                    <h6  style="text-align:center;margin-top:10px;">**ကျေးဇူးတင်ပါသည်***</h6>
                            </div>
                        </div>
                    </div>
                 </div>

                </div>

                <div class="col-md-12">
                    <div class="text-center">
                        <button id="print" class="btn btn-info" type="button">
                            <span><i class="fa fa-print"></i> Print</span>
                        </button>
                    </div>
                </div>
                <div id="mobileprint" class="d-none">

                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')

<script src="{{asset('js/jquery.PrintArea.js')}}" type="text/JavaScript"></script>

<script>
    $(document).ready(function() {
        $("#print").click(function() {
            // var mode = 'iframe'; //popup
            // var close = mode == "popup";
            // var options = {
            //     mode: mode,
            //     popClose: close
            // };
            // $("div.printableArea").printArea(options);


            let html = document.getElementById('printableArea').innerHTML;
            $('#mobileprint').html(html);

            var printContent = $('#mobileprint')[0];
            var WinPrint = window.open('', '', 'width=900,height=650');
            WinPrint.document.write('<html><head><title>Print Voucher</title>');
            WinPrint.document.write('<link rel="stylesheet" type="text/css" href="css/style.css">');
            WinPrint.document.write('<link rel="stylesheet" type="text/css" media="print" href="css/print.css">');
            WinPrint.document.write('</head><body >');
            WinPrint.document.write(printContent.innerHTML);
            WinPrint.document.write('</body></html>');

            WinPrint.focus();
            WinPrint.print();
            WinPrint.document.close();
            WinPrint.close();
        });
    });
    </script>


@endsection
