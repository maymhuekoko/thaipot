@extends('master')

@section('title','Shop Order Details')

@section('place')

<!--<div class="col-md-5 col-8 align-self-center">-->
<!--<h3 class="text-themecolor m-b-0 m-t-0">Pending Order Details</h3>-->
<!--<ol class="breadcrumb">-->
<!--<li class="breadcrumb-item"><a href="{{route('index')}}">Back to Dashborad</a></li>-->
<!--<li class="breadcrumb-item active">Pending Order Details</li>-->
<!--</ol>-->
<!--</div>-->

@endsection


@section('content')

<?php $user = session()->get('user')->role_flag;?>
<div class="row">
	<div class="col-md-12">
		<div class="card shadow">
		    <div class="card-header">
		        <h4 class="font-weight-bold mt-2">Pending Order Details</h4>
		    </div>
		    <div class="card-body">

		    	<div class="row">

		    		<div class="col-md-4">
		    			<div class="row">
			              	<div class="font-weight-bold text-primary col-md-6 offset-md-1">Order Number</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{$pending_order_details->order_number}}</h5>
				        </div>

				        <div class="row mt-1">
			              	<div class="font-weight-bold text-primary col-md-6 offset-md-1">Table Name</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{$pending_order_details->table->table_number??"Take Away"}}</h5>
				        </div>
		    		</div>

		    		<div class="col-md-8">
		    			<h4 class="font-weight-bold mt-2 text-primary text-center">Pending Order Detail</h4>
		    			<div class="table-responsive">
		                    <table class="table">
		                        <thead>
		                            <tr>
		                                <th>Name</th>
		                                <th>Quantity</th>
		                                <th>Price</th>
										<th>Sub Total Price</th>

		                            </tr>
		                        </thead>
		                        <tbody>
                                    @foreach($item_shop_orders as $item)
                                        <tr>
                                            @foreach($menu_items as $menu)
                                                @if($menu->id == $item->item_id)
                                                    <td>{{$menu->item_name}}</td>
                                                @endif
                                            @endforeach
                                            <td>{{$item->quantity}}</td>
                                            <td>{{$item->price}}</td>
                                            <td>{{$item->price * $item->quantity}}</td>
                                        </tr>
                                    @endforeach
		                        </tbody>
		                    </table>
		                </div>
		    		</div>

		    	</div>
		    </div>
		</div>
	</div>
</div>

<div class="row justify-content-center">
    @if($pending_order_details->table_id != null)
	    <a href="/Add-More/take_away/{{$pending_order_details->id}}" class="btn btn-info text-center">Extra Meal</a>
    @else
        <a href="/take_away" class="btn btn-info text-center">Extra Meal</a>
    @endif
    @if($user != 3)
    {{-- <a href="#" class="btn  ml-2" style="background-color:lightgreen;color:white;" onclick="done({{$pending_order_details->id}})">Done</a>

    @else --}}
    <a href="#" class="btn btn-info ml-2" style="color:white;" onclick="storeVoucher('{{$pending_order_details->id}}')">Store Voucher</a>
    @endif

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
                    <input type="text" class="form-control"  value="20900">
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
                <div class="form-group mt-3">
                    <label class="font-weight-bold">Change</label>
                    <input type="text" class="form-control" readonly id="change_amount1" value="">
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
            <div class="form-group mt-3" id="dis_pay_amount">
                <label class="font-weight-bold">Pay Amount</label>
                <input type="text" class="form-control"  value="" id="pay_amount_dis" placeholder="Enter Pay Amount" onkeyup="pay_dis(this.value)">
            </div>
            <div class="form-group mt-3" id="dis_change_amount">
                <label class="font-weight-bold">Change</label>
                <input type="text" class="form-control" readonly id="change_amount_dis" value="">
            </div>
            <div class="row">
                <!-- <div class="col-3">
                    <div class="form-group mt-3" id="promotion">
                        <label class="control-label">Promotion</label>
                           <div class="switch">
                               <label>OFF
                               <input type="checkbox"  name="customer_console" id="console" onchange="promotion_on()"><span class="lever"></span>ON</label>
                            </div>
                   </div>
                </div> -->
                <div class="col-9">
                    <div class="form-group mt-3" id="promotion_name">
                        <label class="font-weight-bold">Choose Promotion</label>
                        <select class="form-control" name="purchaseitem" onchange="promotionchange(this.value)">
                            <option value="" hidden>Select Promotion</option>
                            @foreach ($promotion as $p)
                            <option value="{{$p->id}}">{{$p->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="ispromotion">

            </div>

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
       $('#promotion').show();
       $('#dis_footer').show();
       $('#curr_extra_total').show();
       $('#extra_gram').show();
       $('#extra_amt').show();
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
       var dis_value = $('#curr_voucher_total1').val(parseInt($('#curr_voucher_total1').val()) -20900);
       $('#dis_type').val(1);
       $('#dis_val').val(0);
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
    var change_value = $('#change_amount').val();
    var pay_value_dis = $('#pay_amount_dis').val();
    var change_value_dis = $('#change_amount_dis').val();
    var ispromotion = $('#ispromotion').text();
    if($('#console').prop("checked") == true){
         var console = 1;
        if(ispromotion == 'This promotion is expired.' || ispromotion == 'This voucher amount is less than promotion amount.'){
          var promotion = 0;
          var promotion_value = 0;
        }else{
          var p = ispromotion.split(":");
          var promotion = p[0];
          var promotion_value = p[1];
        }
    }else{
       var console = 0;
       var promotion = 0;
       var promotion_value = 0;
    }

        $.ajax({

        type:'POST',

        url:'/TakeAwayVoucherStore',

        data:{
        "_token":"{{csrf_token()}}",
        "order_id":order_id,
        "discount_type" : discount_type ,
        "discount_value" : discount_value,
        "pay_amount" : pay_value,
        "change_amount" : change_value,
        "pay_amount_dis" : pay_value_dis,
        "change_amount_dis" : change_value_dis,
        "customer_console" : console,
        "promotion" : promotion,
        "promotionvalue" : promotion_value,
        },

        success:function(data){
            var url = '{{ route("take_away_order_voucher", ":order_id") }}';

            url = url.replace(':order_id', data.id);

            setTimeout(function(){

                window.location.href= url;

            }, 1000);
            // alert(data);
            if(data.error){
                swal({
                title: "Failed!",
                text : "Something Wrong!",
                icon : "error",
            });
            
            }
            
        }

    });


}



       function storeVoucher(order_id, price){
           //
           $.ajax({

            type:'POST',

            url:'/TakeAwayDiscountForm',

            data:{
            "_token":"{{csrf_token()}}",
            "order_id":order_id,
            },

            success:function(data){
                // $('#voudiscount').modal('show');
                $('#hid_order_id').val(order_id);
                $('#dis_type').val();
                $('#dis_val').val();
                $('#voucher_total_dis').val(data);
                $('#voucher_total').val(data);
            }
            })
           $('#dis_radio_form').modal('show');
           $('#dis_voucher_total').hide();
           $('#dis_pay_amount').hide();
           $('#dis_change_amount').hide();
           $('#promotion').hide();
           $('#promotion_name').hide();
           $('#dis_footer').hide();
           $('#curr_extra_total').hide();
           $('#extra_gram').hide();
           $('#extra_amt').hide();
           //
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
