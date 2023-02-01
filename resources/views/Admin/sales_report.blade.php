@extends('master')

@section('title','Daily Consumption Page')

@section('content')
<style>
   body{
    font-size: 12px;
   }
   th{
    font-size: 15px;
   }
   th, tr, td{
    border: 2px solid #f3f1f1;
   }
   .table thead th, .table th{
    border: 2px solid #f3f1f1;
   }
   .table-bottom{
    margin: -17px 25px 50px 25px;
    color: black;
    font-weight: bold;
   }
</style>


<div class="row"  id='printableArea'>
<div class="offset-md-2 col-md-8">
<div class="card">
    <div class="card-body p-5">
    <h3 class="mb-4">Daily Sales Report</h3>
    <div class="float-right" style="position: relative; top: -10px;">
        <label for="">Date:</label>
        <input type="date" id="datePicker">
    </div>
    <table class="table table-hover" id="sale_table">
    <thead style="background-color: lightblue; color: white; font-weight: bold;">
            <tr>
            <th></th>
            <th>Pax</th>
            <th>Amount</th>
            <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Adult</td>
                <td>{{$adults}}</td>
                <td>{{$adults * 21900}}</td>
                <td></td>
            </tr>
            <tr>
                <td>Child 9 to 12</td>
                <td>{{$children}}</td>
                <td>{{$children * 11000}}</td>
                <td></td>
            </tr>
            <tr>
                <td>Child 4 to 8</td>
                <td>{{$kids}}</td>
                <td>{{$kids * 9000}}</td>
                <td></td>
            </tr>

            <tr>
                <td>Extra Pot</td>
                <td>{{$extra_pots}}</td>
                <td>{{$extra_pots * 3000}}</td>
                <td></td>
            </tr>
            <tr>
                <td>Extra Food</td>
                <td>{{$extra_grams}}</td>
                <td>{{$extra_amount}}</td>
                <td></td>
            </tr>
            <tr>
                <td>Total</td>
                <td></td>
                <td>{{$first_total}}</td>
                <td></td>
            </tr>
            <tr>
                <td>Service Charge 5%</td>
                <td></td>
                <td>{{$service_charge}}</td>
                <td></td>
            </tr>
            <tr>
                <td>Total</td>
                <td></td>
                <td>{{$second_total}}</td>
                <td></td>
            </tr>
            <tr>
                <td>Discount</td>
                <td></td>
                <td>{{$discount_amount}}</td>
                <td></td>
            </tr>
            <tr style="height: 43.933px;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr style="height: 43.933px;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr style="height: 43.933px;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr style="height: 43.933px;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Take away</td>
                <td></td>
                <td>{{$take_total}}</td>
                <td></td>
            </tr>
            <tr>
                <td>Sub Total</td>
                <td></td>
                <td>{{($second_total - $discount_amount) + $take_total}}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                <p>Cash - {{$cash}}</p>
                <p class="mt-3">Kpay - {{$kpay}}</p>
                <p class="mt-3">Wave - {{$wave}}</p>
                <p class="mt-3">CB - {{$cb}}</p>
                <p class="mt-3">AYA - {{$aya}}</p>
                <p class="mt-3">YoMa - {{$yoma}}</p>
                <p class="mt-3">A+ - {{$aplus}}</p>
                <p class="mt-3">Total - {{$second_total}}</p>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <p>Expend - {{$expend}}</p>
                    <p>Balance - {{(($second_total - $discount_amount) + $take_total) - $expend}}</p>
                </td>
            </tr>
        </tbody>
    </table>


</div>
<div class="d-flex justify-content-between table-bottom">
    <p>Checked By</p>
    <p>Received By</p>
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
@endsection

@section('js')
<script src="{{asset('js/jquery.PrintArea.js')}}" type="text/JavaScript"></script>
 <script>
    $(document).ready(function(){
    //    $('#datePicker').val("");
       $("#print").click(function() {
          var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("#printableArea").printArea(options);

            // let html = document.getElementById('printableArea').innerHTML;
            // $('#mobileprint').html(html);

            // var printContent = $('#mobileprint')[0];
            // var WinPrint = window.open('', '', 'width=900,height=650');
            // WinPrint.document.write('<html><head><title>Print Voucher</title>');
            // WinPrint.document.write('<link rel="stylesheet" type="text/css" href="css/style.css">');
            // WinPrint.document.write('<link rel="stylesheet" type="text/css" media="print" href="css/print.css">');
            // WinPrint.document.write('</head><body >');
            // WinPrint.document.write(printContent.innerHTML);
            // WinPrint.document.write('</body></html>');

            // WinPrint.focus();
            // WinPrint.print();
            // WinPrint.document.close();
            // WinPrint.close();
        });
    });
    $('#datePicker').change(function() {
            var startDate = $('#datePicker').val();

            $.ajax({
            type:'POST',
            url:'/Sales-Report-DateFilter',
            data:{
            "_token":"{{csrf_token()}}",
            "start_date":startDate,
            },
            success:function(data){
                let html = '';
                $('#sale_table').empty();
                $('#datePicker').val("");
                html +=`
                    <thead style="background-color: lightblue; color: white; font-weight: bold;">
                        <tr>
                        <th></th>
                        <th>Pax</th>
                        <th>Amount</th>
                        <th>Remark</th>
                        </tr>
                    </thead>`;
                // $.each(data, function(i,v){
                //     console.log(v)
                    // let url1 = "{{url('/Consumption/Details/')}}/"+data.id;
                    // let date_str = v.created_at.substring(0, 10);
                    html +=`
                    <tbody>
                        <tr>
                            <td>Adult</td>
                            <td>${data.adults}</td>
                            <td>${data.adults * 21900}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Child 9 to 12</td>
                            <td>${data.children}</td>
                            <td>${data.children * 11000}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Child 4 to 8</td>
                            <td>${data.kids}</td>
                            <td>${data.kids * 9000}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Extra Pot</td>
                            <td>${data.extra_pots}</td>
                            <td>${data.extra_pots * 3000}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Extra Food</td>
                            <td>${data.extra_grams}</td>
                            <td>${data.extra_amount}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td>${data.first_total}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Service Charge 5%</td>
                            <td></td>
                            <td>${data.service_charge}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td>${data.second_total}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td></td>
                            <td>${data.discount_amount}</td>
                            <td></td>
                        </tr>
                        <tr style="height: 43.933px;">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="height: 43.933px;">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="height: 43.933px;">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="height: 43.933px;">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Take away</td>
                            <td></td>
                            <td>${data.take_total}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Sub Total</td>
                            <td></td>
                            <td>${(data.second_total - data.discount_amount) + data.take_total}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                            <p>Cash - ${data.cash}</p>
                            <p class="mt-3">Kpay - ${data.kpay}</p>
                            <p class="mt-3">Wave - ${data.wave}</p>
                            <p class="mt-3">CB - ${data.cb}</p>
                            <p class="mt-3">AYA - ${data.aya}</p>
                            <p class="mt-3">YoMa - ${data.yoma}</p>
                            <p class="mt-3">A+ - ${data.aplus}</p>
                            <p class="mt-3">Total - ${data.second_total}</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <p>Expend - ${data.expend}</p>
                                <p>Balance - ${((data.second_total - data.discount_amount) + data.take_total) - data.expend}</p>
                            </td>
                        </tr>
                    </tbody>
                    `;
                // })
                $('#sale_table').html(html);
            }
            })

        })
 </script>
@endsection
