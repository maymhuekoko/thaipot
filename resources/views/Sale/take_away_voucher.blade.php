@extends('master')

@section('title','Take Away Order Voucher')

@section('place')

<div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Take Away Order Voucher</h3>
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
                                        <strong style="font-size:16px;font-weight:bold;">Table Number : {{isset($voucher->shopOrder->table->table_number)?$voucher->shopOrder->table->table_number." (Take Away)": "Take Away"}}</strong><br>
                                        <strong style="font-size:16px;font-weight:bold;">Voucher Number : {{$voucher->voucher_code}}</strong><br>

                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top:12px;">
                                <div class="table-responsive" style="clear: both;">
                                    <table class="table">
                                        <thead>
                                            <tr style="text-align:left;">
                                                <th style="padding-left:20px;padding-right:20px;"><strong>Menu Name</strong></th>
                                                <th style="padding-left:20px;padding-right:20px;"><strong>Price</strong></th>
                                                <th style="padding-left:20px;padding-right:20px;"><strong>Quantity</strong></th>
                                                <th style="padding-left:20px;padding-right:20px;"><strong>Total</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $item)
                                            <tr style="font-size:13px;">
                                                @foreach($names as $name)
                                                    @if($item->item_id == $name->id)
                                                        <th style="padding-left:20px;padding-right:20px;">{{$name->item_name}}</td>
                                                    @endif
                                                @endforeach
                                                <th style="padding-left:20px;padding-right:20px;">{{$item->price}}</td>
                                                <th style="padding-left:20px;padding-right:20px;">{{$item->quantity}}</td>
                                                <th style="padding-left:20px;padding-right:20px;">{{$item->price * $item->quantity}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($voucher->discount_type == null)
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                         <strong>Voucher Total - {{$voucher->total_price}}</strong><br>
                                         @if ($voucher->promotion == 'Cash Back' || $voucher->promotion == 'Discount Percentage')
                                         <strong>{{$voucher->promotion}} - {{$voucher->promotion_value}}</strong><br>
                                          @if (explode(' ',$voucher->promotion_value)[1] == '%')
                                          <strong>Total - {{$voucher->total_price-($voucher->total_price*(explode(' ',$voucher->promotion_value)[0])/100)}}</strong><br>
                                          <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                          <strong>Change - {{$voucher->pay_value - ($voucher->total_price-($voucher->total_price*(explode(' ',$voucher->promotion_value)[0])/100))}}</strong><br>
                                          @else
                                          <strong>Total - {{$voucher->total_price - $voucher->promotion_value}}</strong><br>
                                          <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                          <strong>Change - {{$voucher->pay_value - ($voucher->total_price - $voucher->promotion_value)}}</strong><br>
                                          @endif
                                          @else
                                          <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                          <strong>Change - {{$voucher->change_value}}</strong><br>
                                         @endif

                                         @if ($voucher->promotion == 'FOC Items')
                                         <strong>{{$voucher->promotion}} - {{$voucher->promotion_value}}</strong><br>
                                         @endif
                                    </div>
                                    @elseif ($voucher->discount_type == 1)
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                        <strong>Voucher Total - {{$voucher->total_price}}</strong><br>
                                        <strong>Discount - FOC</strong><br>
                                        <strong>Total - 0</strong><br>
                                        <strong>Pay - 0</strong><br>
                                         <strong>Change - 0</strong><br>
                                   </div>
                                   @elseif ($voucher->discount_type == 2)
                                   <?php $total = $voucher->total_price - ($voucher->discount_value/100) * $voucher->total_price ; ?>
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                        <strong>Voucher Total - {{$voucher->total_price}}</strong><br>
                                        <strong>Discount - {{$voucher->discount_value}} %</strong><br>
                                        <strong>Total - {{$total}}</strong><br>
                                        <strong>Pay - {{$voucher->pay_value}}</strong><br>
                                         <strong>Change - {{$voucher->change_value}}</strong><br>
                                   </div>
                                   @elseif ($voucher->discount_type == 3)
                                   <?php $total = $voucher->total_price - $voucher->discount_value; ?>
                                    <div style="text-align:right;margin-right:10px;margin-top:20px;font-size:17px;font-weight:bold;">
                                        <strong>Voucher Total - {{$voucher->total_price}}</strong><br>
                                        <strong>Discount - {{$voucher->discount_value}} </strong><br>
                                        <strong>Total - {{$total}}</strong><br>
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
        //     var mode = 'iframe'; //popup
        //     var close = mode == "popup";
        //     var options = {
        //         mode: mode,
        //         popClose: close
        //     };
        //     $("#printableArea").printArea(options);

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
