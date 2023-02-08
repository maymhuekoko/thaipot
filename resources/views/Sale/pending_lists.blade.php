@extends('master')

@section('title','Shop Order Pending Page')

@section('place')

<!--<div class="col-md-5 col-8 align-self-center">-->
<!--    <h3 class="text-themecolor m-b-0 m-t-0">Pending Shop Order Page</h3>-->
<!--    <ol class="breadcrumb">-->
<!--        <li class="breadcrumb-item"><a href="{{route('index')}}">Back to Dashborad</a></li>-->
<!--        <li class="breadcrumb-item active">Pending Shop Order Page</li>-->
<!--    </ol>-->
<!--</div>-->

@endsection

@section('content')
<?php $user = session()->get('user')->role_flag;?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="font-weight-bold mt-2">Pending Shop Order List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="example23">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Table Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pending_lists as $order)
                                <tr>
                                	<td>{{$order->order_number}}</td>
                                    @if($order->table_id == 0)
                                    <td>Take Away</td>
                                    @else
                                    <td>{{$order->table->table_number}}</td>
                                    @endif
                                    <td>
                                    	<a href="{{route('pending_order_details', $order->id)}}" class="btn btn-info">Check Order Details</a>

                                    	<a href="{{route('add_more_item', $order->id)}}" class="btn btn-success">Extra Meal</a>
                                    	@if($user == 3)
                                    	    <button class="btn" style="background-color:lightgreen;color:white;" onclick="done({{$order->table_id}})">Done</button>
                                            {{-- <button class="btn btn-danger" style="color:white;" onclick="cancel({{$order->id}})">Cancel</button> --}}
                                            {{-- <a href="{{route('cancelorder', $order->id)}}" class="btn btn-danger">Cancel</a> --}}
                                    	@else
                                    	        <button class="btn btn-primary" onclick="storeVoucher({{$order->id}}, {{$order->price}})">Store Voucher</button>
                                            {{-- <a href="{{route('cancelorder', $order->id)}}" class="btn btn-danger">Cancel</a> --}}
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
<div class="modal fade" id="voudiscount" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title text-white">Item Price</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    id="#close_modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="checkout_modal_body">
                <input type="hidden" id="vou_discount" name="vou_discount">
                <input type="hidden" id="hid_order_id">
                <input type="hidden" id="dis_type">
                <input type="hidden" id="dis_val">
                <div class="form-group">
                    <label class="font-weight-bold">Voucher Total</label>
                    <input type="text" class="form-control" readonly id="voucher_total" value="">
                </div>
                <div class="row">
                    <div class="form-group  col-md-6" id="extra_gram1">
                        <label class="font-weight-bold">Extra Gram</label>
                        <input type="text" class="form-control"  id="no_extraamt1" value="0"  onkeyup="extragramadd1(this.value)">
                    </div>
                    <div class="form-group  col-md-6" id="extra_amt1">
                        <label class="font-weight-bold">Extra Amount</label>
                        <input type="text" class="form-control"  id="no_extra1" value="0" readonly>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="radio_foc" onclick="foc_radio()">
                        <label class="form-check-label" for="radio_foc">
                          FOC
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="radio_percent" onclick="percent_radio()">
                        <label class="form-check-label" for="radio_percent">
                          Discount Percent
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="radio_amount" onclick="amount_radio()">
                        <label class="form-check-label" for="radio_amount">
                          Discount Amount
                        </label>
                    </div>
                </div>
                <div class="form-group mt-3" id="dis_foc">
                    <label class="font-weight-bold">FOC</label>
                    <input type="text" class="form-control"  value="21900">
                </div>
                <div class="form-group mt-3" id="dis_percent">
                    <label class="font-weight-bold">Discount Percent</label>
                    <input type="text" class="form-control"  value="" placeholder="Enter percent (%)" onkeyup="percent_dis(this.value)">
                </div>
                <div class="form-group mt-3" id="dis_amount">
                    <label class="font-weight-bold">Discount Amount</label>
                    <input type="text" class="form-control amount_dis"  value="" placeholder="Enter Amount" onkeyup="amount_dis(this.value)">
                </div>
                <div class="form-group mt-3">
                    <label class="font-weight-bold">Current Voucher Total</label>
                    <input type="text" class="form-control" readonly id="curr_voucher_total1" value="">
                </div>
                <div class="form-group mt-3">
                    <label class="font-weight-bold">Pay Amount</label>
                    <input type="text" class="form-control"  value="" id="pay_amount" placeholder="Enter Pay Amount" onkeyup="pay_amt1(this.value)">
                </div>
                <div class="form-group mt-3" id="type_pay">
                    <label class="font-weight-bold">Pay Type</label>
                    <select name="pay_type" class="form-control" id="pay_type">
                        <option value="1">Cash</option>
                        <option value="2">K Pay</option>
                        <option value="1">Wave</option>
                        <option value="1">CB</option>
                        <option value="1">AYA</option>
                        <option value="1">YOMA</option>
                        <option value="1">A+</option>
                    </select>
                </div>
                <div class="form-group mt-3 row">
                    <label class="font-weight-bold col-md-4">Gov Tax</label>
                    <div class="col-md-4 form-check">
                        <input class="form-check-input" type="radio" name="flexRadio3" id="gov_yes1">
                        <label class="form-check-label" for="gov_yes1">
                          YES
                        </label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input class="form-check-input" type="radio" name="flexRadio3" id="gov_no1" checked>
                        <label class="form-check-label" for="gov_no1">
                          NO
                        </label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label class="font-weight-bold">Change</label>
                    <input type="text" class="form-control" readonly id="change_amount1" value="">
                </div>
                <div class="form-group mt-3">
                    <label class="font-weight-bold">Remark</label>
                    <input type="text" class="form-control" id="vou_remark" value="">
                </div>
                <button type="button" class="btn btn-success mt-4" onclick="change_price()" btn-lg
                    btn-block">Store Voucher</button>
            </div>


        </div>
    </div>
</div>
<div class="modal fade" id="dis_radio_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Store Voucher</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row text-center">
                <div class="col-md-4 mb-2">
                    <h4 class="font-weight-bold">Discount:</h4>
                </div>
                <div class="col-md-4 form-check">
                    <input class="form-check-input" type="radio" name="flexRadio" id="radio_yes" onclick="yes_radio()">
                    <label class="form-check-label" for="radio_yes">
                      YES
                    </label>
                </div>
                <div class="col-md-4 form-check">
                    <input class="form-check-input" type="radio" name="flexRadio" id="radio_no" onclick="no_radio()">
                    <label class="form-check-label" for="radio_no">
                      NO
                    </label>
                </div>
            </div>
            <div class="form-group" id="dis_voucher_total">
                <label class="font-weight-bold">Voucher Total</label>
                <input type="text" class="form-control" readonly id="voucher_total_dis" value="">
            </div>
            <div class="row" id="extra_gram_display">
                <div class="form-group col-md-6" id="extra_gram">
                    <label class="font-weight-bold">Extra Gram</label>
                    <input type="text" class="form-control"  id="no_extraamt" value="0"  onkeyup="extragramadd(this.value)">
                </div>
                <div class="form-group col-md-6" id="extra_amt">
                    <label class="font-weight-bold">Extra Amount</label>
                    <input type="text" class="form-control"  id="no_extra" value="0" readonly>
                </div>
            </div>
            <div class="form-group mt-3" id="curr_extra_total">
                <label class="font-weight-bold">Current Voucher Total</label>
                <input type="text" class="form-control" readonly  value="" id="curr_voucher_total">
            </div>

            <div class="form-group mt-3" id="dis_pay_amount">
                <label class="font-weight-bold">Pay Amount</label>
                <input type="text" class="form-control"  value="" id="pay_amount_dis" placeholder="Enter Pay Amount" onkeyup="pay_amt(this.value)">
            </div>
            <div class="form-group mt-3" id="dis_pay_type">
                <label class="font-weight-bold">Pay Type</label>
                <select name="pay_type_dis" class="form-control" id="pay_type_dis">
                    <option value="1">Cash</option>
                    <option value="2">K Pay</option>
                    <option value="3">Wave</option>
                    <option value="4">CB</option>
                    <option value="5">AYA</option>
                    <option value="6">YOMA</option>
                    <option value="7">A+</option>
                </select>
            </div>
            <div class="form-group mt-3 row" id="dis_govtax">
                <label class="font-weight-bold col-md-4">Gov Tax</label>
                <div class="col-md-4 form-check">
                    <input class="form-check-input" type="radio" name="flexRadio2" id="gov_yes">
                    <label class="form-check-label" for="gov_yes">
                      YES
                    </label>
                </div>
                <div class="col-md-4 form-check">
                    <input class="form-check-input" type="radio" name="flexRadio2" id="gov_no" checked>
                    <label class="form-check-label" for="gov_no">
                      NO
                    </label>
                </div>
            </div>
            <div class="form-group mt-3" id="dis_change_amount">
                <label class="font-weight-bold">Change</label>
                <input type="text" class="form-control" readonly id="change_amount" value="">
            </div>
            <div class="row">

            </div>
            <div class="row" id="ispromotion">

            </div>
            <input type="hidden" id="bd_exit">
        </div>
        <div class="modal-footer" id="dis_footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="change_price()">Store Voucher</button>
        </div>
      </div>
    </div>
</div>
@endsection

@section('js')

<script>

 $(document).ready(function() {
    $('#dis_foc').hide();
    $('#dis_percent').hide();
    $('#dis_amount').hide();
    $('#dis_pay_type').hide();
})
function yes_radio(){
    // alert('yes');
    $('#dis_radio_form').modal('hide');
    $('#voudiscount').modal('show');
    $('#extra_gram1').show();
    $('#extra_amt1').show();
}
function no_radio(){
    // alert('no');
    $('#dis_voucher_total').show();
    $('#dis_pay_amount').show();
    $('#dis_change_amount').show();
    $('#dis_govtax').show();
    $('#promotion').show();
    $('#dis_footer').show();
    $('#curr_extra_total').show();
    $('#extra_gram').show();
    $('#extra_amt').show();
    $('#dis_pay_type').show();
}

function extragramadd(val){
    // alert(val);
    $('#no_extra').val(val*35);
    var tot = parseInt($('#voucher_total_dis').val()) + parseInt(val*35);
    var ser = tot * 0.05;
    var bd = $('#bd_exit').val();
    // alert(bd);
    var vtot = tot + ser - bd;
    $('#curr_voucher_total').val(vtot);
}

function extragramadd1(val){
    // alert(val);
    $('#no_extra1').val(val*35);
    var total = parseInt($('#voucher_total').val()) + parseInt(val*35);
    var ser1 = total * 0.05;
    var bd1 = $('#bd_exit').val();
    // alert(bd1);
    var vtotal = total + ser1 -bd1;
    $('#curr_voucher_total1').val(vtotal);
}

function foc_radio(){
    $('#dis_foc').show();
    $('#dis_percent').hide();
    $('#dis_amount').hide();
    var dis_value = $('#curr_voucher_total1').val(parseInt($('#curr_voucher_total1').val()) -21900);
    $('#dis_type').val(1);
    $('#dis_val').val(21900);
}
function percent_radio(){
    $('#dis_foc').hide();
    $('#dis_percent').show();
    $('#dis_amount').hide();
    $('#dis_type').val(2);
}
function amount_radio(){
    $('#dis_foc').hide();
    $('#dis_percent').hide();
    $('#dis_amount').show();
    $('#dis_type').val(3);
}
function percent_dis(val){
 var t = parseInt($('#voucher_total').val()) + parseInt($('#no_extra1').val());
 var bdp = $('#bd_exit').val();
 var tot = t + parseInt(t * 0.05) - bdp;

    $('#curr_voucher_total1').val(tot-(parseInt(tot/100 * val)));
    $('#dis_val').val(val);
}
function amount_dis(val){
    var t = parseInt($('#voucher_total').val()) + parseInt($('#no_extra1').val());
    var bda = $('#bd_exit').val();
   var tot = t + parseInt(t * 0.05) - bda;
    $('#curr_voucher_total1').val(tot - parseInt(val));
    $('#dis_val').val(val);
}
function pay_amt(val){
    // alert(val);
    var curr_amt = $('#curr_voucher_total').val();
    $('#change_amount').val(val - curr_amt);
}

function pay_amt1(val){
    // alert(val);
    var curr_amt = $('#curr_voucher_total1').val();
    $('#change_amount1').val(val - curr_amt);
}

function pay_dis(val){
    // alert(val);
    var curr_amt = $('#voucher_total_dis').val();
    $('#change_amount_dis').val(val - curr_amt);
}
function promotion_on(){
    if($('#console').prop("checked") == true){
         var console = 1;
         $('#promotion_name').show();
    }else{
       var console = 0;
       $('#promotion_name').hide();
    }
}
function change_price(){
    // $('#voudiscount').modal('hide');
    var order_id = $('#hid_order_id').val();
    var discount_type = $('#dis_type').val();
    var discount_value = $('#dis_val').val();
    var pay_value = $('#pay_amount').val();
    var change_value = $('#change_amount1').val();
    var vou_remark = $('#vou_remark').val();
    var pay_value_dis = $('#pay_amount_dis').val();
    var change_value_dis = $('#change_amount').val();
    var extra_amt1 = $('#no_extra1').val();
    var extra_amt = $('#no_extra').val();
    var extra_gram1 = $('#no_extraamt1').val();
    var extra_gram = $('#no_extraamt').val();
    var pay_type = $('#pay_type').val();
    var pay_type_dis = $('#pay_type_dis').val();

    if($('#gov_yes').is(":checked")){
        var govtax_dis = 1;
    }else{
        var govtax_dis = 0;
    }

    if($('#gov_yes1').is(":checked")){
        var govtax = 1;
    }else{
        var govtax = 0;
    }


    if(change_value_dis>=0 && change_value>=0){
        $.ajax({

        type:'POST',

        url:'/ShopVoucherStore',

        data:{
        "_token":"{{csrf_token()}}",
        "order_id": order_id,
        "discount_type" : discount_type ,
        "discount_value" : discount_value,
        "pay_amount" : pay_value,
        "change_amount" : change_value,
        "pay_amount_dis" : pay_value_dis,
        "change_amount_dis" : change_value_dis,
        "extragram1" : extra_gram1,
        "extragram" : extra_gram,
        "extraamt1" : extra_amt1,
        "extraamt" : extra_amt,
        "pay_type" : pay_type,
        "pay_type_dis" : pay_type_dis,
        "govtax" : govtax,
        "govtax_dis" : govtax_dis,
        "vou_remark" : vou_remark,
        },

        success:function(data){
    // $('#voudiscount').modal('show');
    if(data.error){
                swal({
                title: "Failed!",
                text : "Something Wrong!",
                icon : "error",
            });
            }
            else{


            var url = '{{ route("shop_order_voucher", ":order_id") }}';

            url = url.replace(':order_id', data.id);

            setTimeout(function(){

                window.location.href= url;

            }, 1000);
            }
        }
        })

    }
  else{
    swal({
                title: "Failed!",
                text : "Your Pay Amount is less than Voucher Total!",
                icon : "error",
            });
  }

}


    function storeVoucher(order_id, price){
        //
        $.ajax({

            type:'POST',

            url:'/DiscountForm',

            data:{
            "_token":"{{csrf_token()}}",
            "order_id":order_id,
            },

            success:function(data){
                // $('#voudiscount').modal('show');
                $('#hid_order_id').val(order_id);
                $('#dis_type').val();
                $('#dis_val').val();
                if(price == 0){
                    // alert('hi');
                    $('#voucher_total_dis').val(data.vtot);
                    $('#voucher_total').val(data.vtot);
                    $('#curr_voucher_total1').val(data.stot);
                    $('#curr_voucher_total').val(data.stot);
                    $('#bd_exit').val(data.bd);
                }else{
                    // alert('fail')
                    $('#voucher_total_dis').val(price);
                    $('#voucher_total').val(price);
                    $('#curr_voucher_total1').val(price);
                    $('#curr_voucher_total').val(price);
                    $('#extra_gram_display').hide();

                }
            }
        })
        $('#dis_radio_form').modal('show');
        $('#dis_voucher_total').hide();
        $('#dis_pay_amount').hide();
        $('#dis_change_amount').hide();
        $('#dis_govtax').hide();
        $('#promotion').hide();
        $('#promotion_name').hide();
        $('#dis_footer').hide();
        $('#curr_extra_total').hide();
        $('#extra_gram').hide();
        $('#extra_amt').hide();
        //
    }

    function done(table_id){
     $.ajax({

            type:'POST',

            url:'/waiterdone',

            data:{
            "_token":"{{csrf_token()}}",
            "table_id":table_id,
            },

            success:function(data){
            swal({
                title: "Success!",
                text : "Successfully Pay Amount!",
                icon : "success",
            });

            }
            })
    }

    function promotionchange(id){
        let order = $('#hid_order_id').val();
        $.ajax({

        type:'POST',

        url:'/PromotionCheck',

        data:{
        "_token":"{{csrf_token()}}",
        "promotion_id":id,
        "order_id": order,
        },

        success:function(data){
            let html = '';
           if(data.promotion.length == 0){
            $('#ispromotion').html('<span class="text-danger offset-3">This promotion is expired.</span>')
           }else{
             if(data.promotion.type == 1){
                var vtotal = $('#voucher_total_dis').val();
                if(data.promotion.voucher_amount <= vtotal){
                if(data.promotion.reward == 1){
                    html += `<span class="text-success text-center offset-1">Cash Back : ${data.promotion.amount}</span>`;
                    $('#ispromotion').html(html);
                }else if(data.promotion.reward == 2){
                    html += `<span class="text-success text-center offset-1">FOC Items : ${data.promotion.foc_items}</span>`;
                    $('#ispromotion').html(html);
                }
               else{
                    html += `<span class="text-success text-center offset-1">Discount Percentage : ${data.promotion.percent} %</span>`;
                    $('#ispromotion').html(html);
                }
            }
            else{
                $('#ispromotion').html('<span class="text-danger offset-3">This voucher amount is less than promotion amount.</span>');
            }
             }

           }
        }
        })

    }


</script>


@endsection
