@extends('master')

@section('title','Daily Purchase Page')

@section('place')

<div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Purchase Item List Page</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">Back to Dashborad</a></li>
        <li class="breadcrumb-item active">Purchase Item List Page</li>
    </ol>
</div>

@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between">
                <h4 class="font-weight-bold mt-2">Purchase Item List</h4>
                <a class="btn btn-outline-primary fw-bold" href="/daily_purchase/create">+ Add New Daily Purchase</a>
            </div>
            <div class="card-body">
                <div class="row form-group">
                    <div class="offset-md-3 col-md-3">
                        <label for="">Start Date</label>
                        <input type="date" class="form-control" id="start_date">
                    </div>
                    <div class="col-md-3">
                        <label for="">End Date</label>
                        <input type="date" class="form-control" id="end_date">
                    </div>
                    <div class="col-md-3" style="margin-top:35px;">
                        <button class="btn btn-m btn-primary" onclick="datefilter()">Search</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="example23">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Purchase Date</th>
                                <th>Total Quantity</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="sale_table">
                            @foreach($purchases as $index=>$item)  
                            <tr class="text-center">
                                <td>{{$index+1}}</td>  
                                <td>{{date_format($item->created_at, "Y-m-d")}}</td>
                                <td>{{$item->total_quantity}}</td>   
                                <td>{{$item->price}}</td>
                                <!-- date_format($date,"Y/m/d H:i:s") -->
                                <td>
                                    <a href="{{route('purchase_details',$item->id)}}" class="btn btn-outline-primary">Check Details</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
 <script>

    function datefilter(){
        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();

        $.ajax({
        type:'POST',
        url:'/Finished-Order-DateFilter',
        data:{
        "_token":"{{csrf_token()}}",
        "start_date":start_date,
        "end_date":end_date,
        },
        success:function(data){
            // console.log("Something"+ data);
            let html = '';
            $.each(data, function(i,v){
                let url1 = "{{url('/Purchase/Details/')}}/"+v.id;
                let date_str = v.created_at.substr(0, 10);
                html +=`
                <tr class="text-center">
                    <td>${i+1}</td>
                    <td>${date_str}</td>
                    <td>${v.price}</td>
                    <td>${v.total_quantity}</td>;
                    <td><a href="${url1}" class="btn btn-outline-primary">Check Details</a></td>
                <tr>`;


            })
            $('#sale_table').html(html);
        }
        })
    }

 </script>
@endsection
