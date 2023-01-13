@extends('master')

@section('title','Purchase Details')

@section('place')

@endsection

@section('content')

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">        
        <h4 class="font-weight-normal">Daily Purchase Detail</h4>
    </div>
</div>

<div class="row">
    <!-- <div class="col-md-14"> -->
        <div class="card shadow" style="width: 100%; height: 100%;">
            <div class="card-header">
                <h4 class="font-weight-bold mt-2">Daily Purchase Detail</h4>
            </div>
            <div class="card-body">
           	           	
            	<div class="row">
            		<div class="col-md-6">

            			<div class="row">				           
			              	<div class="font-weight-bold text-primary col-md-5">Purchase Date</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">
			              		{{date('d-m-Y', strtotime($purchase->created_at))}}
			              	</h5>
				        </div> 

				        <div class="row mt-1">				           
			              	<div class="font-weight-bold text-primary col-md-5">Total Price</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{($purchase->price)}} ကျပ်</h5>
				        </div> 

				        <div class="row mt-1">				           
			              	<div class="font-weight-bold text-primary col-md-5">Total Quantity</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{$purchase->total_quantity}}</h5>
				        </div> 
				

            		</div>
					
            		<div class="col-md-12" style="margin-left:auto;margin-right:auto;">
            			<h4 class="font-weight-bold mt-2 text-primary text-center">Purchase Unit's List</h4>
            			<div class="table-responsive text-black">
		                    <table class="table" id="example23" >
		                        <thead>
		                            <tr>
		                                <th >#</th>
		                                <th>Item Name</th>
										<th>Purchase Number</th>
		                                <th>Purchase Quantity</th>
		                                <th>Purchase Price</th>
		                                <th>Sub Total</th>
		                            </tr>
		                        </thead>
		                        <tbody id="units_table">
		                               @foreach($items as $index=>$unit)
								
		                                <tr>
		                                    
		                                    <td>{{$index+1}}</td>
		                                	<td>{{$unit->name}}</td>
											<td>{{$unit->purchase_no}}</td>
                                            <td>{{$unit->stock_quantity}}</td>
											<td>{{$unit->price}}</td>
		                                	<td>{{$unit->stock_quantity * $unit->price}}</td>
		                                </tr>                                   
		                            @endforeach
		                        </tbody>
		                    </table>
		                </div>
            		</div>
            		
            		

            	</div> 
            		</div>
            		
            </div>
        <!-- </div> -->
    </div>
</div>

@endsection
