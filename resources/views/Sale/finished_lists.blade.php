@extends('master')

@section('title','Finished Shop Order Page')

@section('place')

<div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Shop Order Page</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">Back to Dashborad</a></li>
        <li class="breadcrumb-item active">Shop Order Page</li>
    </ol>
</div>

@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="font-weight-bold mt-2">Finished Shop Order List</h4>
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
                    {{-- <table class="table" id="example23">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Table Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order_lists as $order)
                                <tr>
                                	<td>{{$order->order_number}}</td>
                                    @if($order->table_id == 0)
                                    <td>Take Away</td>
                                    @else
                                    <td>{{$order->table->table_number}}</td>
                                    @endif
                                    <td>

                                    	<a href="{{route('shop_order_voucher', $order->id)}}" class="btn btn-info">Check Voucher</a>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                    <table class="table" id="example23">
                        <thead>
                            <tr class="text-center">
                                <th>
                                   Voucher Number
                                </th>
                                <th>
                                    Total Amount
                                </th>
                                <th>
                                    Adult
                                </th>
                                <th>
                                    Child
                                </th>
                                <th>
                                    Kid
                                </th>
                                <th>
                                    Date
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="sale_table">
                            @foreach($vouchers as $vouc)
                            <tr class="text-center">
                                <td>{{$vouc->voucher_code}}</td>
                                <td>{{$vouc->total_price}}</td>
                                <td>{{$vouc->shopOrder->adult_qty==0? '-':$vouc->shopOrder->adult_qty}}</td>
                                <td>{{$vouc->shopOrder->child_qty==0? '-':$vouc->shopOrder->child_qty}}</td>
                                <td>{{$vouc->shopOrder->kid_qty==0? '-':$vouc->shopOrder->kid_qty}}</td>
                                @if ($vouc->type == 1)
                                <td>{{isset($vouc->shopOrder->table->table_number)? $vouc->shopOrder->table->table_number: "Take Away"}}</td>
                                @elseif($vouc->type == 2)
                                <td>{{isset($vouc->shopOrder->table->table_number)? $vouc->shopOrder->table->table_number." (Take Away)": "Take Away"}}</td>
                                @else
                                <td>Take Away</td>
                                @endif
                                <td>{{$vouc->date}}</td>
                                <td>
                                    @if ($vouc->type == 2)
                                    <a href="{{route('shop_voucher',$vouc->id)}}" class="btn btn-info">Check Voucher</a>
                                        <!-- @if ($vouc->status == 0)
                                        <a class="btn btn-danger text-white" onclick="cancelvoucher({{$vouc->id}})" id="hide_{{$vouc->id}}">Cancel</a>
                                        <span id="cancel_{{$vouc->id}}" hidden>(CANCEL)</span>
                                        @else
                                        <span>(CANCEL)</span>
                                        @endif -->
                                    @else
                                    <a href="{{route('shop_voucher1',$vouc->id)}}" class="btn btn-info">Check Voucher</a>
                                        <!-- @if ($vouc->status == 0)
                                        <a class="btn btn-danger text-white" onclick="cancelvoucher({{$vouc->id}})" id="hide_{{$vouc->id}}">Cancel</a>
                                        <span id="cancel_{{$vouc->id}}" hidden>(CANCEL)</span>
                                        @else
                                        <span>(CANCEL)</span>
                                        @endif -->
                                    @endif
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
        url:'/Finished-Voucher-DateFilter',
        data:{
        "_token":"{{csrf_token()}}",
        "start_date":start_date,
        "end_date":end_date,
        },
        success:function(data){
            let html = '';
            $.each(data, function(i,v){
                let url1 = "{{url('/shop_voucher1')}}/"+v.id;
                let url2 = "{{url('/shop_voucher/')}}/"+v.id;
                let table_no = 0;

                if(v.shop_order.table_id && v.type == 2){
                    table_no = v.shop_order.table.table_number+" (Take Away)";
                }else if(v.shop_order.table_id && v.type == 1){
                    table_no = v.shop_order.table.table_number;
                }else{
                    table_no = "Take Away";
                }
                // table_no = v.shop_order.table_id? v.shop_order.table_id: "Take Away";

                html +=`
                <tr class="text-center">
                    <td>${v.voucher_code}</td>
                    <td>${v.total_price}</td>
                    <td>${v.total_quantity}</td>
                    <td>${table_no}</td>
                    <td>${v.voucher_date.substring(0, 10)}</td>`;

                if(v.type == 1){
                    html +=`    <td>
                                <a href="${url1}" class="btn btn-info">Check Voucher</a>
                                </td>
                            </tr>`;
                }else{
                    html +=`    <td>
                                <a href="${url2}" class="btn btn-info">Check Voucher</a>
                                </td>
                        </tr>`;
                }

            })
            $('#sale_table').html(html);
        }
        })
    }

    function cancelvoucher(id){
        // alert(id);
        $('#cancel_'+id).removeAttr('hidden');
        $('#hide_'+id).hide();

        $.ajax({
        type:'POST',
        url:'/Voucher-Cancel',
        data:{
        "_token":"{{csrf_token()}}",
        "voucher_id":id,
        },
        success:function(data){
            console.log(data);
        }
    })

    }
 </script>
@endsection
