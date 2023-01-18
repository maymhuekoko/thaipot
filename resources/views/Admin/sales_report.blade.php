@extends('master')

@section('title','Table List')

@section('place')

<div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Table List</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">Back to Dashborad</a></li>
        <li class="breadcrumb-item active">Table List</li>
    </ol>
</div>

@endsection

@section('content')

<style>
   
</style>


<div class="row bg-white p-5">
    <h3 class="my-3">Daily Sales Report</h3>
    
    <table class="table table-bordered">
    <thead>
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
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Child 9 to 12</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Child 4 to 8</td>
                <td></td>
                <td></td>
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
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Extra Food</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Total</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Service Charge 5%</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Total</td>
                <td></td>
                <td></td>
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



@endsection