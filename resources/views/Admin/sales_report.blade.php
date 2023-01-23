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


<div class="row">
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
                <td>Cheese</td>
                <td></td>
                <td></td>
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
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Sub Total</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                <p>Cash - </p>
                <p class="mt-3">Kpay - </p>
                <p class="mt-3">Wave - </p>
                <p class="mt-3">CB - </p>
                <p class="mt-3">AYA - </p>
                <p class="mt-3">YoMa - </p>
                <p class="mt-3">A+ - </p>
                <p class="mt-3">Total - </p>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <p>Expend -</p>
                    <p>Balance -</p>
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
@endsection

@section('js')
 <script>
    $(document).ready(function(){
        
       
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
                alert('success');
                let html = '';
                $('#sale_table').empty();
                $('#datePicker').val();
                html +=`
                    <thead style="background-color: lightblue; color: white; font-weight: bold;">
                        <tr>
                        <th></th>
                        <th>Pax</th>
                        <th>Amount</th>
                        <th>Remark</th>
                        </tr>
                    </thead>`;
                $.each(data, function(i,v){
                    // console.log(data)
                    // let url1 = "{{url('/Consumption/Details/')}}/"+v.id;
                    // let date_str = v.created_at.substring(0, 10);
                    html +=`
                    <tbody>
                        <tr>
                            <td>Adult</td>
                            <td>${v.adults}</td>
                            <td>${v.adults * 21900}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Child 9 to 12</td>
                            <td>${v.children}</td>
                            <td>${v.children * 11000}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Child 4 to 8</td>
                            <td>${v.kids}</td>
                            <td>${v.kids * 9000}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Cheese</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Extra Pot</td>
                            <td>${v.extra_pots}</td>
                            <td>${v.extra_pots * 3000}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Extra Food</td>
                            <td>${v.extra_grams}</td>
                            <td>${v.extra_amount}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td>${v.first_total}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Service Charge 5%</td>
                            <td></td>
                            <td>${v.service_charge}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td>${v.second_total}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Discount</td>
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
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Sub Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                            <p>Cash - </p>
                            <p class="mt-3">Kpay - </p>
                            <p class="mt-3">Wave - </p>
                            <p class="mt-3">CB - </p>
                            <p class="mt-3">AYA - </p>
                            <p class="mt-3">YoMa - </p>
                            <p class="mt-3">A+ - </p>
                            <p class="mt-3">Total - </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <p>Expend -</p>
                                <p>Balance -</p>
                            </td>
                        </tr>
                    </tbody>
                    `;
                })
                $('#sale_table').html(html);
            }
            })

        })
 </script>
@endsection
