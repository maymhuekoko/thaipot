<?php

namespace App\Http\Controllers\Web;

use App\ConsumptionHistory;
use App\Town;
use App\User;
use DateTime;
use App\Order;
use App\State;
use App\Table;
use App\Option;
use App\Expense;
use App\Voucher;
use App\MenuItem;
use App\Purchase;
use App\Promotion;
use App\ShopOrder;
use App\TableType;
use App\PurchaseHistory;
use Carbon\Carbon;
use App\CuisineType;
use Illuminate\Http\Request;
use App\Exports\ExportExpense;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Income;
use App\Pi;
use App\PiCategory;
use App\TotalConsumption;
use App\TotalPurchase;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    protected function getAdminDashboard()
	{
		return view('Admin.admin_panel');
	}

	protected function getEmployeeList()
	{
        $employee = User::all();

		return view('Admin.employee_list', compact('employee'));
	}

    protected function storeEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:App\User,email',
            'password' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $image_name = "user.jpg";


            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'photo_path' => $image_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'role_flag' => $request->role_name,
                'prohibition_flag' => 1,
            ]);



        alert()->success('Successfully Added');

        return redirect()->route('employee_list');

    }
    protected function updateEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:App\User,email',
            'password' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $image_name = "user.jpg";


            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'photo_path' => $image_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'role_flag' => $request->role_name,
                'prohibition_flag' => 1,
            ]);



        alert()->success('Successfully Added');

        return redirect()->route('employee_list');

    }

    protected function getEmployeeDetails($id){

        try {

            $employee = User::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Employee Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        return view('Admin.employee_details', compact('employee'));
    }

    protected function getTableList(){

        $table_lists = Table::orderBy('table_type_id', 'ASC')->get();

        $table_type_lists = TableType::all();

        return view ('Admin.table_list', compact('table_lists','table_type_lists'));
    }

    protected function storeTableType(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'prefix' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $user_code = $request->session()->get('user')->id;

        try {

            TableType::create([
                'name' => $request->name,
                'prefix' => $request->prefix,
                'created_by' => $user_code,
            ]);

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Creating Table Type.');

            return redirect()->back();
        }

        alert()->success('Successfully Added');

        return redirect()->route('table_list');

    }

    protected function updateTableType(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        try {

            $type = TableType::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Table Type Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $type->name = $request->name;

        $type->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('table_list');
    }

    protected function storeTable(Request $request){

        $validator = Validator::make($request->all(), [
            'table_type' => 'required',
            'quantity' => 'required',
            'table_prefix' => 'required',
            'floor' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        try {

            $type = TableType::findOrFail($request->table_type);

        } catch (\Exception $e) {

            alert()->error("Table Type Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $prefix = $type->prefix;

        $table_prefix = $request->table_prefix;

        $floor = $request->floor;

        $number_of_table = $request->quantity;

        for ($i = 1; $i <= $number_of_table; $i++) {

            $table = substr($table_prefix, 0, -1);

            $room_num = "$prefix". "-" ."$floor" . "$table" . $i;

            //$room_num = "$build_name". "-" . "$i" . "$prefix" . $j;

            Table::create([
                'table_number' => $room_num,
                'floor' => $floor,
                'table_type_id' => $type->id,
            ]);

        }

        alert()->success('Successfully Added!');

        return redirect()->route('table_list');
    }

    //state township
    protected function getStateList()
	{

		$state_lists = State::all();

		return view('Admin.state_list', compact('state_lists'));
	}

    protected function storeTown(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'code' => 'required',
			'name' => 'required',
			'state_id' => 'required',
			'allowdelivery'=> 'required',
		]);
		if($request->allowdelivery == 1){
			$validator = Validator::make($request->all(), [
				'code' => 'required',
				'name' => 'required',
				'state_id' => 'required',
				'allowdelivery'=> 'required',
				'charges' => 'required'
			]);
		}
		if ($validator->fails()) {

			alert()->error('Something Wrong!');

			return redirect()->back();
		}

		try {

			$town = Town::create([
				'town_code' => $request->code,
				'town_name' => $request->name,
				'state_id' => $request->state_id,
				'status' => $request->allowdelivery,
				'delivery_charges'=> $request->charges,
			]);

		} catch (\Exception $e) {

			alert()->error('Something Wrong!');

			return redirect()->back();
		}

		alert()->success('Successfully Added');

		return redirect()->back();
	}

    protected function editTown(Request $request)
	{

		try {

			$town = Town::findOrFail($request->town_id);
            // dd($town);
		} catch (\Exception $e) {

			alert()->error("Town Not Found!")->persistent("Close!");

			return redirect()->back();
		}


		$town->town_code = $request->town_code;
		$town->town_name = $request->town_name;
		$town->status = $request->allowdelivery;
		$town->delivery_charges = $request->editcharges;

		$town->save();

		alert()->success('Successfully Updated!');
        $town_lists = Town::where('state_id', $request->state_id)->get();

		return response()->json($town_lists);
	}

    protected function ajaxSearchTown(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'state_id' => 'required',
		]);

		if ($validator->fails()) {

			return response()->json(array("errors" => $validator->getMessageBag()), 422);
		}

		$town_lists = Town::where('state_id', $request->state_id)->get();

		return response()->json($town_lists);
	}
    //end state township

    //date search for expense
    protected function ajaxSearchPurchase(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'date' => 'required',
		]);

		if ($validator->fails()) {

			return response()->json(array("errors" => $validator->getMessageBag()), 422);
		}

		$expense_lists = Expense::where('date', $request->date)->get();

		return response()->json($expense_lists);
	}

    public function exportExpenses(Request $request){
        return Excel::download(new ExportExpense, 'Expenses.xlsx');
    }

    protected function newOreditPurchase(Request $request)
	{

		try {

			$edit_new_exp = Expense::findOrFail($request->purchase_id);
            // dd($town);
		} catch (\Exception $e) {

			Expense::create([
                'title' => $request->title,
                'description' => $request->description,
                'amount' => $request->amount,
                'date' => $request->date,
            ]);
            $expense_lists = Expense::where('date', $request->date)->get();

		return response()->json($expense_lists);

		}


		$edit_new_exp->title = $request->title;
		$edit_new_exp->description = $request->description;
		$edit_new_exp->amount = $request->amount;
		$edit_new_exp->date = $request->date;

		$edit_new_exp->save();

		alert()->success('Successfully Updated!');
        $expense_lists = Expense::where('date', $request->date)->get();

		return response()->json($expense_lists);
	}

    protected function deletePurchaseExpense(Request $request){
        $del_exp = Expense::findOrFail($request->id);
        $del_exp->delete();
        $expense_lists = Expense::where('date', $request->date)->get();
		return response()->json($expense_lists);
    }
    //end date

    // public function mobileprint(Request $request)
    // {
    //         $orders = ShopOrder::where("is_mobile",1)->with('option')->with('table')->orderBy('id','desc')->first();
    //         // dd($orders);
    //         $option_name = DB::table('option_shop_order')
    //         ->where('shop_order_id',$orders->id)
    //         ->where('print',0)
    //         ->get();
    //         // dd($option_name);

    //         $date = new DateTime('Asia/Yangon');
    //          $real_date = $date->format('d-m-Y h:i:s');

    //          $wname = session()->get('user')->name;

    //     $name = [];
	// 	foreach($option_name as $optionss)
	// 	{
	// 	$oname = Option::where('id',$optionss->option_id)->with('menu_item')->first();
	// 	array_push($name,$oname);
	// 	}
    //     // dd($name);
    //     // $print = DB::table('option_shop_order')
    //     //     ->where('shop_order_id',$orders->id)
    //     //     ->update(['print' => 1]);
    //         if($orders){
    //             return response()->json([
    //                 'name' => $name,
    //                 'optqty' => $option_name,
    //                 'date' => $real_date,
    //                 'waiter' => $wname,
    //                 'order_table' => $orders,
    //             ]);
    //         }else{
    //             return response()->json(null);
    //         }
    // }


    // public function addmobileprint(Request $request)
    // {
    //         $orders = ShopOrder::where("is_mobile",1)->with('option')->with('table')->orderBy('id','desc')->first();
    //         // dd($orders);
    //         $print = DB::table('option_shop_order')
    //         ->where('shop_order_id',$orders->id)
    //         ->update(['print' => 1]);

    //             return response()->json([
    //                 'data' => 'success'
    //             ],200);

    // }

    public function mobileprint(Request $request)
    {
            // $orders = ShopOrder::where("is_mobile",1)->with('option')->with('table')->orderBy('id','desc')->first();
            // dd($orders->id);
            $option_n = DB::table('option_shop_order')
            ->where('print',0)
            ->get();
            // dd(count($option_n));

            if(count($option_n) == 0 ){
                $option_name = DB::table('option_shop_order')
                ->where('status',5)
                ->get();
                $print1 = DB::table('option_shop_order')
                  ->where('status',5)
                  ->update(['status' => 0]);
            }
            else{
                $option_name = $option_n;
                $print = DB::table('option_shop_order')
            ->where('print',0)
            ->update(['print' => 1]);
            }

            // dd(count($option_name));

            $orders = DB::table('option_shop_order')->orderBy('id','desc')->first();
            $tableno = ShopOrder::find($orders->shop_order_id);

            // dd($tableno->table_id);
            // console.log($option_name , $orders);
            $date = new DateTime('Asia/Yangon');
             $real_date = $date->format('d-m-Y h:i:s');

             $wname = session()->get('user')->name;
            $name = [];
		foreach($option_name as $optionss)
		{
		$oname = Option::where('id',$optionss->option_id)->with('menu_item')->first();
		array_push($name,$oname);
		}
        // dd($name);
        // if(count($option_n) == 0 ){
        //     $print1 = DB::table('option_shop_order')
        //     ->where('status',5)
        //     ->update(['status' => 0]);
        // }
        // else{
            // $print = DB::table('option_shop_order')
            // ->where('print',0)
            // ->update(['print' => 1]);
        // }



            // if($orders){
                return response()->json([
                    'name' => $name,
                    'optqty' => $option_name,
                    'date' => $real_date,
                    'waiter' => $wname,
                    'order_table' => $orders,
                    'tableno' => $tableno
                ]);
            // }else{
                // return response()->json(null);
            // }
    }

    protected function getFinicial(Request $request){
        return view('Admin.financial_panel');
    }

//     protected function getTotalSaleReport(Request $request){

//         $shopOrdelivery = $request->shopOrdelivery;
//         $type = $request->type;
//         $total_sale_price=0;
//         $total_est_cost_price=0;
//         if($shopOrdelivery==3){
//             $expenses_daily_amount=0;
//             $expenses_weekly_amount=0;
//             $expenses_monthly_amount=0;
//             $expenses_indate_amount=0;
//             $shop_total_sale_price=0;
//             $delivery_total_sale_price=0;
//             $shop_total_est_price=0;
//             $delivery_total_est_price=0;

//             if($type == 1){

//                 $daily = date('Y-m-d', strtotime($request->value));
//                     $shop_voucher_lists = Voucher::where('type',1)->whereDate('voucher_date',$daily)->get();
//                     $delivery_voucher_lists = Voucher::where('type',2)->whereDate('voucher_date',$daily)->get();
//                     $indate= Expense::whereDate('date',$request->value)->get();
//                     foreach($indate as $exp_indate){
//                         $expenses_indate_amount+=$exp_indate->amount;
//                     }
//             }elseif ($type == 7){
//                 $shop_voucher_lists = Voucher::where('type',1)->whereBetween('date', [$request->start_date, $request->end_date])->get();
//                 $delivery_voucher_lists = Voucher::where('type',2)->whereBetween('date', [$request->start_date, $request->end_date])->get();
//                 $indate= Expense::whereBetween('date', [$request->start_date, $request->end_date])->get();
//                 foreach($indate as $exp_indate){
//                     $expenses_indate_amount+=$exp_indate->amount;
//                 }
//             }

//             foreach($shop_voucher_lists as $shop_voucher_list){
//                 $shop_total_sale_price += $shop_voucher_list->total_price;

// //                foreach($shop_voucher_list->option as $shop_option){
// //                    $shop_total_sale_price+=$shop_option->sale_price;
// //                    $shop_total_est_price+=$shop_option->est_cost_price;
// //                }
//             }
//             foreach($delivery_voucher_lists as $delivery_voucher_list){
//                 $delivery_total_sale_price+=$delivery_voucher_list->total_price;

// //                foreach($delivery_voucher_list->option as $delivery_option){
// //                    $delivery_total_sale_price+=$delivery_option->sale_price;
// //                    $delivery_total_est_price+=$delivery_option->est_cost_price;
// //                }
//             }
//             $total_fixed_expense= $expenses_daily_amount;

//             return response()->json([
//                 "allShopAndDeli"=>1,
//                 "shop_total_sale"=> $shop_total_sale_price,
//                 "delivery_total_sale"=> $delivery_total_sale_price,
//                 "shop_total_est_price"=> $shop_total_est_price,
//                 "delivery_total_est_price"=> $delivery_total_est_price,
//                 "expenses_indate_amount"=> $expenses_indate_amount,
//                 "total_fixed_expense"=> $total_fixed_expense,
//             ]);
//         }
//         else {
//             $expenses_indate_amount = 0;
//             if($type == 1){

//                 $daily = date('Y-m-d', strtotime($request->value));
//                     if($shopOrdelivery==1){
//                         $shop_deli_type=1;
//                     }
//                     elseif($shopOrdelivery==2){
//                         $shop_deli_type=2;
//                     }
//                     $voucher_lists = Voucher::where('type',$shop_deli_type)->whereDate('voucher_date',$daily)->with('shopOrder.table')->get();

//                     foreach($voucher_lists as $voucher_list){
//                         foreach($voucher_list->option as $shop_option){
//                             $total_sale_price+=$shop_option->sale_price;
//                             $total_est_cost_price+=$shop_option->est_cost_price;
//                         }
//                     }
//                     $indate= Expense::whereDate('date',$request->value)->get();

//                     foreach($indate as $exp_indate){
//                         $expenses_indate_amount+=$exp_indate->amount;
//                     }

//             } elseif ($type == 7){

//                 if($shopOrdelivery==1){
//                     $shop_deli_type=1;
//                 }
//                 elseif($shopOrdelivery==2){
//                     $shop_deli_type=2;
//                 }
//                 $voucher_lists = Voucher::whereBetween('date', [$request->start_date, $request->end_date])->where('type',$shop_deli_type)->with('shopOrder.table')->get();
//                 foreach($voucher_lists as $voucher_list){
//                     foreach($voucher_list->option as $shop_option){
//                         $total_sale_price+=$shop_option->sale_price;
//                         $total_est_cost_price+=$shop_option->est_cost_price;
//                     }
//                 }
//                 $indate = Expense::whereBetween('date', [$request->start_date, $request->end_date])->get();

//                 foreach($indate as $exp_indate){
//                     $expenses_indate_amount+=$exp_indate->amount;
//                 }

//             }



//             return response()->json([
//                 "allShopAndDeli"=>0,
//                 "total_sales" => $total_sale_price,
//                 "total_est_price" => $total_est_cost_price,
// //                "total_profit"=> $total_sale_price-$total_est_cost_price,
//                 "total_profit"=> $total_sale_price-$expenses_indate_amount,
//                 "voucher_lists" => $voucher_lists,
//                 "expenses_indate_amount" => $expenses_indate_amount,
//             ]);
//         }

//     }

    public function getTotalExpense(Request $request)
    {
	    $expenses = Expense::where('date',$request->value)->get();

        return response()->json($expenses);
    }
    public function getExpense()
    {
	    $expenses = Expense::all();

        return view('Admin.expense',compact('expenses'));
    }

 public function getSaleRecord( Request $request)
 {
    $shopOrdelivery = $request->shopOrdelivery;
    $type = $request->type;
    $options=[];
    $arr_ki=[];
    $total_qty=[];

        if($type == 1){

            $daily = date('Y-m-d', strtotime($request->value));
                if($shopOrdelivery==1){
                    $shop_deli_type=1;
                }
                elseif($shopOrdelivery==2){
                    $shop_deli_type=2;
                }
                $voucher_lists = Voucher::where('type',$shop_deli_type)->whereDate('voucher_date',$daily)->get();

        }
        elseif($type == 2){
            $week_count = $request->value;

            $start_month = date('Y-m-d',strtotime('first day of this month'));

            if ($week_count == 1) {

                $start_date = date('Y-m-d',strtotime('first day of this month'));

                $end_date = date('Y-m-d', strtotime("+1 week" , strtotime($start_month)));

            } elseif ($week_count == 2) {

                $start_date = date('Y-m-d', strtotime("+1 week" , strtotime($start_month)));

                $end_date = date('Y-m-d', strtotime("+2 week" , strtotime($start_month)));

            } elseif ($week_count == 3) {

                $start_date = date('Y-m-d', strtotime("+2 week" , strtotime($start_month)));

                $end_date = date('Y-m-d', strtotime("+3 week" , strtotime($start_month)));

            } else {

                $start_date = date('Y-m-d', strtotime("+3 week" , strtotime($start_month)));

                $end_date = date('Y-m-d',strtotime('last day of this month'));

            }

            if($shopOrdelivery==1){

                $voucher_lists = Voucher::where('type',1)->whereBetween('voucher_date', [$start_date, $end_date])->get();

            }
            elseif($shopOrdelivery==2) {
                $voucher_lists = Voucher::where('type',2)->whereBetween('voucher_date', [$start_date, $end_date])->get();
            }

        }
        else{

            $monthly = $request->value;

            if($shopOrdelivery==1){

                $voucher_lists = Voucher::where('type',1)->whereMonth('voucher_date', $monthly)->get();

            }
            elseif($shopOrdelivery==2) {
                $voucher_lists = Voucher::where('type',2)->whereMonth('voucher_date', $monthly)->get();
            }

        }

        $menu_items = MenuItem::all();

        foreach($voucher_lists as $key=>$item){

            $item_count=count($options);

            for($i=0; $i<count($item->option);$i++){

                if(!in_array($item->option[$i]->id,$arr_ki)){

                    array_push($arr_ki,$item->option[$i]->id);

                    // $total_qty=[...$total_qty,[
                    //     'option_id'=>$item->option[$i]->id,
                    //     'qty'=>$item->option[$i]->pivot->quantity,
                    //     ],
                    // ];
                    array_push($total_qty,[
                        'option_id'=>$item->option[$i]->id,
                        'qty'=>$item->option[$i]->pivot->quantity,
                        ]);

                    array_push($options,$item->option[$i]);
                }

            else{
                foreach($total_qty as $key=>$t){

                   if($t['option_id']==$item->option[$i]->id)
                    {
                        $qty = $t['qty'] + $item->option[$i]->pivot->quantity;

                        array_splice($total_qty, $key, 1);
                         array_push($total_qty,[
                            'option_id'=>$item->option[$i]->id,
                            'qty'=>$qty
                        ]);

                        // $total_qty=[...$total_qty,[
                        //     'option_id'=>$item->option[$i]->id,
                        //     'qty'=>$qty
                        // ]];
                    }
                }

            }

            }

        }

        return response()->json([
            "total_qty"=> $total_qty,
            "options" => $options,
            "menu_items"=> $menu_items
        ]);
    // }

 }
public function saleRecord()
{
    return view('Admin.sale_record');
}

protected function getTotalOrderFulfill(Request $request)
{
       $jan_income = Voucher::whereMonth('date', '01')->where('status',0)->get();
       $jan  = 0;
       foreach($jan_income as $j){
             $jan += $j->total_price;
       }

        $feb_income = Voucher::whereMonth('date', '02')->where('status',0)->get();
        $feb  = 0;
       foreach($feb_income as $f){
             $feb += $f->total_price;
       }

        $mar_income = Voucher::whereMonth('date', '03')->where('status',0)->get();
        $mar  = 0;
       foreach($mar_income as $m){
             $mar += $m->total_price;
       }

        $apr_income = Voucher::whereMonth('date', '04')->where('status',0)->get();
        $apr  = 0;
        foreach($apr_income as $a){
              $apr += $a->total_price;
        }

        $may_income = Voucher::whereMonth('date', '05')->where('status',0)->get();
        $may  = 0;
       foreach($may_income as $ma){
             $may += $ma->total_price;
       }

        $jun_income = Voucher::whereMonth('date', '06')->where('status',0)->get();
        $jun  = 0;
       foreach($jun_income as $ju){
             $jun += $ju->total_price;
       }

    $jul_income = Voucher::whereMonth('date', '07')->where('status',0)->get();
    $jul  = 0;
    foreach($jul_income as $july){
          $jul += $j->total_price;
    }

        $aug_income = Voucher::whereMonth('date', '08')->where('status',0)->get();
        $aug  = 0;
        foreach($aug_income as $au){
              $aug += $au->total_price;
        }
        $sep_income = Voucher::whereMonth('date', '09')->where('status',0)->get();
        $sep  = 0;
        foreach($sep_income as $se){
              $sep += $se->total_price;
        }
        $oct_income = Voucher::whereMonth('date', '10')->where('status',0)->get();
        $oct  = 0;
       foreach($oct_income as $o){
             $oct += $o->total_price;
       }
        $nov_income = Voucher::whereMonth('date', '11')->where('status',0)->get();
        $nov  = 0;
        foreach($nov_income as $n){
              $nov += $n->total_price;
        }

        $dec_income = Voucher::whereMonth('date', '12')->where('status',0)->get();
        $dec  = 0;
       foreach($dec_income as $de){
             $dec += $de->total_price;
       }

    // dd($firstdate."--->".$first_week."-->".$second_week."-->".$third_week);
    return response()->json([
        "jan_income" => $jan,
        "feb_income" => $feb,
        "mar_income" => $mar,
        "apr_income" => $apr,
        "may_income" => $may,
        "jun_income" => $jun,
        "jul_income" => $jul,
        "aug_income" => $aug,
        "sep_income" => $sep,
        "oct_income" => $oct,
        "nov_income" => $nov,
        "dec_income" => $dec,
    ]);

}
protected function getmonthpie(Request $request)
{
    // dd($request->pie_month);
    $total_sale=0; $total_purchase=0; $total_expense=0;
    if($request->pie_month == '01'){
       $voucher = Voucher::whereMonth('date', '01')->where('status',0)->get();
       $purchase = TotalPurchase::whereMonth('created_at','01')->get();
       $expense = Expense::whereMonth('date','01')->get();
    }
    else if($request->pie_month == '02'){
        $voucher = Voucher::whereMonth('date', '02')->where('status',0)->get();;
        $purchase = TotalPurchase::whereMonth('created_at','02')->get();
        $expense = Expense::whereMonth('date','02')->get();
     }
     else if($request->pie_month == '03'){
        $voucher = Voucher::whereMonth('date', '03')->where('status',0)->get();
        $purchase = TotalPurchase::whereMonth('created_at','03')->get();
        $expense = Expense::whereMonth('date','03')->get();
     }
     else if($request->pie_month == '04'){
        $voucher = Voucher::whereMonth('date', '04')->where('status',0)->get();
        $purchase = TotalPurchase::whereMonth('created_at','04')->get();
        $expense = Expense::whereMonth('date','04')->get();
     }
     else if($request->pie_month == '05'){
        $voucher = Voucher::whereMonth('date', '05')->where('status',0)->get();
        $purchase = TotalPurchase::whereMonth('created_at','05')->get();
        $expense = Expense::whereMonth('date','05')->get();
     }
     else if($request->pie_month == '06'){
        $voucher = Voucher::whereMonth('date', '06')->where('status',0)->get();
        $purchase = TotalPurchase::whereMonth('created_at','06')->get();
        $expense = Expense::whereMonth('date','06')->get();
     }
     else if($request->pie_month == '07'){
        $voucher = Voucher::whereMonth('date', '07')->where('status',0)->get();
        $purchase = TotalPurchase::whereMonth('created_at','07')->get();
        $expense = Expense::whereMonth('date','07')->get();
     }
     else if($request->pie_month == '08'){
        $voucher = Voucher::whereMonth('date', '08')->where('status',0)->get();
        $purchase = TotalPurchase::whereMonth('created_at','08')->get();
        $expense = Expense::whereMonth('date','08')->get();
     }
     else if($request->pie_month == '09'){
        $voucher = Voucher::whereMonth('date', '09')->where('status',0)->get();
        $purchase = TotalPurchase::whereMonth('created_at','09')->get();
        $expense = Expense::whereMonth('date','09')->get();
     }
     else if($request->pie_month == '10'){
        $voucher = Voucher::whereMonth('date', '10')->where('status',0)->get();
        $purchase = TotalPurchase::whereMonth('created_at','10')->get();
        $expense = Expense::whereMonth('date','10')->get();
     }
     else if($request->pie_month == '11'){
        $voucher = Voucher::whereMonth('date', '11')->where('status',0)->get();
        $purchase = TotalPurchase::whereMonth('created_at','11')->get();
        $expense = Expense::whereMonth('date','11')->get();
     }
     else if($request->pie_month == '12'){
        $voucher = Voucher::whereMonth('date', '12')->where('status',0)->get();
        $purchase = TotalPurchase::whereMonth('created_at','12')->get();
        $expense = Expense::whereMonth('date','12')->get();
     }
    foreach($voucher as $vou){
        $total_sale += $vou->total_price;
    }
    foreach($purchase as $pur){
        $total_purchase += $pur->price;
    }
    foreach($expense as $exp){
        $total_expense += $exp->amount;
    }
    return response()->json([
        'total_sale' => $total_sale,
        'total_purchase' => $total_purchase,
        'total_expense' => $total_expense
    ]);

}
function getWeekNowFamous_Menu()
{
    // dd('hello');
    $arr = [];$arr_un = [];
    $now = Carbon::now();
    $weekStartDate = $now->startOfWeek()->format('Y-m-d');
    $weekEndDate = $now->endOfWeek()->format('Y-m-d');


    $duplicates = DB::table('option_voucher')
    ->select('option_id', DB::raw('COUNT(*) as `count`'))
    ->groupBy('option_id')
    ->havingRaw('COUNT(*) > 2')
    ->take(5)
    ->get();

    foreach($duplicates as $dup){
        $menu = Option::where('id',$dup->option_id)->with('menu_item')->first();
        // dd($menu->menu_item->item_name);
        array_push($arr,$menu->menu_item->item_name);
    }

    $options = DB::table('option_voucher')->get();
    $opt = Option::all();
    $un_menu = DB::table('options')
    ->whereNotIn('id', DB::table('option_voucher')->pluck('option_id'))
    ->take(20)
    ->get();
    foreach($un_menu as $un){
        $mnu = MenuItem::find($un->menu_item_id);
        array_push($arr_un,$mnu->item_name);
    }
    // array_push($arr_un,$un_menu->menu_item->item_name);
     $arrr = array_unique($arr_un);
     $arrrr = array_slice($arrr, 0, 5);
    //  dd($arrrr);

    return response()->json([
        'famous_item' => $arr,
        'unfamous_item' => $arrrr
    ]);
}

// manager dashboard
public function managerDashboard(){
    $voucher = Voucher::where('status',0)->get();
            $purchase = Purchase::all();
            $expense = Expense::all();
            $total_sale = 0;$today_sale = 0;$total_inventory = 0;$total_expense=0;$total_profit= 0;
            $today = date("Y-m-d");
            foreach($voucher as $vou){
                $total_sale += $vou->total_price;
            }
            $tod_voucher = Voucher::where('date',$today)->where('status',0)->get();
               foreach($tod_voucher as $tod){
                $today_sale += $tod->total_price;
            }
            foreach($purchase as $pur){
                $total_inventory += $pur->total_price;
            }
            foreach($expense as $exp){
                $total_expense += $exp->amount;
            }
            $menu = MenuItem::all()->count();
            return view('report',compact('total_sale','today_sale','total_inventory','menu','total_expense'));
}
// public function getFamousWeek_data(Request $request)
// {
//     // dd($request->famous_week);
//     $arr = [];
//     if($request->type == 2){
//         $weekStartDate = Carbon::parse($request->famous_week)->startOfWeek()->format('Y-m-d');
//     $weekEndDate = Carbon::parse($request->famous_week)->endOfWeek()->format('Y-m-d');
//     // dd($weekStartDate);
//     $voucher_lists = Voucher::whereBetween('date', [$weekStartDate, $weekEndDate])->get();
//     }
//     else if($request->type == 1){
//         $voucher_lists = Voucher::where('date', $request->famous_day)->get();
//     }

//     foreach($voucher_lists as $vlist)
//     {
//         $voucher_option = DB::table('option_voucher')->where('voucher_id',$vlist->id)->get();
//         foreach($voucher_option as $vou){
//             $menu_name = Option::where('id',$vou->option_id)->get();
//             foreach($menu_name as $menu){
//                 $item = MenuItem::where('id',$menu->menu_item_id)->get();
//                foreach($item as $it){
//                  array_push($arr,['id' => $it->id,'menu' => $it->item_name, 'count' => 0],);
//                 // array_push($arr,$it->id);
//                }
//             }
//         }
//     }
//     // dd($arr);
//     // dd(array_count_values($arr));
//     $famous_menu = [];
//     $count = 0;
//     for($i=0;$i<count($arr);$i++)
//     {
//         $n = $arr[$i]['id'];

//         // [26,27,51,31,44,27,26]
//         // dd($arr[$i]['id']."-----".$arr[$j]['id']);
//         for($j=0;$j<count($arr);$j++)
//         {
//             if($n == $arr[$j]['id'])
//             {
//                 $arr[$i]['count'] +=1;

//             }

//         }


//     }
//     $arr_menu = array_unique($arr, SORT_REGULAR);
//     // dd($arr_menu);
//     return response()->json($arr_menu);
// }

public function getPromotionList(Request $request){
    $promotion = Promotion::all();
    $menu = MenuItem::all();
    return view('Admin.promotion_create',compact('promotion','menu'));
}

public function storePromotion(Request $request){
    // dd($request->all());
    $validator = Validator::make($request->all(), [
        'title' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
    ]);

    if ($validator->fails()) {

        alert()->error("Something Wrong! Validation Error");

        return redirect()->back();
    }

    $promotion = Promotion::create([
        'title' => $request->title,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'customer_console' => 1,
    ]);
    $html = ''; $html1 = '';
    if($request->flexRadio == 1){
        $promotion->reward = 1;
        $promotion->amount = $request->amount;
    }
    else if($request->flexRadio == 2){
        $promotion->reward = 2;
        foreach($request->menuitem as $menu){
            $html .= $menu.',';
        }
        $promotion->foc_items = $html;
    }
    else{
        $promotion->reward = 3;
        $promotion->percent = $request->percentage;
    }

    if($request->flexRadio1 == 1){
        $promotion->type = 1;
        $promotion->voucher_amount = $request->voucher_amount;
    }
    else if($request->flexRadio1 == 2){
        $promotion->type = 2;
        foreach($request->purchaseitem as $purchase){
            $html1 .= $purchase.',';
        }
        $promotion->purchase_item = $html1;
    }
    else{
        $promotion->type = 3;
        $promotion->purchase_time = $request->purchase_time;
    }
    $promotion->save();

    alert()->success('Promotion is successfully Created!');
    return redirect()->back();
}

protected function deletePromotion($id) //Not Finish
    {

        $item = Promotion::find($id);

        $item->delete();

        alert()->success('Successfully Deleted!');
        return back();
    }
protected function checkPromotion(Request $request){
    $currentDate = Carbon::now();
    $promotion = Promotion::find($request->promotion_id);
    $order = ShopOrder::where('id',$request->order_id)->first();

    if (($currentDate >= $promotion->start_date) && ($currentDate <= $promotion->end_date)){
        $promo = $promotion;
      }else{
        $promo = [];
      }

    return response()->json([
        "promotion" => $promo,
        "order" => $order
    ]);
}

    protected function getDailyPurchase(){
        $purchases = TotalPurchase::all();

        return view('Admin.purchase_items',compact('purchases'));
    }

    protected function createDailyPurchase(){
        $categories = PiCategory::all();
        $items = Pi::all();

        return view('Admin.create_purchase_items', compact('categories', 'items'));
    }

    protected function purchaseSubCategorySearch(Request $request){
        $cat_id = $request->cat_id;
        $items = Pi::where('pi_category_id',$cat_id)->get();

        return response()->json($items);
    }

    protected function purchaseItemSearch(Request $request){
        $item_id = $request->item_id;
        $item = Pi::where('id', $item_id)->first();

        return response()->json($item);
    }

    protected function addDailyPurchase(Request $request){
        $item = Pi::where('id', $request->item_name)->first();

        if($item->price != $request->price){
            return redirect()->back()->with([
                "current_price" => $request->price,
                "old_price" => $item->price,
            ]);
        }
    }

    protected function searchItem($id){
        $item = Pi::where('id', $id)->first();
        return response()->json($item);
    }

    public function purchasepriceUpdate(Request $request)
    {   
        try{
            $counting_unit = Pi::findOrfail($request->unit_id);
        } catch (\Exception $e) {
            return response()->json(0);
        }
        $counting_unit->update([
            'price' => $request->purchase_price,
         ]);

         return response()->json($counting_unit);

    }

    protected function storePurchaseHistory(Request $request){
        $validator = Validator::make($request->all(), [
            'purchase_number' => 'required',
            'unit' => 'required',
            'price' => 'required',
            'qty' => 'required',
        ]);

        $unit = $request->unit;

        $price = $request->price;

        $qty = $request->qty;
        
        $type = $request->type;

        $purchase_no = $request->purchase_number;

        $total_qty = 0;

        $total_price = 0;

        $psub_total = 0;

        $date = date('Y-m-d H:i:s');

        for($count = 0; $count < count($unit); $count++){
            $psub_total = $price[$count] * $qty[$count];
            $total_price += $psub_total;
        }

        

        foreach ($qty as $q) {

            $total_qty += $q;
        }

        try {

            $consumption_total = TotalPurchase::create([
                "total_quantity" =>  $total_qty,
                "price" => $total_price,
            ]);


            for($count = 0; $count < count($unit); $count++){

                 $counting_unit = Pi::find($unit[$count]);
                 
                 $balance_qty = ($counting_unit->current_quantity + $qty[$count]);
                
                 $counting_unit->stock_quantity = $counting_unit->stock_quantity + $balance_qty;

                 $counting_unit->price = $request->price[$count];

                 $counting_unit->save();

                 $purchase = PurchaseHistory::create([
                    "total_purchase_id" => $consumption_total->id,
                    'pi_category_id' => $counting_unit->pi_category_id,
                    'purchase_item_id' => $counting_unit->id,
                    'name' => $counting_unit->name,
                    'purchase_no' => $request->purchase_number,
                    'amount' => $counting_unit->amount,
                    'unit' => $counting_unit->unit,
                    'price' => $request->price[$count],
                    'stock_quantity' => $request->qty[$count],
                ]);

            }

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Purchase Store.');

            return redirect()->back();
        }

        alert()->success("Success");

        return redirect('/daily_purchase');
    }

    protected function getPurchaseHistoryDetails($id){

        try {

            $purchase = TotalPurchase::findOrFail($id);
            $items = PurchaseHistory::where('total_purchase_id', $purchase->id)->get();

        } catch (\Exception $e) {

            alert()->error('Something Wrong! Purchase Cannot be Found.');

            return redirect()->back();
        }

        return view('Admin.purchase_details', compact('purchase', 'items'));

    }

    protected function getDailyConsumption(){
        $consumptions = TotalConsumption::all();
        
        return view('Admin.consumption_items',compact('consumptions'));
    }

    protected function createDailyConsumption(){
        $categories = PiCategory::all();
        $items = Pi::all();

        return view('Admin.create_consumption_items', compact('categories', 'items'));
    }

    protected function storeConsumptionHistory(Request $request){
        $validator = Validator::make($request->all(), [
            'purchase_number' => 'required',
            'unit' => 'required',
            'price' => 'required',
            'qty' => 'required',
        ]);

        $unit = $request->unit;

        $price = $request->price;

        $qty = $request->qty;
        
        $type = $request->type;

        $purchase_no = $request->purchase_number;

        $total_qty = 0;

        $total_price = 0;

        $psub_total = 0;

        $date = date('Y-m-d H:i:s');

        for($count = 0; $count < count($unit); $count++){
            $psub_total = $price[$count] * $qty[$count];
            $total_price += $psub_total;
        }

        

        foreach ($qty as $q) {

            $total_qty += $q;
        }

        try {

            $consumption_total = TotalConsumption::create([
                "total_quantity" =>  $total_qty,
                "price" => $total_price,
            ]);

            for($count = 0; $count < count($unit); $count++){

                $counting_unit = Pi::find($unit[$count]);
                 
                // $balance_qty = ($counting_unit->current_quantity + $qty[$count]);
                
                $counting_unit->stock_quantity = $counting_unit->stock_quantity - $request->qty[$count];

                $counting_unit->save();


                $consumption = ConsumptionHistory::create([
                    "total_consumption_id" => $consumption_total->id,
                    'pi_category_id' => $counting_unit->pi_category_id,
                    'purchase_item_id' => $counting_unit->id,
                    'name' => $counting_unit->name,
                    'consumption_no' => $request->purchase_number,
                    'unit' => $counting_unit->unit,
                    'stock_quantity' => $request->qty[$count],
                ]);

            }

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Purchase Store.');

            return redirect()->back();
        }

        alert()->success("Success");

        return redirect('/daily_consumption');
    }

    protected function getConsumptionHistoryDetails($id){

        try {

            $consumption = TotalConsumption::findOrFail($id);
            $items = ConsumptionHistory::where('total_consumption_id', $consumption->id)->get();
            $purchase_items = Pi::all();

        } catch (\Exception $e) {

            alert()->error('Something Wrong! Purchase Cannot be Found.');

            return redirect()->back();
        }

        return view('Admin.consumption_details', compact('consumption', 'items', 'purchase_items'));

    }

    protected function getFilterFinishedOrderList(Request $request){
        return response()->json('hello');
        $start_date = $request->start_date;
        $end_date = $request->end_date;


        $purchases = TotalPurchase::where(DB::raw('CAST(created_at as date)'), '>=', $start_date) ->where(DB::raw(' CAST(created_at as date) '), '<=', $end_date)->get();
        
        return response()->json('hello');
    }

    protected function incomeList(request $request){
	    $incomes = Income::all();

	    return view('Admin.income', compact('incomes'));
	}

    protected function storeIncome(request $request){

        $validator = Validator::make($request->all(), [
         "type" => "required",
         "title" => "required",
         "description" => "required",
         "amount" => "required",
         "profit_loss_flag" => "required",
     ]);

     if($validator->fails()){

         alert()->error('အချက်အလက် များ မှားယွင်း နေပါသည်။');

         return redirect()->back();
     }

     $item = Income::create([
             'type' => $request->type,
             'period' => $request->period,
             'date' => $request->date,
             'title' => $request->title,
             'description' => $request->description,
             'amount' => $request->amount,
             'profit_loss_flag' => $request->profit_loss_flag,
     ]);

     return redirect()->back();
 }

protected function updateIncome($id, Request $request)
	{
		try {

        	$income = Income::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Income Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $income->type = $request->type;

        $income->period = $request->period;
        
        $income->date = $request->date;

        $income->title = $request->title;
        
        $income->description = $request->description;
        
        $income->amount = $request->amount;
        
        $income->profit_loss_flag = $request->profit_loss_flag;
        
        $income->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('incomes');
	}
	
    protected function deleteIncome(Request $request)
	{
        $income = Income::find($request->income_id);
        
        $income->delete();

        $incomes = Income::all();

        // alert()->success('Successfully Deleted!');

        // return redirect()->route('incomes');
        return response()->json($incomes);
	}

    //financial
    protected function getTotalSalenAndProfit(Request $request){

        return view('Admin.financial_panel');
    }

    protected function getTotalSaleReport(Request $request){

        $type = $request->type;

        $from_date = $request->from_date;

        $to_date = $request->to_date;

        $total_sales = 0;
        
        $total_order = 0;

        $total_profit = 0;

        $other_income = 0;

        $other_expense = 0;
        
        $total_purchase = 0;
        
        $total_transaction = 0;

        $consume = 0;

        $purchase = 0;

        $consumption = 0;

        $income = 0;

        if($type == 1){

            $daily = date('Y-m-d', strtotime($request->value));

            $voucher_lists = Voucher::whereDate('voucher_date', $daily)->get();

            $total_consumptions = TotalConsumption::whereDate('created_at',$daily)->get();

            $total_purchases = TotalPurchase::whereDate('created_at',$daily)->get();

            $total_expenses = Expense::whereDate('date', $daily)->get();

            $total_incomes = Income::whereDate('date', $daily)->get();

            foreach($total_incomes as $val){
                $income += $val->amount;
            }


            // foreach($other_incomes as $other){
            //     if($other->type == 1 && $other->period == 1){
            //         $other_income += $other->amount;
            //     }
            //     else if($other->type == 1 && $other->period == 2){
            //         $other_income += (int)($other->amount/7);
            //     }
            //     else if($other->type == 1 && $other->period == 3){
            //         $other_income += (int)($other->amount/30);
            //     }
            //     else{
            //         $other_income += $other->amount;
            //     }
            // }

            // $other_expenses = Expense::whereDate('date',$daily)
            //                             ->orWhere('type', 1)->get();

            // foreach($other_expenses as $other){
            //     if($other->type == 1 && $other->period == 1){
            //         $other_expense += $other->amount;
            //     }
            //     else if($other->type == 1 && $other->period == 2){
            //         $other_expense += (int)($other->amount/7);
            //     }
            //     else if($other->type == 1 && $other->period == 3){
            //         $other_expense += (int)($other->amount/30);
            //     }
            //     else{
            //         $other_expense += $other->amount;
            //     }
            // }
            $total_shop_sales = Voucher::where('type', 1)->whereDate('voucher_date', $daily)->get();

            foreach($total_shop_sales as $sale){
                $total_order += $sale->total_price;
            }

            $total_take_away_sales = Voucher::where('type', 2)->whereDate('voucher_date', $daily)->get();

            foreach($total_take_away_sales as $sale){
                $other_income += $sale->total_price;
            }

            foreach($total_consumptions as $consumption){
                $consume += $consumption->price;
            }

            foreach($total_purchases as $pur){
                $purchase += $pur->price;
            }

            $total_profit = $consume - $purchase;
            
            // $purchase_lists = Purchase::whereDate('purchase_date',$daily)->where('purchase_type',2)->get();
            
            foreach($total_purchases as $purchase){
                $total_purchase += $purchase->price;
            }
            
            // $transaction_lists = Transaction::whereDate('tran_date',$daily)->get();
            
            // foreach($transaction_lists as $transaction){
            //     $total_transaction += $transaction->pay_amount;
            // }

            $date_fil_lists = Voucher::whereBetween('voucher_date',[$from_date,$to_date])->get();

        }
        elseif($type == 2){

            $week_count = $request->value;

            $start_month = date('Y-m-d',strtotime('first day of this month'));

            if ($week_count == 1) {

                $end_date = date('Y-m-d', strtotime("+1 week" , strtotime($start_month)));

                $voucher_lists = Voucher::whereBetween('voucher_date', [$start_month, $end_date])->get();
                
                $total_consumptions = TotalConsumption::whereBetween('created_at', [$start_month, $end_date])->get();

                $total_purchases = TotalPurchase::whereBetween('created_at', [$start_month, $end_date])->get();

                $total_expenses = Expense::whereBetween('date', [$start_month, $end_date])->get();
                                            
                $date_fil_lists = Voucher::whereBetween('voucher_date', [$start_month, $end_date])
                                        ->whereBetween('voucher_date',[$from_date,$to_date])->get();

                $total_incomes = Income::whereBetween('date',  [$start_month, $end_date])->get();

                foreach($total_incomes as $val){
                    $income += $val->amount;
                }

                foreach($total_consumptions as $consumption){
                    $consume += $consumption->price;
                }
    
                foreach($total_purchases as $pur){
                    $purchase += $pur->price;
                }
    
                $total_profit = $consume - $purchase;

                $total_shop_sales = Voucher::where('type', 1)->whereBetween('voucher_date', [$start_month, $end_date])->get();

                foreach($total_shop_sales as $sale){
                    $total_order += $sale->total_price;
                }

                $total_take_away_sales = Voucher::where('type', 2)->whereBetween('voucher_date', [$start_month, $end_date])->get();

                foreach($total_take_away_sales as $sale){
                    $other_income += $sale->total_price;
                }


                // $date_fil_lists = Voucher::whereBetween('voucher_date', [$from_date,$to_date])->get();

            } elseif ($week_count == 2) {

                $start_date = date('Y-m-d', strtotime("+1 week" , strtotime($start_month)));

                $end_date = date('Y-m-d', strtotime("+2 week" , strtotime($start_month)));

                $voucher_lists = Voucher::whereBetween('voucher_date', [$start_date, $end_date])->get();
                
                $total_consumptions = TotalConsumption::whereBetween('created_at', [$start_date, $end_date])->get();

                $total_purchases = TotalPurchase::whereBetween('created_at', [$start_date, $end_date])->get();

                $total_expenses = Expense::whereBetween('date', [$start_date, $end_date])->get();
            
                $date_fil_lists = Voucher::whereBetween('voucher_date', [$start_date, $end_date])
                                           ->whereBetween('voucher_date',[$from_date,$to_date])->get();

                $total_incomes = Income::whereBetween('date', [$start_date, $end_date])->get();

                foreach($total_incomes as $val){
                    $income += $val->amount;
                }

                foreach($total_consumptions as $consumption){
                    $consume += $consumption->price;
                }
    
                foreach($total_purchases as $pur){
                    $purchase += $pur->price;
                }

                $total_shop_sales = Voucher::where('type', 1)->whereBetween('voucher_date', [$start_date, $end_date])->get();

                foreach($total_shop_sales as $sale){
                    $total_order += $sale->total_price;
                }
    
                $total_take_away_sales = Voucher::where('type', 2)->whereBetween('voucher_date', [$start_date, $end_date])->get();

                foreach($total_take_away_sales as $sale){
                    $other_income += $sale->total_price;
                }
    
                $total_profit = $consume - $purchase;

            } elseif ($week_count == 3) {

                $start_date = date('Y-m-d', strtotime("+2 week" , strtotime($start_month)));

                $end_date = date('Y-m-d', strtotime("+3 week" , strtotime($start_month)));

                $voucher_lists = Voucher::whereBetween('voucher_date', [$start_date, $end_date])->get();
                
                $total_consumptions = TotalConsumption::whereBetween('created_at', [$start_date, $end_date])->get();

                $total_purchases = TotalPurchase::whereBetween('created_at', [$start_date, $end_date])->get();

                $total_expenses = Expense::whereBetween('date', [$start_date, $end_date])->get();
            
                $date_fil_lists = Voucher::whereBetween('voucher_date', [$start_date, $end_date])
                                           ->whereBetween('voucher_date',[$from_date,$to_date])->get();

                $total_incomes = Income::whereBetween('date', [$start_date, $end_date])->get();

                foreach($total_incomes as $val){
                    $income += $val->amount;
                }
        
                foreach($total_consumptions as $consumption){
                    $consume += $consumption->price;
                }
    
                foreach($total_purchases as $pur){
                    $purchase += $pur->price;
                }

                $total_shop_sales = Voucher::where('type', 1)->whereBetween('voucher_date', [$start_date, $end_date])->get();

                foreach($total_shop_sales as $sale){
                    $total_order += $sale->total_price;
                }
    
                $total_take_away_sales = Voucher::where('type', 2)->whereBetween('voucher_date', [$start_date, $end_date])->get();

                foreach($total_take_away_sales as $sale){
                    $other_income += $sale->total_price;
                }
    
                $total_profit = $consume - $purchase;

            } else {

                $start_date = date('Y-m-d', strtotime("+3 week" , strtotime($start_month)));

                $end_date = date('Y-m-d',strtotime('last day of this month'));

                $voucher_lists = Voucher::whereBetween('voucher_date', [$start_date, $end_date])->get();
                
                $total_consumptions = TotalConsumption::whereBetween('created_at', [$start_date, $end_date])->get();

                $total_purchases = TotalPurchase::whereBetween('created_at', [$start_date, $end_date])->orWhere('type', 1)->get();

                $total_expenses = Expense::whereBetween('date', [$start_date, $end_date])->get();
            
                $date_fil_lists = Voucher::whereBetween('voucher_date', [$start_date, $end_date])
                                           ->whereBetween('voucher_date',[$from_date,$to_date])->get();

                $total_incomes = Income::whereBetween('date', [$start_date, $end_date])->get();

                foreach($total_incomes as $val){
                    $income += $val->amount;
                }

                foreach($total_consumptions as $consumption){
                    $consume += $consumption->price;
                }
    
                foreach($total_purchases as $pur){
                    $purchase += $pur->price;
                }

                $total_shop_sales = Voucher::where('type', 1)->whereBetween('voucher_date', [$start_date, $end_date])->get();

                foreach($total_shop_sales as $sale){
                    $total_order += $sale->total_price;
                }

                $total_take_away_sales = Voucher::where('type', 2)->whereBetween('voucher_date', [$start_date, $end_date])->get();

                foreach($total_take_away_sales as $sale){
                    $other_income += $sale->total_price;
                }
    
                $total_profit = $consume - $purchase;

            }

            // foreach($other_incomes as $other){
            //     if($other->type == 1 && $other->period == 1){
            //         $other_income += $other->amount * 7;
            //     }
            //     else if($other->type == 1 && $other->period == 2){
            //         $other_income += $other->amount;
            //     }
            //     else if($other->type == 1 && $other->period == 3){
            //         $other_income += (int)($other->amount/4);
            //     }
            //     else{
            //         $other_income += $other->amount;
            //     }
            // }

            // foreach($other_expenses as $other){
            //     if($other->type == 1 && $other->period == 1){
            //         $other_expense += $other->amount * 7;
            //     }
            //     else if($other->type == 1 && $other->period == 2){
            //         $other_expense += $other->amount;
            //     }
            //     else if($other->type == 1 && $other->period == 3){
            //         $other_expense += (int)($other->amount/4);
            //     }
            //     else{
            //         $other_expense += $other->amount;
            //     }
            // }
            
            foreach($total_purchases as $purchae){
                $total_purchase += $purchae->price;
            }
            
            
            // foreach($transaction_lists as $transaction){
            //     $total_transaction += $transaction->pay_amount;
            // }

        }
        else{

            $monthly = $request->value;

            $voucher_lists = Voucher::whereMonth('voucher_date', $monthly)->get();
            
            $total_consumptions = TotalConsumption::whereMonth('created_at', $monthly)->get();

            $total_purchases = TotalPurchase::whereMonth('created_at', $monthly)->get();

            $total_expenses = Expense::whereMonth('created_at', $monthly)->get();

            $total_incomes = Income::whereMonth('date', $monthly)->get();

            foreach($total_incomes as $val){
                $income += $val->amount;
            }

            // foreach($other_incomes as $other){
            //     if($other->type == 1 && $other->period == 1){
            //         $other_income += $other->amount * 30;
            //     }
            //     else if($other->type == 1 && $other->period == 2){
            //         $other_income += $other->amount * 4;
            //     }
            //     else if($other->type == 1 && $other->period == 3){
            //         $other_income += $other->amount;
            //     }
            //     else{
            //         $other_income += $other->amount;
            //     }
            // }

            // $other_expenses = Expense::whereMonth('date', $monthly)
            //                             ->orWhere('type', 1)->get();

            // foreach($other_expenses as $other){
            //     if($other->type == 1 && $other->period == 1){
            //         $other_expense += $other->amount * 30;
            //     }
            //     else if($other->type == 1 && $other->period == 2){
            //         $other_expense += $other->amount * 4;
            //     }
            //     else if($other->type == 1 && $other->period == 3){
            //         $other_expense += $other->amount;
            //     }
            //     else{
            //         $other_expense += $other->amount;
            //     }
            // }
            
            //  $purchase_lists = Purchase::whereMonth('purchase_date', $monthly)->where('purchase_type',2)->get();
            
            foreach($total_purchases as $purchae){
                $total_purchase += $purchae->price;
            }
            
            foreach($total_consumptions as $consumption){
                $consume += $consumption->price;
            }

            foreach($total_purchases as $pur){
                $purchase += $pur->price;
            }

            $total_shop_sales = Voucher::where('type', 1)->whereMonth('voucher_date', $monthly)->get();

            foreach($total_shop_sales as $sale){
                $total_order += $sale->total_price;
            }

            $total_take_away_sales = Voucher::where('type', 2)->whereMonth('voucher_date', $monthly)->get();

            foreach($total_take_away_sales as $sale){
                $other_income += $sale->total_price;
            }

            $total_profit = $consume - $purchase;
            // $transaction_lists = Transaction::whereMonth('tran_date', $monthly)->get();
            
            // foreach($transaction_lists as $transaction){
            //     $total_transaction += $transaction->pay_amount;
            // }

            $date_fil_lists = Voucher::whereBetween('voucher_date',[$from_date,$to_date])->get();
        }


        if($from_date == null){
            foreach ($voucher_lists as $lists) {

                $total_sales += $lists->total_price;

                // foreach ($lists->counting_unit as $unit) {

                //     $total_profit += ($unit->pivot->price * $unit->pivot->quantity) - ($unit->purchase_price * $unit->pivot->quantity);
                // }

            }


        }else{
            foreach ($date_fil_lists as $lists) {

                $total_sales += $lists->total_price;

                // foreach ($lists->counting_unit as $unit) {

                //     $total_profit += ($unit->pivot->price * $unit->pivot->quantity) - ($unit->purchase_price * $unit->pivot->quantity);
                // }

            }

        }
        
        // foreach($order_lists as $order){
        //     $total_order += $order->est_price;
        // }

        $purchase_res = 0;

        foreach($total_purchases as $val){
            $purchase_res += $val->price;
        }

        $expense_res = 0;

        foreach($total_expenses as $val){
            $expense_res += $val->amount;
        }

        return response()->json([
            "total_sales" => $total_sales,
            "total_consumptions" => $total_consumptions,
            "total_profit" => $total_profit,
            "total_purchases" => $total_purchases,
            "total_purchase" => $total_purchase,
            "total_expenses" => $total_expenses,
            "total_transaction" => $total_transaction,
            "voucher_lists" => $voucher_lists,
            // "order_lists" => $order_lists,
            // "purchase_lists" => $purchase_lists,
            // "transaction_lists" => $transaction_lists,
            "total_order" => $total_order,
            "other_incomes" => $other_income,
            "other_expenses" => $other_expense,
            "date_fil_lists" => $date_fil_lists,
            "consume" => $consume,
            "purchase" => $purchase_res,
            "expense" => $expense_res,
            "income" => $income,
            "total_incomes" => $total_incomes,
        ]);

        
    }

    protected function getSalesReport(){
        $adults = 0;
        $children = 0;
        $kids = 0;
        $extra_pots = 0;
        $extra_grams = 0;
        $extra_amount = 0;
        $discount_amount = 0;

        $adult_amount = 0;
        $children_amount = 0;
        $kid_amount = 0;
        $extra_pot_amount = 0;
        $first_total = 0;
        $second_total = 0;

        $shop_orders = ShopOrder::whereDate('created_at', date('Y-m-d'))->get();
        $vouchers = Voucher::whereDate('voucher_date', date('Y-m-d'))->get();
        
        foreach($shop_orders as $order){
            $adults += $order->adult_qty;
            $children += $order->child_qty;
            $kids += $order->kid_qty;
            $extra_pots += $order->extrapot_qty;
        }

        $adult_amount += $adults * 20900;
        $children_amount += $children * 11000;
        $kid_amount += $kids * 9000;
        $extra_pot_amount += $extra_pots * 3000;

        foreach($vouchers as $voucher){
            $extra_grams += $voucher->extra_gram;
            $extra_amount += $voucher->extra_amount;
        }

        $first_total += ($adult_amount + $children_amount + $kid_amount + $extra_pot_amount + $extra_amount);

        $service_charge = $first_total * 0.05;

        $second_total = $first_total + $service_charge;

        return view('Admin.sales_report', compact('adults', 'children', 'kids', 'extra_pots', 'extra_grams', 'extra_amount', 'first_total', 'service_charge', 'second_total'));
    }

    protected function getSaleReportDate(Request $request){
        $daily = $request->start_date;
        $adults = 0;
        $children = 0;
        $kids = 0;
        $extra_pots = 0;
        $extra_grams = 0;
        $extra_amount = 0;
        $discount_amount = 0;

        $adult_amount = 0;
        $children_amount = 0;
        $kid_amount = 0;
        $extra_pot_amount = 0;
        $first_total = 0;
        $second_total = 0;

        $shop_orders = ShopOrder::whereDate('created_at', $daily)->get();
        $vouchers = Voucher::whereDate('voucher_date', $daily)->get();
        
        foreach($shop_orders as $order){
            $adults += $order->adult_qty;
            $children += $order->child_qty;
            $kids += $order->kid_qty;
            $extra_pots += $order->extrapot_qty;
        }

        $adult_amount += $adults * 20900;
        $children_amount += $children * 11000;
        $kid_amount += $kids * 9000;
        $extra_pot_amount += $extra_pots * 3000;

        foreach($vouchers as $voucher){
            $extra_grams += $voucher->extra_gram;
            $extra_amount += $voucher->extra_amount;
        }

        $first_total += ($adult_amount + $children_amount + $kid_amount + $extra_pot_amount + $extra_amount);

        $service_charge = $first_total * 0.05;

        $second_total = $first_total + $service_charge;

        return response()->json([
            "adults" => $adults, 
            "children" => $children, 
            "kids" => $kids, 
            "extra_pots" => $extra_pots, 
            "extra_grams" => $extra_grams, 
            "extra_amount" => $extra_amount, 
            "first_total" => $first_total, 
            "service_charge" => $service_charge, 
            "second_total" => $second_total
        ]);
    }
}
