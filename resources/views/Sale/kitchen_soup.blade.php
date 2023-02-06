@extends('master')

@section('title','Kitchen List Details')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Kitchen Options</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">Back to Dashborad</a></li>
        <li class="breadcrumb-item active">Shop Order Voucher</li>
    </ol>
</div> --}}

@endsection

@section('content')

<style>

    td{
        text-align:left;
        font-size:15px;
    }

    th{
        text-align:left;
        font-size:15px;
    }

    h6{
        font-size:15px;
        font-weight:500;
    }
</style>
<?php $wname = session()->get('user')->name;?>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-5" style="width:45%;" id="printableArea">
                <div class="card card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center">
                                <address>
                                    <h4 style="font-weight:bold;"> &nbsp;<b class="text-center">Thai Pot&nbsp;&nbsp;(<span class="text-danger">Kitchen</span>)</b></h4>
                                        <h5 style="font-weight:bold;">Restaurant</h5>
                                </address>
                            </div>

                            <div class="pull-right text-left">
                                <h5 style="font-weight:bold;">Waiter Name : {{$wname}}</h5>
                                    <h5 style="font-weight:bold;">Date : <i class="fa fa-calendar"></i> {{$real_date}} </h5>
                                    @if($tablenoo != 1)

                                       <h5 style="font-weight:bold;">Table-Number : {{$tableno->table_number}}</h5>

                                    @elseif($tablenoo == 1)
                                    <h5 style="font-weight:bold;">Table-Number : Delivery Order</h5>
                                    @endif
                                </font>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="table-responsive" style="clear: both;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td style="font-weight:bold;">Kitchen</td>
                                            <td style="font-weight:bold;margin-left:30px;">Soup Name</td>
                                            <td style="font-weight:bold;margin-left:30px;">Qty</td>
                                        </tr>
                                    </thead>
                                <tbody>
                                    {{-- @foreach ($shop_order as $option) --}}
                                        <tr>
                                            <td>Extra</td>
                                            <td>{{$shop_order->soup_name}}</td>
                                            <td>{{$shop_order->extrapot_qty+1}}</td>
                                        </tr>
                                        @if ($shop_order->remark != null)
                                        <tr>
                                            <th class="text-danger" style="font-weight:bold;">Notes :</th>
                                            <td class="text-danger" colspan="3" style="font-weight:bold;">{{$shop_order->remark}}</td>
                                            </tr>
                                        @endif
                                    {{-- @endforeach --}}
                                </tbody>
                                </table>

                                <h5 class="text-center font-weight-bold">***************</h5>
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
                    @if ($tablenoo == 1)
                    <a href="{{route('delivery_pending_lists')}}" id="goto" class="btn btn-outline-danger" type="button">
                        <span><i class="fa fa-info"></i> To Pending Voucher Lists </span>

                    </a>
                    @else
                    <a href="/Pending-Order" id="goto" class="btn btn-outline-danger" type="button">
                        <span><i class="fa fa-info"></i> To Pending Voucher Lists </span>
                    </a>
                    @endif

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
            // window.print();
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
