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
                <div class="col-md-5" style="width:45%;" id='printableArea'>
                    <div class="card card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div  style="text-align:center;">
                                    <address>
                                        <strong style="font-size:17px;font-weight:bold;">THAI POT</strong><br>
                                            <strong style="font-size:17px;font-weight:bold;"> Restaurant</strong><br>
                                            <strong style="font-size:17px;font-weight:bold;"> No (4), Kan Road, Hlaing Township</strong><br>
                                            <strong style="font-size:17px;font-weight:bold;">Yangon, Myanmar</strong><br>
                                            <strong style="font-size:17px;font-weight:bold;"><i class="fas fa-mobile-alt"></i> 09 5007997, 09260523688</strong><br>
                                    </address>
                                </div>
                                <div class="pull-right text-left" style="margin-top:20px;">
                                    <strong style="font-size:16px;font-weight:bold;">Cashier Name: {{$voucher->sale_by}}</strong><br>
                                        <strong style="font-size:16px;font-weight:bold;">Date : <i class="fa fa-calendar"></i> {{$voucher->voucher_date}}</strong><br>
                                        <strong style="font-size:16px;font-weight:bold;">Table Number : {{isset($voucher->shopOrder->table->table_number)? $voucher->shopOrder->table->table_number: 'Take Away'}}</strong><br>
                                        <strong style="font-size:16px;font-weight:bold;">Voucher Number : {{$voucher->voucher_code}}</strong><br>

                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top:12px;">
                                <div class="table-responsive" style="clear: both;">
                                    <table class="table">
                                        <thead>
                                            <tr style="text-align:left;">
                                                <th style="padding-left:20px;padding-right:20px;"><strong>Person</strong></th>
                                                <th style="padding-left:20px;padding-right:20px;"><strong>Price & Qty</strong></th>
                                                <th style="padding-left:20px;padding-right:20px;"><strong>Total</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($order->adult_qty != 0)
                                            <tr style="text-align:left;">
                                                <th style="padding-left:20px;padding-right:20px;">Adult</th>
                                                <th style="padding-left:20px;padding-right:20px;">21900 * <span>{{$order->adult_qty}}</span></th>
                                                <th style="padding-left:20px;padding-right:20px;">{{$order->adult_qty * 21900}}</th>
                                            </tr>
                                            @endif
                                            @if ($order->child_qty != 0)
                                            <tr style="text-align:left;">
                                                <th style="padding-left:20px;padding-right:20px;">Children</th>
                                                <th style="padding-left:20px;padding-right:20px;">11000 * <span>{{$order->child_qty}}</span></th>
                                                <th style="padding-left:20px;padding-right:20px;">{{$order->child_qty * 11000}}</th>
                                            </tr>
                                            @endif
                                            @if ($order->kid_qty != 0)
                                            <tr style="text-align:left;">
                                                <th style="padding-left:20px;padding-right:20px;">Kids</th>
                                                <th style="padding-left:20px;padding-right:20px;">9000 * <span>{{$order->kid_qty}}</span></th>
                                                <th style="padding-left:20px;padding-right:20px;">{{$order->kid_qty * 9000}}</th>
                                            </tr>
                                            @endif
                                            @if ($order->cheese_qty != 0)
                                            <tr style="text-align:left;">
                                                <th style="padding-left:20px;padding-right:20px;">Cheese Sauce</th>
                                                <th style="padding-left:20px;padding-right:20px;">1900 * <span>{{$order->cheese_qty}}</span></th>
                                                <th style="padding-left:20px;padding-right:20px;">{{$order->cheese_qty * 1900}}</th>
                                            </tr>
                                            @endif
                                            @if ($order->extrapot_qty != 0)
                                            <tr style="text-align:left;">
                                                <th style="padding-left:20px;padding-right:20px;">Extra Pot</th>
                                                <th style="padding-left:20px;padding-right:20px;">3000 * <span>{{$order->extrapot_qty}}</span></th>
                                                <th style="padding-left:20px;padding-right:20px;">{{$order->extrapot_qty * 3000}}</th>
                                            </tr>
                                            @endif
                                            @if($voucher->extra_gram != 0)
                                            <tr style="text-align:left;">
                                                <th style="padding-left:20px;padding-right:20px;">Extra Gram</th>
                                                <th style="padding-left:20px;padding-right:20px;">{{$voucher->extra_gram}}(g)</span></th>
                                                <th style="padding-left:20px;padding-right:20px;">{{$voucher->extra_amount}}</th>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    @if($voucher->discount_type == null)
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                         <strong>Voucher Total - {{$voutotal}}</strong><br>
                                         <strong>Service Charges(5%) - {{$servicecharges}}(+)</strong><br>
                                         @if ($voucher->govtax == 1)
                                         <strong>Gov Tax(5%) -<span>{{$servicecharges}}(+)</span></strong><br>
                                         @else
                                         <strong>Gov Tax(5%) -<span style="padding-left: 50px;">(+)</span></strong><br>
                                         @endif

                                         @if ($voucher->promotion == 'Cash Back' || $voucher->promotion == 'Discount Percentage')
                                         <strong>{{$voucher->promotion}} - {{$voucher->promotion_value}}</strong><br>
                                          @if (explode(' ',$voucher->promotion_value)[1] == '%')
                                          <strong>Total - {{$voutotal-($voutotal*(explode(' ',$voucher->promotion_value)[0])/100)}}</strong><br>
                                          <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                          <strong>Change - {{$voucher->pay_value - $voutotal-(($voutotal*(explode(' ',$voucher->promotion_value)[0])/100))}}</strong><br>
                                          @else
                                          <strong>Total - {{$voutotal - $voucher->promotion_value}}</strong><br>
                                          <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                          <strong>Change - {{$voucher->pay_value - ($voutotal - $voucher->promotion_value)}}</strong><br>
                                          @endif
                                          @else
                                          @if ($order->birth_qty != 0)
                                          {{-- <strong>Sub Total - {{$voutotal + $servicecharges}}</strong><br> --}}
                                          <strong>Birthday Discount(20%) - {{$order->birth_qty * 4600}}(-)</strong><br>
                                          <strong>Total - {{$voucher->govtax != 0 ? $voutotal + (2*$servicecharges) - ($order->birth_qty * 4600) : $voutotal + $servicecharges - ($order->birth_qty * 4600)}}</strong><br>
                                          @else
                                          <strong>Total - {{$voucher->govtax != 0 ? $voutotal + (2*$servicecharges) : $voutotal + $servicecharges}}</strong><br>
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
                                        <strong>Voucher Total - {{$voutotal}}</strong><br>
                                        <strong>Service Charges(5%) - {{$servicecharges}} (+)</strong><br>
                                        @if ($voucher->govtax == 1)
                                        <strong>Gov Tax(5%) -<span>{{$servicecharges}}(+)</span></strong><br>
                                        @else
                                        <strong>Gov Tax(5%) -<span style="padding-left: 50px;">(+)</span></strong><br>
                                        @endif
                                        {{-- <strong>Sub Total - {{$voutotal + $servicecharges}} </strong><br> --}}
                                        @if ($order->birth_qty != 0)
                                        <strong>Birthday Discount(20%) - {{$order->birth_qty * 4600}} (-)</strong><br>
                                        @endif
                                        <strong>Discount - FOC(1 person)(-21900)</strong><br>
                                        <strong>Total - {{($voutotal+ ($voucher->govtax == 0 ? $servicecharges : 2 * $servicecharges)) - 21900 - ($order->birth_qty * 4600)}}</strong><br>
                                        <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                        <strong>Change - {{$voucher->change_value}}</strong><br>
                                        @if ($voucher->remark != null)
                                        <strong style="color:red;">Remark - {{$voucher->remark}}</strong><br>
                                        @endif
                                   </div>
                                   @elseif ($voucher->discount_type == 2)
                                   <?php $total = $voutotal - ($voucher->discount_value/100) * $voutotal ; ?>
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                        <strong>Voucher Total - {{$voutotal}}</strong><br>
                                        <strong>Service Charges(5%) - {{$servicecharges}} (+)</strong><br>
                                        @if ($voucher->govtax == 1)
                                        <strong>Gov Tax(5%) -<span>{{$servicecharges}}(+)</span></strong><br>
                                        @else
                                        <strong>Gov Tax(5%) -<span style="padding-left: 50px;">(+)</span></strong><br>
                                        @endif
                                        {{-- <strong>Sub Total - {{$voutotal + $servicecharges}} </strong><br> --}}
                                        @if ($order->birth_qty != 0)
                                        <strong>Birthday Discount(20%) - {{$order->birth_qty * 4600}} (-)</strong><br>
                                        @endif
                                        <strong>Discount - {{$voucher->discount_value}} % (-)</strong><br>
                                        <strong>Total - {{($voutotal+ ($voucher->govtax == 0 ? $servicecharges : 2 * $servicecharges))-((($voutotal+ ($voucher->govtax != 0 ? $servicecharges : 2 * $servicecharges))-($order->birth_qty * 4600))*($voucher->discount_value/100))-($order->birth_qty * 4600)}}</strong><br>
                                        <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                         <strong>Change - {{$voucher->change_value}}</strong><br>
                                         @if ($voucher->remark != null)
                                         <strong style="color:red;">Remark - {{$voucher->remark}}</strong><br>
                                         @endif
                                   </div>
                                   @elseif ($voucher->discount_type == 3)
                                   <?php $total = $voutotal - $voucher->discount_value; ?>
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                        <strong>Voucher Total - {{$voutotal}}</strong><br>
                                        <strong>Service Charges(5%) - {{$servicecharges}} (+)</strong><br>
                                        @if ($voucher->govtax == 1)
                                        <strong>Gov Tax(5%) -<span>{{$servicecharges}}(+)</span></strong><br>
                                        @else
                                        <strong>Gov Tax(5%) -<span style="padding-left: 50px;">(+)</span></strong><br>
                                        @endif
                                        {{-- <strong>Sub Total - {{$voutotal + $servicecharges}} </strong><br> --}}
                                        @if ($order->birth_qty != 0)
                                        <strong>Birthday Discount(20%) - {{$order->birth_qty * 4600}} (-)</strong><br>
                                        @endif
                                        <strong>Discount - {{$voucher->discount_value}} (-)</strong><br>
                                        <strong>Total - {{($voutotal+ ($voucher->govtax == 0 ? $servicecharges : 2 * $servicecharges)) - $voucher->discount_value -($order->birth_qty * 4600)}}</strong><br>
                                        <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                         <strong>Change - {{$voucher->change_value}}</strong><br>
                                         @if ($voucher->remark != null)
                                         <strong style="color:red;">Remark - {{$voucher->remark}}</strong><br>
                                         @endif
                                   </div>
                                    @endif
                                    <h6  style="text-align:center;margin-top:10px;">**?????????????????????????????????????????????***</h6>
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
            // $("#printableArea").printArea(options);


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
