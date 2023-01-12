@extends('master')

@section('title','Finished Shop Order Page')

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
                <a class="btn btn-outline-primary fw-bold" href="/daily_purchase/create">+ Add New Purchase Item</a>
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
                                <th>Name</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Price</th>
                                <th>Stock Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="sale_table">
                                <!-- @php 
                                    $i = 0;
                                @endphp  -->
                            @foreach($purchase_items as $index=>$item)  
                            <tr class="text-center">
                                <td>{{$index+1}}</td>         
                                <td>{{$item->name}}</td>   
                                    @foreach($categories as $category)
                                        @if($category->id == $item->pi_category_id)
                                            <td>{{$category->name}}</td>        
                                        @endif          
                                    @endforeach
                                <td>{{$item->amount}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->stock_quantity}}</td>
                                <td>
                                    <a href="#" class="btn btn-outline-primary">Check Details</a>
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
            let html = '';
            $.each(data, function(i,v){
                console.log(data)
                let url1 = "{{url('/Shop-Order-Voucher/')}}/"+v.shop_order.id;
                let url2 = "{{url('/delivery_order_voucher/')}}/"+v.shop_order.id;
                html +=`
                <tr class="text-center">
                    <td>${v.voucher_code}</td>
                    <td>${v.total_price}</td>
                    <td>${v.total_quantity}</td>`;
                    if (v.type == 1){
                        html += `
                        <td>${v.shop_order.table.table_number}</td>
                        `;
                    }

                    else{
                        html += `
                        <td>Take Away</td>
                        `;
                    }
                    html += `
                    <td>${v.date}</td>
                    <td>
                    `;

                        if (v.type == 2){
                            html += `
                            <a href="${url2}" class="btn btn-info">Check Voucher</a>
                        </td>

                        </tr>
                            `;
                        }
                        else{
                            html +=`
                            <a href="${url1}" class="btn btn-info">Check Voucher</a>
                        </td>

                        </tr>
                            `;
                        }


            })
            $('#sale_table').html(html);
        }
        })
    }

 </script>
@endsection
