<?php

namespace App\Http\Controllers\Web;

use App\Code;
use App\Meal;
use App\Town;
use Datetime;
use App\Order;
use App\Table;
use App\Option;
use App\Voucher;
use App\MenuItem;
use App\Promotion;
use App\ShopOrder;
use App\TableType;
use App\Ingredient;
use App\CuisineType;
use Dotenv\Result\Success;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\TakeAwayOrder;
use App\TotalConsumption;
use App\TotalPurchase;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SaleController extends Controller
{
	protected function getShopOrderPanel(){

		return view('Sale.shop_order_panel');
	}

    protected function getQrcodePage(){
        return view('Sale.qrcode');
    }

	protected function getSalePage(){

		$table_lists = Table::orderBy('table_type_id', 'ASC')->get();
        $table4n1 = Table::where('table_type_id', 2)->skip(0)->take(6)->get();
        $table4n2 = Table::where('table_type_id', 2)->skip(6)->take(6)->get();

		$table_types = TableType::all();

		return view('Sale.sale_page', compact('table_lists','table4n1','table4n2','table_types'));
	}

	protected function getPendingShopOrderList(){

		$pending_lists = ShopOrder::where('status', 1)->where('take_away_flag', 0)->latest()->get();

        $promotion = Promotion::all();

		return view('Sale.pending_lists', compact('pending_lists','promotion'));
	}

    protected function getPendingTakeAwayOrderList(){

		$pending_lists = ShopOrder::where('status', 1)->where('take_away_flag', 1)->latest()->get();

        $promotion = Promotion::all();

		return view('Sale.take_away_pending_lists', compact('pending_lists','promotion'));
	}

    protected function notification(Request $request){
        $shop_lists = ShopOrder::where('status', 1)->get();
        $deli_lists = Order::where('status', 2)->get();
        return response()->json([
            'shop' => $shop_lists,
            'deli' => $deli_lists
        ],200);
    }

	protected function gotopendinglists(){

		$pending_lists = ShopOrder::where('status', 1)->where('take_away_flag', 0)->latest()->get();
        $promotion = Promotion::all();

		return view('Sale.pending_lists', compact('pending_lists','promotion'));
	}

	protected function getPendingShopOrderDetails($order_id){
		$table_number = 0;
		try {

			$pending_order_details = ShopOrder::findOrFail($order_id);

		} catch (\Exception $e) {

        	alert()->error("Pending Order Not Found!")->persistent("Close!");

            return redirect()->back();
    	}
        //   dd($pending_order_details);
    	return view('Sale.pending_order_details', compact('pending_order_details','table_number'));

	}

	protected function getPendingTakeAwayDetails($order_id){
		$table_number = 0;
		try {

		$pending_order_details = ShopOrder::findOrFail($order_id);

		if($pending_order_details->take_away_flag == 1){
			$item_shop_orders = DB::table('item_shop_order')->where('shop_order_id', $order_id)->get();
		}

		$menu_items = MenuItem::all();

		$promotion = Promotion::all();
            // dd($pending_order_details->option);
		} catch (\Exception $e) {

        	alert()->error("Pending Order Not Found!")->persistent("Close!");

            return redirect()->back();
    	}
        //   dd($pending_order_details);
    	return view('Sale.pending_take_away_details', compact('pending_order_details','table_number', 'item_shop_orders', 'menu_items', 'promotion'));
	}

    protected function getPendingDeliveryOrderDetails($order_id){
		$table_number = 0;
		try {

		$pending_order_details = Order::findOrFail($order_id);

		} catch (\Exception $e) {

        	alert()->error("Pending Order Not Found!")->persistent("Close!");

            return redirect()->back();
    	}

    	$total_qty = 0 ;

    	$total_price = 0 ;

    	foreach ($pending_order_details->option as $option) {

			$total_qty += $option->pivot->quantity;

			$total_price += $option->sale_price * $option->pivot->quantity;
		}

    	return view('Sale.delivery_pending_order_details', compact('pending_order_details','total_qty','total_price','table_number'));
	}

	protected function getShopDeli(Request $request){

		$deliID = $request->delid;


	}
	protected function takeAwayPage(){
		$table_number = 0;
		$table = 0;
		$items = MenuItem::all();
        // dd($items);

		$myUrl = explode('/', url()->current());
		$currentUrl = ucwords($myUrl[count($myUrl)-1]);
		$currentUrl = str_replace('_', ' ', $currentUrl);

		$meal_types = Meal::where('name', $currentUrl)->first();
        $codes =Code::all();

		$cuisine_types = CuisineType::where('meal_id', $meal_types->id)->with('items')->get();

		$ygn_towns = Town::where('state_id',17)->get();
		// dd($cuisine_types);
		return view('Sale.take_away_sale_page', compact('ygn_towns','codes','items','meal_types','cuisine_types','table_number','table'));
	}

	protected function takeAwayPage1($id){
		$table_number = 0;
		$table = 0;
		$items = MenuItem::all();
        // dd($items);

		$myUrl = explode('/', url()->current());
		$currentUrl = ucwords($myUrl[count($myUrl)-2]);
		$currentUrl = str_replace('_', ' ', $currentUrl);

		$tableId = $myUrl[count($myUrl)- 1];

		$meal_types = Meal::where('name', $currentUrl)->first();
        $codes =Code::all();

		$cuisine_types = CuisineType::where('meal_id', $meal_types->id)->get();

		$ygn_towns = Town::where('state_id',17)->get();
		return view('Sale.take_away_sale_page', compact('tableId', 'ygn_towns','codes','items','meal_types','cuisine_types','table_number','table'));
	}

	protected function searchDelicharges(Request $request){
		$deli_pay = Town::find($request->town_id);

		return response()->json($deli_pay);
	}
	protected function getShopOrderSalePage($table_id){

		$items = MenuItem::all();

        // dd($items);

		$meal_types = Meal::all();

        // dd($meal_types);

        $codes = Code::all();

		$cuisine_types = CuisineType::all();

		if ($table_id == 0) {

			$table_number = 0;

		} else {

			$order = ShopOrder::where('table_id', $table_id)->where('status', 1)->first();

			if(!empty($order)){
				// dd("hello");
				return redirect()->route('pending_order_details',$order->id);

			}else{
				// dd("hello2");
				$table = Table::where('id', $table_id)->first();

				$table_number = $table->id;

			}
		}
		$table = 1;
		$ygn_towns = Town::where('state_id',13)->get();
		$cuisine_types = CuisineType::all();
		$table_id = $order->table_id;

		return view('Sale.order_sale_page', compact('table_id', 'ygn_towns','cuisine_types','codes','items','meal_types','table','cuisine_types','table_number'));
	}

	protected function getCountingUnitsByItemId(Request $request){

		$item_id = $request->item_id;

        $item = MenuItem::where('id', $item_id)->first();

        $units = Option::where('menu_item_id', $item->id)->with('menu_item')->get();

        return response()->json($units);
	}

	protected function getItemById(Request $request){
		$item = MenuItem::where('id', $request->item_id)->first();

		return response()->json($item);
	}

    protected function save_note(Request $request){
        $notes = $request->note_id;
        // dd($notes);
        return response()->json([
            'noteId' => $notes,
        ]);
    }

    //Start store thai
    protected function storeThaiShopOrder(Request $request){
        // return response()->json(QrCode::size(150)->generate('hello'));
        $validator = Validator::make($request->all(), [
			'table_id' => 'required',
            'start_time' => 'required',
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong! Validation Error.');

            return redirect()->back();
		}

        $user_name =  session()->get('user')->name;

        try {
            $myArray = explode(':', $request->start_time);
            if($myArray[0]>12){
                $start_time = $myArray[0]-12 . ':' .$myArray[1] . 'PM';
            }else{
                $start_time = $request->start_time . 'AM';
            }
            if($myArray[0] +2>12){
                $end_time = $myArray[0]+2-12 .':'.$myArray[1] . 'PM';
            }else{
                $end_time = $myArray[0]+2 .':'.$myArray[1] . 'AM';
            }


            // dd($table);
				// if (empty($table)) {
                    // if($is_desktop == true || $is_mobile == true){
                        // dd($request->all());
            if($request->adult_qty != 0){
					$order = ShopOrder::create([
		                'table_id' => $request->table_id,
		                'status' => 1,
                        'is_mobile'=> 1,
						'sale_by' =>$user_name,
                        'adult_qty' => $request->adult_qty,
                        'child_qty' => $request->child_qty,
                        'kid_qty' => $request->kid_qty,
                        'birth_qty' => $request->birth_qty,
                        'extrapot_qty' => $request->extrapot_qty,
                        'soup_name' => $request->soup_name,
                        'remark' => $request->remark,
								// Order Status = 1
		            ]);
                }
                // }
		            $order->order_number = "ORD-".sprintf("%04s", $order->id);

		            $order->save();

                    $table = Table::where('id', $request->table_id)->first();
                    $table->start_time = $start_time;
                    $table->end_time = $end_time;
                    $table->status = 2;
                    $table->save();

				// }

		} catch (Exception $e) {

			alert()->error("Something Wrong! When Store Shop Order");

			response()->json('wrong');

		}

      	alert()->success('Successfully Store Shop Order');

        $fromadd = 0;
        $tablenoo = 0;
        $date = new DateTime('Asia/Yangon');

        $real_date = $date->format('d-m-Y h:i:s');

        return response()->json($order);

		// return view('Sale.pending_lists', compact('pending_lists','promotion'));

	}

    //end

	protected function storeShopOrder(Request $request){

		// dd($request->all());
		$validator = Validator::make($request->all(), [
			// 'table_id' => 'required',
			'option_lists' => 'required',
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong! Validation Error.');

            return redirect()->back();
		}
		 $user_name =  session()->get('user')->name;
		//  dd($user_name);
		$take_away = $request->take_away;

		$table_number = $request->table_exists;

		$option_lists = json_decode($request->option_lists);
		// $agent = new \Jenssegers\Agent\Agent;
		// $is_mobile = $agent->isMobile();
        // $is_desktop = $agent->isDesktop();
		try {
			// dd($is_mobile,$is_desktop);

			if($table_number != 0){
				$table = Table::where('id', $request->table_id)->first();
			}
			$card_total = 0;

			if (empty($table)) {
                    // if($is_desktop == true || $is_mobile == true){
					$order = ShopOrder::create([
		                // 'table_id' => $request->table_id,
		                'status' => 1,
                        'is_mobile'=> 1,
						'take_away_flag'=>$take_away,
						'sale_by' =>$user_name,
										// Order Status = 1
		            ]);
                // }
					if($take_away == 1){
						foreach(json_decode($request->price) as $card){
							$card_total += $card->order_qty * $card->selling_price;
						}
						$order->price = $card_total;
					}
					if($table_number != 0){
						$order->table_id = $table_number;
					}
		            $order->order_number = "ORD-".sprintf("%04s", $order->id);

		            $order->save();

		            foreach ($option_lists as $option) {
						DB::table('item_shop_order')->insert([
							"shop_order_id" => $order->id,
							"item_id" => $option->id,
							"quantity" => $option->order_qty,
							"price" => $option->selling_price
						]);
					}

				} else {

					if ($table->status == 2) {

						alert()->error('Something Wrong! Table is not available.');

	            		return redirect()->back();

					} else {

						$table->status = 2;

						$table->save();
                        // if($is_desktop == true || $is_mobile == true){
						$order = ShopOrder::create([
			                // 'table_id' => $request->table_id,
			                'status' => 1, 										// Order Status = 1
			                 'type' => 1,
							 'is_mobile'=> 1,
							 'take_away_flag'=>$take_away,
							 'sale_by' =>$user_name,
			            ]);
                    // }
			            $order->order_number = "ORD-".sprintf("%04s", $order->id);

			            $order->save();

			            // foreach ($option_lists as $option) {

						// 	$order->option()->attach($option->id, ['quantity' => $option->order_qty,'note' => null,'status' => 7]);
						// }
					}
				}

		} catch (Exception $e) {

			alert()->error("Something Wrong! When Store Shop Order");

			return redirect()->back();
		}

      	alert()->success('Successfully Store Shop Order');
        //   $allow_print = true;
		$orders = ShopOrder::find($order->id);
		// dd($orders->option()->price);
		$tableno = Table::find($orders->table_id);


		$take_away = $request->take_away;
		$fromadd = 1;
		$tablenoo = 0;
		$date = new DateTime('Asia/Yangon');

	  $real_date = $date->format('d-m-Y h:i:s');
	  $code_lists = json_decode($request->code_lists);
	  $notte = [];
	  if($code_lists != null){
	  foreach($code_lists as $code){
		  $remark_note = DB::table('item_shop_order')
						  ->where('item_id',$code->id)
						  ->update(['note' => $code->remark]);
		  $note_remark = DB::table('item_shop_order')
						  ->where('item_id',$code->id)
						  ->first();
		  array_push($notte,$note_remark);
	  }
	  }
	  $kit = DB::table('item_shop_order')
				  ->where('shop_order_id',$request->order_id)
				  ->get();
		  return view('Sale.take_away_kitchen_lists',compact('table_number', 'take_away','notte','orders','tableno','real_date','fromadd','tablenoo', 'option_lists', 'code_lists'));
	}

	public function toKitchenAddMore($id)
	{

		$orders = ShopOrder::find($id);
		$tableno = Table::find($orders->table_id);
		$alloption = Option::all();
		$option_name = DB::table('option_shop_order')
		->where('shop_order_id',$orders->id)
		->where('tocook',1)
		->get();
		$name = [];
		foreach($option_name as $optionss)
		{
		$oname = Option::find($optionss->option_id);
		array_push($name,$oname);

		}

		$fromadd = 1;
		$tablenoo = 0;
		$date = new DateTime('Asia/Yangon');

	  $real_date = $date->format('d-m-Y h:i:s');
	  return view('Sale.kitchen_lists',compact('option_name','name','tableno','fromadd','tablenoo','real_date'));

	}

	public function toKitchenVoucher($id)
	{
		$orders = ShopOrder::find($id);
		$tableno = Table::find($orders->table_id);
		$alloption = Option::all();
		$option_name = DB::table('option_shop_order')
		->where('shop_order_id',$orders->id)
		->get();
		$name = [];
		foreach($option_name as $optionss)
		{
		$oname = Option::find($optionss->option_id);
		array_push($name,$oname);
		}

			$fromadd = 0;
			$tablenoo = 0;
			$date = new DateTime('Asia/Yangon');

			$real_date = $date->format('d-m-Y h:i:s');

			return view('Sale.kitchen_lists',compact('take_away','kit','notte','tableno','fromadd','tablenoo','real_date','option_lists','code_lists'));

	}
	protected function addMoreItemUI($order_id){  //Finished UI
		$table = 1;
		try {

        	$order = ShopOrder::findOrFail($order_id);

   		} catch (\Exception $e) {

        	alert()->error("Shop Order Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

    	$items = MenuItem::all();

		$meal_types = Meal::where('name', '<>', 'Take away')->get();

		$meal_ids = $meal_types->pluck('id');

        $codes = Code::all();

		// $cuisine_types = CuisineType::all();
		$cuisine_types = DB::table('cuisine_types')->whereIn('meal_id', $meal_ids)->get();

		$table_number = $order->table->table_number??0;

		$table_id = $order->table_id;
		// dd($table);
		return view('Sale.order_sale_page', compact('table_id', 'codes','items','meal_types','cuisine_types','table_number','order','table'));
	}
    protected function deliaddMoreItemUI($order_id){  //Finished UI
		$table = 2;
		try {

        	$order = Order::findOrFail($order_id);

   		} catch (\Exception $e) {

        	alert()->error("Order Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

    	$items = MenuItem::all();

		$meal_types = Meal::all();

        $codes = Code::all();

		$cuisine_types = CuisineType::all();

		 DB::table('option_shop_order')
		->where('shop_order_id', $order_id)
		->update(['tocook' => 0]);

		$table_number = $order->table->table_number??0;
		$table_id = $order->table_id;
		// dd($table);
        // dd($table_number);
		return view('Sale.order_sale_page', compact('table_id', 'codes','items','meal_types','cuisine_types','table_number','order','table'));
	}

	protected function addMoreItem(Request $request){ //Unfinished
            // dd($request->all());
		$validator = Validator::make($request->all(), [
			'order_id' => 'required',
			'option_lists' => 'required',
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong! Validation Error.');
            return redirect()->back();

		}

		$option_lists = json_decode($request->option_lists);

		try {

			$shop_order = ShopOrder::findOrFail($request->order_id);

		} catch (\Exception $e) {

			alert()->error('Something Wrong! Shop Order Cannot be Found.');

            return redirect()->back();
		}

		if ($shop_order->status == 1) {



		    $shop_order->type=1;
		    $shop_order->save();

			alert()->success('Successfully Added');

      		// return redirect()->route('sale_page');

			  $orders = ShopOrder::find($request->order_id);
			  $tableno = Table::find($orders->table_id);
              if($option_lists != null){
                foreach($option_lists as $option){
                    $items = DB::table('item_shop_order')->insert([
                        'item_id' => $option->id,
                        'shop_order_id' => $request->order_id,
                        'quantity' => $option->order_qty,
                    ]);
                }
            }

			  $take_away = $request->take_away;
			  $fromadd = 1;
			  $tablenoo = 0;
			  $date = new DateTime('Asia/Yangon');

        	$real_date = $date->format('d-m-Y h:i:s');
			$code_lists = json_decode($request->code_lists);
			$notte = [];
			if($code_lists != null){
			foreach($code_lists as $code){
				$remark_note = DB::table('item_shop_order')
								->where('item_id',$code->id)
								->update(['note' => $code->remark]);
				$note_remark = DB::table('item_shop_order')
								->where('item_id',$code->id)
								->first();
				array_push($notte,$note_remark);
			}
			}
            $kit = DB::table('item_shop_order')
                        ->where('shop_order_id',$request->order_id)
                        ->get();
			return view('Sale.kitchen_lists',compact('take_away','kit','notte','tableno','fromadd','tablenoo','real_date','option_lists','code_lists'));

		} else {

			alert()->error('Something Wrong! Shop Order is colsed.');

            return redirect()->back();
		}
	}

    //soup kitchen
    protected function soupkitchen(Request $request){
        $validator = Validator::make($request->all(), [
			'order_id' => 'required',
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong! Validation Error.');
            return redirect()->back();

		}

		try {

			$shop_order = ShopOrder::findOrFail($request->order_id);

		} catch (\Exception $e) {

			alert()->error('Something Wrong! Shop Order Cannot be Found.');

            return redirect()->back();
		}

		if ($shop_order->status == 1) {

		    $shop_order->type=1;
		    $shop_order->save();

			alert()->success('Successfully Added');

      		// return redirect()->route('sale_page');

			  $orders = ShopOrder::find($request->order_id);
			  $tableno = Table::find($orders->table_id);


			  $fromadd = 1;
			  $tablenoo = 0;
			  $date = new DateTime('Asia/Yangon');

        	$real_date = $date->format('d-m-Y h:i:s');

			return view('Sale.kitchen_soup',compact('tableno','fromadd','tablenoo','real_date','shop_order'));

		} else {

			alert()->error('Something Wrong! Shop Order is colsed.');

            return redirect()->back();
		}
    }

    protected function deliaddMoreItem(Request $request){ //Unfinished
        // dd($request->all());
		$validator = Validator::make($request->all(), [
			'deli_order_id' => 'required',
			'deli_option_lists' => 'required',
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong! Validation Error.');

            return redirect()->back();
		}

		$option_lists = json_decode($request->deli_option_lists);


		try {

			$shop_order = Order::findOrFail($request->deli_order_id);

		} catch (\Exception $e) {

			alert()->error('Something Wrong! Shop Order Cannot be Found.');

            return redirect()->back();
		}

		if ($shop_order->status == 2) {

			foreach ($option_lists as $option) {

				$test = $shop_order->option()->where('option_id', $option->id)->first();

				if (empty($test)) {

					$shop_order->option()->attach($option->id, ['quantity' => $option->order_qty,'tocook'=>1,'note' => "Note Default", 'status' => 0]);

				} else {

					$update_qty = $option->order_qty + $test->pivot->quantity;

					$shop_order->option()->updateExistingPivot($option->id, ['quantity' => $update_qty,'tocook'=>1,'add_same_item_status'=>1,'old_quantity'=>$test->pivot->quantity,'new_quantity'=>$option->order_qty] );

				}

			}

		    // $shop_order->type=1;
		    // $shop_order->save();

			alert()->success('Successfully Added');

      		// return redirect()->route('sale_page');

			  $orders = Order::find($request->deli_order_id);
			//   $tableno = Table::find($orders->table_id);
			  $alloption = Option::all();
			  $option_name = DB::table('option_order')
			  ->where('order_id',$orders->id)
			  ->where('tocook',1)
			  ->get();
			  $name = [];
			  foreach($option_name as $optionss)
			  {
			  $oname = Option::find($optionss->option_id);
			  array_push($name,$oname);

			  }

			  $fromadd = 1;
			  $tablenoo = 1;
			  $date = new DateTime('Asia/Yangon');

        	$real_date = $date->format('d-m-Y h:i:s');
			$code_lists = json_decode($request->code_lists);
			$notte = [];
			if($code_lists != null){
			foreach($code_lists as $code){
				$remark_note = DB::table('option_order')
								->where('option_id',$code->id)
								->update(['note' => $code->remark]);
				$note_remark = DB::table('option_order')
								->where('option_id',$code->id)
								->first();
					array_push($notte,$note_remark);
			}
			}
			return view('Sale.kitchen_lists',compact('notte','option_name','name','tableno','fromadd','tablenoo','real_date'));

		} else {

			alert()->error('Something Wrong! Shop Order is colsed.');

            return redirect()->back();
		}
	}

	protected function storeShopOrderVoucher(Request $request){

		// dd($request->all());
		try {

			$shop_order = ShopOrder::where('id',$request->order_id)->where('status','1')->first();

			if(empty($shop_order)){

				return response()->json(['error' => 'Something Wrong! Cannot Checkbill again']);

			}

		} catch (\Exception $e) {

			return response()->json(['error' => 'Something Wrong! Shop Order Cannot Be Found'], 404);

		}



		$table = Table::where('id', $shop_order->table_id)->first();

		if (!empty($table)) {

			$table->status = 1;
            $table->start_time = '00:00';
            $table->end_time = '00:00';
    		$table->save();

		}

		$user_code = $request->session()->get('user')->name;

		$date = new DateTime('Asia/Yangon');

		$real_date = $date->format('Y-m-d H:i:s');

        $re_date = $date->format('Y-m-d');

		$tota = $shop_order->adult_qty * 21900 + $shop_order->child_qty * 11550 + $shop_order->kid_qty * 9450 + $shop_order->extrapot_qty * 3000 + $request->extragram1;
        $tser = $tota * 0.05;
        $total  = ($tota + $tser) - ($shop_order->birth_qty * 4600);
        //  dd($request->change_amount_dis);
        $total_qty =  $shop_order->adult_qty + $shop_order->child_qty  + $shop_order->kid_qty ;

        $voucher = Voucher::create([
            'sale_by' => $user_code,
            'total_price' =>  $total,
            'total_quantity' => $total_qty,
            'voucher_date' => $real_date,
            'type' => 1,
            'status' => 0,
            'date' => $re_date,
        ]);
        if($request->discount_type !=null && $request->discount_value != null){
            $voucher->discount_type = $request->discount_type;
            $voucher->discount_value = $request->discount_value;
            $voucher->pay_value = $request->pay_amount;
            $voucher->change_value = $request->change_amount;
            $voucher->pay_type = $request->pay_type;
            $voucher->govtax = $request->govtax;
            $voucher->govtax_amount = $request->govtax_amt1;
            $voucher->remark = $request->vou_remark;
        }else{
            $voucher->pay_value = $request->pay_amount_dis;
            $voucher->change_value = $request->change_amount_dis;
            $voucher->pay_type = $request->pay_type_dis;
            $voucher->govtax = $request->govtax_dis;
            $voucher->govtax_amount = $request->govtax_amt;
            if($shop_order->birth_qty != 0){
                $voucher->discount_value = $shop_order->birth_qty * 4600;
            }
        }
        if($request->extragram == 0){
            $voucher->extra_gram = $request->extragram1;
            $voucher->extra_amount = $request->extraamt1;
        }
        if($request->extragram1 == 0){
            $voucher->extra_gram = $request->extragram;
            $voucher->extra_amount = $request->extraamt;
        }


    	$voucher->voucher_code = "VOU-".date('dmY')."-".sprintf("%04s", $voucher->id);

        $voucher->save();

     	$shop_voucher =DB::table('shop_order_voucher')->insert(
            ['shop_order_id' => $shop_order->id, 'voucher_id' => $voucher->id]
        );
    //  dd("Helllo");
            $shop_order->voucher_id = $voucher->id;

            $shop_order->status = 2;

            $shop_order->save();

            return response()->json($shop_order);
    }


	protected function storeTakeAwayVoucher(Request $request){
		// dd($request->all());

		try {

			$shop_order = ShopOrder::where('id',$request->order_id)->where('status','1')->where('take_away_flag', 1)->first();

			if(empty($shop_order)){

				return response()->json(['error' => 'Something Wrong! Cannot Checkbill again']);

			}

		} catch (\Exception $e) {

			return response()->json(['error' => 'Something Wrong! Shop Order Cannot Be Found'], 404);

		}

		$table = Table::where('id', $shop_order->table_id)->first();

		if (!empty($table)) {

			$table->status = 1;

    		$table->save();

		}

		$user_code = $request->session()->get('user')->name;

		$total = 0 ;

		$total_qty = 0 ;

		$date = new DateTime('Asia/Yangon');

		$real_date = $date->format('Y-m-d H:i:s');

        $re_date = $date->format('Y-m-d');

		// foreach ($shop_order->option as $option) {
        //     $total += ($option->pivot->quantity * $option->sale_price);

        //     $total_qty += $option->pivot->quantity;
        // }
        //  dd($request->change_amount_dis);

		$total_qty = 0;

		$quantities = DB::table('item_shop_order')->where('shop_order_id', $shop_order->id)->get();

		foreach($quantities as $qty){
			$total_qty += $qty->quantity;
		}

        $voucher = Voucher::create([
            'sale_by' => $user_code,
            'total_price' =>  $shop_order->price,
            'total_quantity' => $total_qty,
            'voucher_date' => $real_date,
            'type' => 2,
            'status' => 0,
            'date' => $re_date,
        ]);

		DB::table('shop_order_voucher')->insert([
			'shop_order_id' => $request->order_id,
			'voucher_id' => $voucher->id
		]);

        if($request->discount_type !=null && $request->discount_value != null){
            $voucher->discount_type = $request->discount_type;
            $voucher->discount_value = $request->discount_value;
            $voucher->pay_value = $request->pay_amount;
            $voucher->change_value = $request->change_amount;
            $voucher->pay_type = $request->pay_type;
            $voucher->total_price = $voucher->total_price - $request->discount_value;
        }else{
            $voucher->pay_value = $request->pay_amount_dis;
            $voucher->change_value = $request->change_amount_dis;
            $voucher->pay_type = $request->pay_type_dis;
        }
        if($request->promotion !=0 && $request->promotionvalue !=0){
            $voucher->promotion = $request->promotion;
            $voucher->promotion_value = $request->promotionvalue;
        }

    	$voucher->voucher_code = "VOU-".date('dmY')."-".sprintf("%04s", $voucher->id);

        $voucher->save();


            $shop_order->voucher_id = $voucher->id;

            $shop_order->status = 2;

            $shop_order->save();

            return response()->json($shop_order);
    }


    //Delivery Voucher
    protected function storeDeliveryOrderVoucher(Request $request){

		// dd($request->all());

		try {

			$shop_order = Order::where('id',$request->order_id)->first();

			if(empty($shop_order)){

				return response()->json(['error' => 'Something Wrong! Cannot Checkbill again']);

			}

		} catch (\Exception $e) {

			return response()->json(['error' => 'Something Wrong! Shop Order Cannot Be Found'], 404);

		}



		$table = Table::where('id', $shop_order->table_id)->first();

		if (!empty($table)) {

			$table->status = 1;

    		$table->save();

		}

		$user_code = $request->session()->get('user')->name;

		$total = 0 ;

		$total_qty = 0 ;

		$date = new DateTime('Asia/Yangon');

		$real_date = $date->format('Y-m-d H:i:s');

        $re_date = $date->format('Y-m-d');

		foreach ($shop_order->option as $option) {
            $total += ($option->pivot->quantity * $option->sale_price);

            $total_qty += $option->pivot->quantity;
        }

        $voucher = Voucher::create([
            'sale_by' => $user_code,
            'total_price' =>  $total,
            'total_quantity' => $total_qty,
            'voucher_date' => $real_date,
            'type' => 2,
            'status' => 0,
            'date' => $re_date,
        ]);
        if($request->discount_type !=null && $request->discount_value != null){
            $voucher->discount_type = $request->discount_type;
            $voucher->discount_value = $request->discount_value;
            $voucher->pay_value = $request->pay_amount;
            $voucher->change_value = $request->change_amount;
            // $voucher->date = $re_date;
        }
        else{
            $voucher->pay_value = $request->pay_amount_dis;
            $voucher->change_value = $request->change_amount_dis;
            // $voucher->date = $re_date;
        }

    	$voucher->voucher_code = "VOU-".date('dmY')."-".sprintf("%04s", $voucher->id);

        $voucher->save();

     	foreach ($shop_order->option as $option) {

        	$voucher->option()->attach($option->id, ['quantity' => $option->pivot->quantity,'price' => $option->sale_price,'date' => $re_date]);

			$moption = Option::findorFail($option->id);
			// dd($moption->id);
			$amount = DB::table('ingredient_option')

			->where('option_id',$moption->id)
			->get();
			//   dd($amount);
			foreach($amount as $amt)
			$amountt = json_encode($amt->amount);
			// dd($amountt);

			// dd($amountt);
			$ingredien = DB::table('ingredient_option')
			// ->select('ingredient_id')
			->where('option_id',$moption->id)
			->get();
			if($ingredien == null)
			{
			foreach($ingredien as $ingred)
			// dd($ingredien);
			$ingreID = $ingred->ingredient_id;
			// dd($ingreID);

            $ingredient_update = Ingredient::findorFail($ingreID);
			$balance_qty = $ingredient_update->instock_quantity - $amountt;
			$ingredient_update->instock_quantity = $balance_qty;
			// dd("Hello");
			$ingredient_update->save();
			}
            }
    //  dd("Helllo");
            $shop_order->voucher_id = $voucher->id;

            $shop_order->status = 3;

            $shop_order->save();

            return response()->json($shop_order,);
    }

    protected function storeShopDiscountForm(Request $request){
        try {

			$shop_order = ShopOrder::where('id',$request->order_id)->where('status','1')->first();
			$take_away = 0;

			if(empty($shop_order)){

				return response()->json(['error' => 'Something Wrong! Cannot Checkbill again']);

			}

			if($shop_order->table_id == NULL){
				$take_away = 1;
			}

		} catch (\Exception $e) {

			return response()->json(['error' => 'Something Wrong! Shop Order Cannot Be Found'], 404);

		}
        // $total = 0 ;

		$total_qty = 0 ;

        $tota = $shop_order->adult_qty * 21900 + $shop_order->child_qty * 11000 + $shop_order->kid_qty * 9000 + $shop_order->extrapot_qty * 3000;
        $ser = $tota * 0.05;
        $total = $tota + $ser - ($shop_order->birth_qty * 4600);
        $bd = $shop_order->birth_qty * 4600;

        return response()->json([
            'vtot' => $tota,
            'stot' => $total,
			'take_away' => $take_away,
            'bd' => $bd,
        ]);
    }

	protected function storeTakeAwayDiscountForm(Request $request){
		try {
			$shop_order = ShopOrder::where('id',$request->order_id)->where('status','1')->where('take_away_flag', 1)->first();
			if(empty($shop_order)){

				return response()->json(['error' => 'Something Wrong! Cannot Checkbill again']);

			}

		} catch (\Exception $e) {

			return response()->json(['error' => 'Something Wrong! Shop Order Cannot Be Found'], 404);

		}
        return response()->json($shop_order->price);
	}

    // protected function storeDeliveryDiscountForm(Request $request){
    //     try {

	// 		$shop_order = Order::where('id',$request->order_id)->where('status','2')->first();

	// 		if(empty($shop_order)){

	// 			return response()->json(['error' => 'Something Wrong! Cannot Checkbill again']);

	// 		}

	// 	} catch (\Exception $e) {

	// 		return response()->json(['error' => 'Something Wrong! Shop Order Cannot Be Found'], 404);

	// 	}
    //     $total = 0 ;

	// 	$total_qty = 0 ;

	// 	foreach ($shop_order->option as $option) {
    //         $total += ($option->pivot->quantity * $option->sale_price);

    //         $total_qty += $option->pivot->quantity;
    //     }
    //     return response()->json(['total'=>$total,'order'=>$shop_order]);
    // }

	protected function getFinishedOrderList(){

		$order_lists = ShopOrder::where('status', 2)->get();
    	$vouchers = Voucher::with('shopOrder')->latest()->get();

		return view('Sale.finished_lists', compact('order_lists','vouchers'));
	}

    protected function getFilterFinishedOrderList(Request $request){

    	$purchases = TotalPurchase::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

		return response()->json($purchases);
	}

	protected function getFilteredVoucher(Request $request){
		$vouchers = Voucher::whereDate('voucher_date', '>=', $request->start_date)->whereDate('voucher_date', '<=', $request->end_date)->with('shopOrder')->latest()->get();

		return response()->json($vouchers);
	}

	protected function getFilterFinishedConsumptionList(Request $request){
		// $consumptions = TotalConsumption::whereBetween('created_at', [$request->start_date, $request->end_date])->get();
		$consumptions = TotalConsumption::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

		return response()->json($consumptions);
	}

	protected function getShopOrderVoucher($order_id){

		try {

        	$order = ShopOrder::findOrFail($order_id);

   		} catch (\Exception $e) {

        	alert()->error("Shop Order Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

    	$voucher = Voucher::where('id', $order->voucher_id)->first();

        $voutotal = ($order->adult_qty * 21900)+($order->child_qty * 11000)+ ($order->kid_qty * 9000)+ ($order->extrapot_qty *3000) + $voucher->extra_amount;

        $servicecharges =($voutotal/100 * 5);

    	return view('Sale.voucher', compact('voucher','order','voutotal','servicecharges'));
	}

    protected function getTakeAwayOrderVoucher($order_id){

		try {

        	$order = ShopOrder::findOrFail($order_id);

   		} catch (\Exception $e) {

        	alert()->error("Shop Order Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

    	$voucher = Voucher::where('id', $order->voucher_id)->first();

		$items = DB::table('item_shop_order')->where('shop_order_id', $order_id)->get();

		// $names = [];

		// foreach($items as $item){
			$names = MenuItem::all();
		// 	array_push($names , $n);
		// }

    	return view('Sale.take_away_voucher', compact('voucher', 'items', 'names'));

	}

    protected function getDeliveryOrderVoucher($order_id){

		try {

        	$order = Order::findOrFail($order_id);

   		} catch (\Exception $e) {

        	alert()->error("Shop Order Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

    	$voucher = Voucher::where('id', $order->voucher_id)->first();

    	return view('Sale.deli_voucher', compact('voucher','order'));
	}

	protected function getOrderVoucher($order_id){

		try {
			$voucher = Voucher::where('id', $order_id)->first();
   		} catch (\Exception $e) {

        	alert()->error("Order Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

    	return view('Sale.voucher', compact('voucher'));
	}

	protected function searchByCuisine(Request $request){

		$cuisine_id = $request->cuisine_id;

        $item = MenuItem::where('cuisine_type_id', $cuisine_id)->get();

        return response()->json($item);
	}

	protected function getTableByFloor(Request $request){

		$floor = $request->floor_id;

		$table_lists = Table::where('floor', $floor)->get();

		return response()->json($table_lists);
	}

	protected function getTableByTableType(Request $request){

		$floor = $request->floor_id;

		$table_type = $request->table_type;

		$table_lists = Table::where('floor', $floor)->where('table_type_id', $table_type)->get();

		return response()->json($table_lists);
	}

// 	waiterdone

    protected function done(Request $request){
        $table = Table::find($request->table_id);
        $table->status = 3;
        $table->save();
        return response()->json([
            "data" => 'success'
            ],200);
    }
    protected function cancelorder($id){
        $order = ShopOrder::find($id);
        $table = Table::find($order->table_id);
        $table->status = 1;
        $table->save();
        $order->delete();
        DB::table('option_shop_order')->where('shop_order_id',$id)->delete();

        $pending_lists = ShopOrder::where('status', 1)->get();

        return view('Sale.pending_lists',compact('pending_lists'));
    }
    protected function canceldetail(Request $request){
        // dd($request->option_id);
        DB::table('option_shop_order')->where('shop_order_id',$request->order_id)->where('option_id',$request->option_id)->delete();

        alert()->success("Successfully Canceled!")->persistent("Close!");

        $table_number = 0;
		try {

		$pending_order_details = ShopOrder::findOrFail($request->order_id);
            // dd($pending_order_details->option);
		} catch (\Exception $e) {

        	alert()->error("Pending Order Not Found!")->persistent("Close!");

            return redirect()->back();
    	}

    	$total_qty = 0 ;

    	$total_price = 0 ;

    	foreach ($pending_order_details->option as $option) {

			$total_qty += $option->pivot->quantity;

			$total_price += $option->sale_price * $option->pivot->quantity;
		}

    	return view('Sale.pending_order_details', compact('pending_order_details','total_qty','total_price','table_number'));
    }
    protected function canceldelidetail(Request $request){
        // dd($request->option_id);
        DB::table('option_order')->where('order_id',$request->order_id)->where('option_id',$request->option_id)->delete();

        alert()->success("Successfully Canceled!")->persistent("Close!");

        $table_number = 0;
		try {

		$pending_order_details = Order::findOrFail($request->order_id);

		} catch (\Exception $e) {

        	alert()->error("Pending Order Not Found!")->persistent("Close!");

            return redirect()->back();
    	}

    	$total_qty = 0 ;

    	$total_price = 0 ;

    	foreach ($pending_order_details->option as $option) {

			$total_qty += $option->pivot->quantity;

			$total_price += $option->sale_price * $option->pivot->quantity;
		}

    	return view('Sale.delivery_pending_order_details', compact('pending_order_details','total_qty','total_price','table_number'));
    }

    protected function cancelvoucher(Request $request){
        $voucher = Voucher::find($request->voucher_id);
        $voucher->status = 5;
        $voucher->save();
        return response()->json([
            'data' => 'success'
        ],200);
    }

	//voucher history
	protected function getShopVoucherDetail($voucher_id){
		try {
			$shop_order = DB::table('shop_order_voucher')->where('voucher_id', $voucher_id)->first();

        	$order = ShopOrder::findOrFail($shop_order->shop_order_id);

   		} catch (\Exception $e) {

        	alert()->error("Shop Order Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

    	$voucher = Voucher::where('id', $order->voucher_id)->first();

		$items = DB::table('item_shop_order')->where('shop_order_id', $order->id)->get();

		// $names = [];

		// foreach($items as $item){
			$names = MenuItem::all();
		// 	array_push($names , $n);
		// }

    	return view('Sale.take_away_voucher', compact('voucher', 'items', 'names'));
	}

	protected function getShopVoucherDetail1($voucher_id){
		try {
			$shop_order = DB::table('shop_order_voucher')->where('voucher_id', $voucher_id)->first();

        	$order = ShopOrder::findOrFail($shop_order->shop_order_id);

   		} catch (\Exception $e) {

        	alert()->error("Shop Order Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

    	$voucher = Voucher::where('id', $order->voucher_id)->first();

        $voutotal = ($order->adult_qty * 21900)+($order->child_qty * 11000)+ ($order->kid_qty * 9000)+ ($order->extrapot_qty *3000) + $voucher->extra_amount;

        $servicecharges =($voutotal/100 * 5);

    	return view('Sale.voucher', compact('voucher','order','voutotal','servicecharges'));
	}
}
