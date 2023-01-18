@extends('master')

@section('title','Table List')

{{-- @section('place')

<div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Table List</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">Back to Dashborad</a></li>
        <li class="breadcrumb-item active">Table List</li>
    </ol>
</div>

@endsection --}}

@section('content')

<style>
   body{
    font-size: 12px;
   }
</style>


<h3 class="my-3">Daily Sales Report</h3>

<div class="row">
<div class="offset-md-1 col-md-6">
<div class="card">
        <div class="card-body">
    <table class="table table-hover">
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
                <td>{{$adults * 20900}}</td>
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
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
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
        </tbody>
    </table>
</div>
</div>
</div>

<div class="col-md-4">
   <div class="card">
        <div class="card-body my-4">
            <h4>Cash - </h4>
            <h4 class="mt-3">Kpay - </h4>
            <h4 class="mt-3">Wave - </h4>
            <h4 class="mt-3">CB - </h4>
            <h4 class="mt-3">AYA - </h4>
            <h4 class="mt-3">YoMa - </h4>
            <h4 class="mt-3">A+ - </h4>
            <h4 class="mt-3">Total - </h4>
        </div>
   </div>
</div>
</div>



@endsection