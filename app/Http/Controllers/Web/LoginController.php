<?php

namespace App\Http\Controllers\Web;

use Auth;
use Session;
use App\User;
use App\Table;
use App\Expense;
use App\Voucher;
use App\MenuItem;
use App\Purchase;
use App\TableType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TotalConsumption;
use App\TotalPurchase;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    protected function index(Request $request) {

        if (Session::has('user')) {

            if($request->session()->get('user')->role_flag == 1){
        //         $table_lists = Table::orderBy('table_type_id', 'ASC')->get();
        // $table4n1 = Table::where('table_type_id', 2)->skip(0)->take(6)->get();
        // $table4n2 = Table::where('table_type_id', 2)->skip(6)->take(6)->get();

		// $table_types = TableType::all();

		// return view('Sale.sale_page', compact('table_lists','table4n1','table4n2','table_types'));

        $voucher = Voucher::where('status',0)->get();
        $purchase = TotalPurchase::all();
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
                $total_inventory += $pur->price;
            }
            foreach($expense as $exp){
                $total_expense += $exp->amount;
            }
            $daily_purchases = TotalPurchase::whereDate('created_at', date('Y-m-d'))->get();
            $daily_consumptions = TotalConsumption::whereDate('created_at', date('Y-m-d'))->get();

            //total expense - total purchase for total expense amount
            $total_expense += $total_inventory;

            $total_purchases = 0;
            $total_consumptions = 0;

            foreach($daily_purchases as $val){
                $total_purchases += $val->price;
            }

            foreach($daily_consumptions as $val){
                $total_consumptions += $val->price;
            }

            $shop_vouchers = Voucher::whereDate('voucher_date', date('Y-m-d'))->where('type', 1)->get();
            $total_shop = 0;
            $total_take_away = 0;

            foreach($shop_vouchers as $val){
                $total_shop += $val->total_price;
            }

            $take_away_vouchers = Voucher::whereDate('voucher_date', date('Y-m-d'))->where('type', 2)->get();
 
            foreach($take_away_vouchers as $val){
                $total_take_away += $val->total_price;
            }

            $menu = MenuItem::all()->count();
            return view('report',compact('total_sale','today_sale','total_inventory','menu','total_expense', 'total_purchases', 'total_consumptions', 'total_shop', 'total_take_away'));

            }elseif ($request->session()->get('user')->role_flag == 4) {

                return redirect()->route('inven_dashboard');

            }
            elseif ($request->session()->get('user')->role_flag == 2) {

                dd("Hello");

            }
        }
        else{
            // dd('hello');
            return view('login');

        }

	}

    protected function loginProcess(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong! Validation Error!');

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!isset($user)) {

            alert()->error('Wrong Email!');

            return redirect()->back();
        }
        elseif (!\Hash::check($request->password, $user->password)) {

            alert()->error('Wrong Password!');

            return redirect()->back();
        }

        session()->put('user', $user);

        if ($user->role_flag == 1 || $user->role_flag == 2 || $user->role_flag == 4 || $user->role_flag == 5 || $user->role_flag == 6) {

            alert()->success("Successfully Login");

            return redirect()->route('index');
        }
        elseif ($user->role_flag == 3) {

            alert()->success("Successfully Login");

            return redirect()->route('shop_order_panel');

        }
        // elseif ($user->role_flag == 4) {

        //     alert()->success("Successfully Login");

        //     return redirect()->route('inven_dashboard');

        // }
        else{

            Session::flush();

            return redirect()->route('index');

        }

    }

    protected function logoutProcess(Request $request){

        Session::flush();

        alert()->success("Successfully Logout");

        return redirect()->route('index');

    }

    protected function getChangePasswordPage(){

        return view('change_pw');
    }

    protected function updatePassword(Request $request){

        $validator = Validator::make($request->all(), [
            'current_pw' => 'required',
            'new_pw' => 'required|confirmed|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/'
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong!');
            return redirect()->back()->withErrors($validator);

        }

        $user = $request->session()->get('user');

        $current_pw = $request->current_pw;

        if(!\Hash::check($current_pw, $user->password)){

            alert()->info("Wrong Current Password!");

            return redirect()->back();
        }

        $has_new_pw = \Hash::make($request->new_pw);

        $user->password = $has_new_pw;

        $user->save();

        alert()->success('Successfully Changed!');

        return redirect()->route('admin_dashboard');
    }
}
