<?php

use App\ShopOrder;
use App\Events\Test;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Route;


Route::get('/agent', function () {
    $agent = new Agent();
    $mobile_print = ShopOrder::where("is_mobile",1)->orderBy('id','desc')->first();
    dd($mobile_print);
    if($agent->isDesktop()){
        dd('Mobile');
    }else if($agent->isMobile()){
        dd('Desktop');
    }
});
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'Web\LoginController@index')->name('index');

Route::post('Authenticate', 'Web\LoginController@loginProcess')->name('loginProcess');

Route::get('LogoutProcess', 'Web\LoginController@logoutProcess')->name('logoutprocess');

Route::group(['middleware' => ['UserAuth']], function () {

    Route::get('ChangePassword-UI', 'Web\LoginController@getChangePasswordPage')->name('change_password_ui');
    Route::put('UpdatePassword', 'Web\LoginController@updatePassword')->name('update_pw');

    //Dashboard List
    Route::get('Inventory-Dashboard', 'Web\InventoryController@getInventoryDashboard')->name('inven_dashboard');
    Route::get('Order-Dashboard', 'Web\OrderController@getOrderPanel')->name('order_panel');
    Route::get('Admin-Dashboard','Web\AdminController@getAdminDashboard')->name('admin_dashboard');
    Route::get('Shop-Order-Dashboard','Web\SaleController@getShopOrderPanel')->name('shop_order_panel');

    //Mobile Print
    Route::post('mobile-print','Web\AdminController@mobileprint');
    Route::post('add-mobile-print','Web\AdminController@addmobileprint');
    /*
    Route::get('Stock-Dashboard', 'Web\StockController@getStockPanel')->name('stock_dashboard');
    Route::get('Sale-Dashboard', 'Web\SaleController@getSalePanel')->name('sale_panel');
    */

    /* //Ajax List
    Route::post('AjaxGetItem', 'Web\InventoryController@AjaxGetItem')->name('AjaxGetItem');
    Route::post('AjaxGetCountingUnit', 'Web\InventoryController@AjaxGetCountingUnit')->name('AjaxGetCountingUnit');
    Route::post('getCountingUnitsByItemCode', 'Web\SaleController@getCountingUnitsByItemCode');
    Route::post('ajaxConvertResult', 'Web\InventoryController@ajaxConvertResult');*/

    Route::post('getCountingUnitsByItemId', 'Web\SaleController@');
    Route::post('searchByCuisine', 'Web\SaleController@searchByCuisine');
    Route::post('getTableByFloor', 'Web\SaleController@getTableByFloor');
    Route::post('getTableByTableType', 'Web\SaleController@getTableByTableType');

    //Meal (Finish)
    Route::get('meal', 'Web\InventoryController@getMealList')->name('meal_list');
    Route::post('meal/store', 'Web\InventoryController@storeMeal')->name('meal_store');
    Route::post('meal/update/{id}', 'Web\InventoryController@updateMeal')->name('meal_update');

    //CuisineType (Finish)
    Route::get('cuisine-type', 'Web\InventoryController@getCuisineTypeList')->name('cuisine_type_list');
    Route::post('cuisine-type/store', 'Web\InventoryController@storeCuisineType')->name('cuisine_type_store');
    Route::post('cuisine-type/update/{id}', 'Web\InventoryController@updateCuisineType')->name('cuisine_type_update');

    //Menu Item
    Route::get('menu-item', 'Web\InventoryController@getMenuItemList')->name('menu_item_list');
    Route::post('menu-item/store', 'Web\InventoryController@storeMenuItem')->name('menu_item_store');
    Route::post('menu-item/update/{id}', 'Web\InventoryController@updateMenuItem')->name('menu_item_update');
    Route::post('menu-item/delete', 'Web\InventoryController@deleteMenuItem')->name('menu.delete');


    //Ingredient List
    Route::get('ingredient-list', 'Web\InventoryController@getIngredientList')->name('ingredient_list');
    Route::post('ingredient-list/store', 'Web\InventoryController@storeIngredient')->name('store_ingredient');
    Route::get('unit-ingredient/brake/{id}','Web\InventoryController@changeBrake')->name('brake_status');
    Route::get('unit-ingredient/unbrake/{id}','Web\InventoryController@changeUnbrake')->name('unbrake_status');
    Route::get('unit-ingredient/edit/{id}', 'Web\InventoryController@editUnitIngredient')->name('edit_unit_ingredient');
    Route::post('unit-ingredient/update/{id}', 'Web\InventoryController@updateUnitIngredient')->name('update_unit_ingredient');

    //Customer Complain List
    Route::get('customer_complain', 'Web\InventoryController@getCustomerComplainList')->name('customer_complain_list');
    Route::post('code/store', 'Web\InventoryController@storeCode')->name('code_store');
    Route::post('code/update/{id}', 'Web\InventoryController@updateCode')->name('code_update');

    //State Township List
    Route::get('StateList', 'Web\AdminController@getStateList')->name('state_list');
    Route::post('StoreTown', 'Web\AdminController@storeTown')->name('store_town');
    Route::post('EditTown', 'Web\AdminController@editTown')->name('edit_town');
    Route::post('AjaxSearchTown', 'Web\AdminController@ajaxSearchTown')->name('ajaxSearch');

    //Expense Purchase
    Route::post('AjaxSearchPurchase', 'Web\AdminController@ajaxSearchPurchase')->name('ajaxSearchpurchase');
    Route::post('newOreditPurchase', 'Web\AdminController@newOreditPurchase')->name('newOreditPurchase');
    Route::post('deletePurchaseExpense','Web\AdminController@deletePurchaseExpense')->name('deletePurchaseExpense');
    Route::get('/export-expenses','Web\AdminController@exportExpenses')->name('export-expenses');

    //Promotion List
    Route::get('PromotionList', 'Web\AdminController@getPromotionList')->name('promotion_create');
    Route::post('StorePromotion', 'Web\AdminController@storePromotion')->name('promotion_store');
    Route::get('promotion/delete/{id}', 'Web\AdminController@deletePromotion')->name('promotion.delete');
    Route::post('PromotionCheck', 'Web\AdminController@checkPromotion')->name('promotion_check');

    //Reorder List
    Route::get('reorder-list', 'Web\InventoryController@getReorderList')->name('reorder_list');
    Route::get('stock-update', 'Web\InventoryController@stockCountUpdate')->name('stock_update');
    Route::post('edit_count','Web\InventoryController@upadate_stock');
    Route::post('upadateonlystock','Web\InventoryController@upadate_onlyStock')->name('upadate_onlystock');

    //Counting Unit
    Route::get('Option/{item_id}', 'Web\InventoryController@getOptionList')->name('option_list');
    Route::post('Option/store', 'Web\InventoryController@storeOption')->name('option_store');
    Route::post('Option/update/{id}', 'Web\InventoryController@updateOption')->name('option_update');
    Route::post('Option/delete', 'Web\InventoryController@deleteOption');

    //Order
    Route::get('Order/{type}', 'Web\OrderController@getOrderPage')->name('order_page');
    Route::get('Order-Details/{id}', 'Web\OrderController@getOrderDetailsPage')->name('order_details');
    Route::post('Order/Change', 'Web\OrderController@changeOrderStatus')->name('update_order_status');
    Route::get('Order/Voucher/History', 'Web\OrderController@getOrderHistoryPage')->name('order_history');
    Route::post('Order/Voucher/Search-History', 'Web\OrderController@searchOrderVoucherHistory')->name('search_order_history');

    Route::get('Employee', 'Web\AdminController@getEmployeeList')->name('employee_list');
    Route::post('Employee/store', 'Web\AdminController@storeEmployee')->name('employee_store');
    Route::post('Employee/update', 'Web\AdminController@updateEmployee')->name('employee_update');
    Route::get('Employee/details/{id}', 'Web\AdminController@getEmployeeDetails')->name('employee_details');

    Route::get('Table', 'Web\AdminController@getTableList')->name('table_list');
    Route::post('Table/store', 'Web\AdminController@storeTable')->name('store_table_list');
    Route::post('Table-Type/store', 'Web\Admstore_shop_orderinController@storeTableType')->name('store_table_type');

    Route::post('Table-Type/update/{id}', 'Web\AdminController@updateTableType')->name('update_table_type');

    // Route::get('finicial', 'Web\AdminController@getFinicial')->name('getfinicial');
    Route::post('getTotalSaleReport', 'Web\AdminController@getTotalSaleReport');
    Route::post('getTotalExpense', 'Web\AdminController@getTotalExpense');
    Route::get('expense', 'Web\AdminController@getExpense')->name('expense');
    Route::post('storeExpense', 'Web\AdminController@storeExpense')->name('store_expense');
    Route::post('deleteExpense/{id}', 'Web\AdminController@deleteExpense')->name('delete_expense');
    Route::post('get-sale-record', 'Web\AdminController@getSaleRecord')->name('get_sale_record');
    Route::get('sale-record', 'Web\AdminController@saleRecord')->name('sale_record');

    //notification
    Route::post('getnotification', 'Web\SaleController@notification')->name('getnotification');


    Route::get('Pending-Order', 'Web\SaleController@getPendingShopOrderList')->name('pending_lists');
    Route::get('Take-Away-Pending-Order', 'Web\SaleController@getPendingTakeAwayOrderList')->name('delivery_pending_lists');
    Route::get('Pending-Order-Details/{id}', 'Web\SaleController@getPendingShopOrderDetails')->name('pending_order_details');

    //pending take away detail
    Route::get('Pending-Take-Away-Details/{id}', 'Web\SaleController@getPendingTakeAwayDetails')->name('pending_take_away_details');

    Route::get('Delivery_Pending-Order-Details/{id}', 'Web\SaleController@getPendingDeliveryOrderDetails')->name('deli_pending_order_details');
    Route::get('Finished-Order', 'Web\SaleController@getFinishedOrderList')->name('finished_lists');
    Route::post('Finished-Order-DateFilter', 'Web\SaleController@getFilterFinishedOrderList')->name('filter_finished_lists');


    Route::post('Finished-Consumption-DateFilter', 'Web\SaleController@getFilterFinishedConsumptionList');


    Route::get('Shop-Order-Voucher/{order_id}', 'Web\SaleController@getShopOrderVoucher')->name('shop_order_voucher');

    Route::get('Take-Away-Order-Voucher/{order_id}', 'Web\SaleController@getTakeAwayOrderVoucher')->name('take_away_order_voucher');

    //Voucher list with date filter
    Route::post('Finished-Voucher-DateFilter', 'Web\SaleController@getFilteredVoucher');

    Route::get('Delivery-Order-Voucher/{order_id}', 'Web\SaleController@getDeliveryOrderVoucher')->name('delivery_order_voucher');
    Route::get('gotopending','Web\SaleController@gotopendinglists')->name('gotopendinglist');
    Route::get('Order-Voucher/{order_id}', 'Web\SaleController@getOrderVoucher')->name('order_voucher');

    // Manager Dashboard
    Route::post('getOrderFullfill', 'Web\AdminController@getTotalOrderFulfill');
    Route::post('getmonthpie', 'Web\AdminController@getmonthpie');
    Route::post('getWeekNowFamous', 'Web\AdminController@getWeekNowFamous_Menu');
    Route::post('getFamousWeek', 'Web\AdminController@getFamousWeek_data');
    Route::get('report','Web\AdminController@managerDashboard')->name('report');

    Route::post('getWeekNowUnFamous', 'Web\AdminController@getWeekNowUnFamous_Menu');

    //qrcode //Start Thai Pot
    Route::get('qrcode', 'Web\SaleController@getQrcodePage')->name('qrcode');
    Route::post('StoreThaiOrder', 'Web\SaleController@storeThaiShopOrder')->name('store_thai_shop_order');
    Route::post('EditThaiOrder', 'Web\SaleController@editThaiShopOrder')->name('edit_thai_shop_order');


    Route::get('Sale', 'Web\SaleController@getSalePage')->name('sale_page');
    Route::post('Sale/Store', 'Web\SaleController@storeShopOrder')->name('store_shop_order');
    Route::post('save_note','Web\SaleController@NoteSave');
    Route::get('kitchen_list','Web\SaleController@show_kitchen')->name('kitchen_list');

    Route::post('storedelivery','Web\OrderController@storedelivery')->name('storedelivery');
    Route::get('Sale/Shop-Order/{table_id}', 'Web\SaleController@getShopOrderSalePage')->name('shop_order_sale');
    // Route::post('shopOrdeli','Web\SaleController@getShopDeli');
    // Route::get('delivery', 'Web\SaleController@takeAwayPage')->name('take_away');
    Route::get('take_away', 'Web\SaleController@takeAwayPage')->name('take_away');

    Route::get('Add-More/take_away/{id}', 'Web\SaleController@takeAwayPage1');

    Route::post('searchDelicharges','Web\SaleController@searchDelicharges');

     //Soup Kitchen Route
    Route::post('Kitchen-Soup', 'Web\SaleController@soupkitchen')->name('soup_kitchen');

    Route::get('Add-More/{order_id}', 'Web\SaleController@addMoreItemUI')->name('add_more_item');
    Route::get('Delivery-Add-More/{order_id}', 'Web\SaleController@deliaddMoreItemUI')->name('deli_add_more_item');
    Route::post('Add-More-Item', 'Web\SaleController@addMoreItem')->name('add_item');
    Route::post('Deli-Add-More-Item', 'Web\SaleController@deliaddMoreItem')->name('deli_add_item');

    Route::post('ShopVoucherStore', 'Web\SaleController@storeShopOrderVoucher')->name('shop.ordervoucher');

    Route::post('TakeAwayVoucherStore', 'Web\SaleController@storeTakeAwayVoucher');

    Route::post('DeliveryVoucherStore', 'Web\SaleController@storeDeliveryOrderVoucher')->name('delivery.ordervoucher');
    Route::post('DiscountForm', 'Web\SaleController@storeShopDiscountForm')->name('shop.discountform');

    Route::post('TakeAwayDiscountForm', 'Web\SaleController@storeTakeAwayDiscountForm');

    Route::post('DeliveryDiscountForm', 'Web\SaleController@storeDeliveryDiscountForm')->name('shop.discountform');
    Route::get('Purchase', 'Web\PurchaseController@getPurchaseHistory')->name('purchase_list');
    Route::get('Purchase/Details/{id}', 'Web\PurchaseController@getPurchaseHistoryDetails')->name('purchase_details');
    Route::get('Purchase/Create', 'Web\PurchaseController@createPurchaseHistory')->name('create_purchase');
    Route::post('Purchase/Store', 'Web\PurchaseController@storePurchaseHistory')->name('store_purchase');

    //voucher history
    Route::get('shop_voucher/{id}', 'Web\SaleController@getShopVoucherDetail')->name('shop_voucher');
    Route::get('shop_voucher1/{id}', 'Web\SaleController@getShopVoucherDetail1')->name('shop_voucher1');

    //purchase item
    Route::get('pi_category', 'Web\InventoryController@getPiCategoryList')->name('pi_category_list');
    Route::post('pi_category/store', 'Web\InventoryController@storePiCategory')->name('pi_category_store');
    Route::post('pi_category/update/{id}', 'Web\InventoryController@updatePiCategory')->name('pi_category_update');

    Route::get('purchase_item', 'Web\InventoryController@getPurchaseItemList')->name('purchase_item_list');
    Route::post('purchase_item/store', 'Web\InventoryController@storePurchaseItem')->name('purchase_item_store');
    Route::post('purchase_item/update/{id}', 'Web\InventoryController@updatePurchaseItem')->name('purchase_item_update');

    //take away
    Route::get('TakeAwayDiscountForm', 'Web\SaleController@storeTakeAwayDiscountForm');

    //daily purchase
    Route::get('daily_purchase', 'Web\AdminController@getDailyPurchase')->name('daily_purchase');
    Route::get('daily_purchase/create', 'Web\AdminController@createDailyPurchase');
    Route::post('purchase_subcategory_search', 'Web\AdminController@purchaseSubCategorySearch');
    Route::post('purchase_item_search', 'Web\AdminController@purchaseItemSearch');
    //add daily purchase
    Route::post('add_daily_purchase', 'Web\AdminController@addDailyPurchase')->name('add_daily_purchase');
    Route::get('search_item/{id}', 'Web\AdminController@searchItem');
    Route::post('purchaseprice/update', 'Web\AdminController@purchasepriceUpdate')->name('purchasepriceupdate');
    Route::post('Purchase/Store', 'Web\AdminController@storePurchaseHistory')->name('store_purchase');
    //daily purchase detail
    Route::get('Purchase/Details/{id}', 'Web\AdminController@getPurchaseHistoryDetails')->name('purchase_details');

    //daily consumption
    Route::get('daily_consumption', 'Web\AdminController@getDailyConsumption')->name('daily_consumption');
    Route::get('daily_consumption/create', 'Web\AdminController@createDailyConsumption');
    Route::post('Consumption/Store', 'Web\AdminController@storeConsumptionHistory')->name('store_consumption');
    //daily purchase detail
    Route::get('Consumption/Details/{id}', 'Web\AdminController@getConsumptionHistoryDetails')->name('consumption_details');

    //daily income list
    Route::get('Incomes', 'Web\AdminController@incomeList')->name('incomes');
    Route::post('storeIncome', 'Web\AdminController@storeIncome')->name('store_income');
    Route::post('updateIncome/{id}', 'Web\AdminController@updateIncome')->name('update_income');
    Route::post('deleteIncome', 'Web\AdminController@deleteIncome')->name('delete_income');
    Route::post('getTotalSaleReport', 'Web\AdminController@getTotalSaleReport');

    //financial
    Route::get('Financial', 'Web\AdminController@getTotalSalenAndProfit')->name('financial');

    //Customer
    /* Route::get('Customer', 'Web\AdminController@getCustomerList')->name('customer_list');
    Route::post('Customer/store', 'Web\AdminController@storeCustomer')->name('store_customer');
    Route::get('Customer/details/{id}', 'Web\AdminController@getCustomerDetails')->name('customer_details');
    Route::post('Customer/Change-Level', 'Web\AdminController@changeCustomerLevel')->name('change_customer_level');*/

    //Daily Sales Report
    Route::get('daily_sales_report', 'Web\AdminController@getSalesReport')->name('daily_sales_report');
    // Sales-Report-DateFilter
    Route::post('Sales-Report-DateFilter', 'Web\AdminController@getSaleReportDate');

    Route::post('edit_ingredient','Web\InventoryController@editIngredient');
    Route::post('update_ingredient','Web\InventoryController@store_updateIngredient')->name('update_ingre');
    Route::get('kitchen-voucher/{id}','Web\SaleController@toKitchenVoucher')->name('kitchen.voucher');
    Route::get('kitchen-addmore-voucher/{id}','Web\SaleController@toKitchenAddMore')->name('kitchen.addvoucher');

    // waiter
    Route::post('waiterdone','Web\SaleController@done');
    Route::get('cancelorder/{id}','Web\SaleController@cancelorder')->name('cancelorder');
    Route::get('canceldetail/{order_id}/{option_id}','Web\SaleController@canceldetail')->name('canceldetail');
    Route::get('canceldelidetail/{order_id}/{option_id}','Web\SaleController@canceldelidetail')->name('canceldelidetail');
    Route::post('Voucher-Cancel','Web\SaleController@cancelvoucher');
});


    Route::get('/pusher',function(){
        event (new App\Events\OrderNoti(189,0));
        return "event successful";
    });
