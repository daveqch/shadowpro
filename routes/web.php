<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

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


Route::resources([
    'irr' => App\Http\Controllers\IrrController::class,
]);

Route::get('/lang', [
    'uses' => 'App\Http\Controllers\HomeController@lang',
    'as' => 'lang.index'
]);

Route::get('/', function () {
    try {
        DB::connection()->getPdo();
        if (!Schema::hasTable('application_settings'))
            return redirect('/install');
    } catch (\Exception $e) {
        return redirect('/install');
    }
    return redirect('dashboard');
});

Route::get('/clear', function () {
    $exitCode = Artisan::call('config:clear');
    echo $exitCode;
});


Auth::routes(['register' => false]);

Route::get('/install', [
    'uses' => 'App\Http\Controllers\InstallController@index',
    'as' => 'install.index'
]);

Route::post('/install', [
    'uses' => 'App\Http\Controllers\InstallController@install',
    'as' => 'install.install'
]);


Route::group(['middleware' => ['auth']], function () {
    Route::get('/company/companyAccountSwitch', [
        'uses' => 'App\Http\Controllers\CompanyController@companyAccountSwitch',
        'as' => 'company.companyAccountSwitch'
    ]);

    Route::get('/bill/getAddPaymentDetails', [
        'uses' => 'App\Http\Controllers\BillController@getAddPaymentDetails',
        'as' => 'bill.getAddPaymentDetails'
    ]);

    Route::post('/bill/addPaymentStore', [
        'uses' => 'App\Http\Controllers\BillController@addPaymentStore',
        'as' => 'bill.addPaymentStore'
    ]);

    Route::get('/invoice/getAddPaymentDetails', [
        'uses' => 'App\Http\Controllers\InvoiceController@getAddPaymentDetails',
        'as' => 'invoice.getAddPaymentDetails'
    ]);

    Route::post('/invoice/addPaymentStore', [
        'uses' => 'App\Http\Controllers\InvoiceController@addPaymentStore',
        'as' => 'invoice.addPaymentStore'
    ]);

    Route::get('/report/income', [
        'uses' => 'App\Http\Controllers\ReportController@income',
        'as' => 'report.income'
    ]);

    Route::get('/report/expense', [
        'uses' => 'App\Http\Controllers\ReportController@expense',
        'as' => 'report.expense'
    ]);

    Route::get('/report/incomeVsexpense', [
        'uses' => 'App\Http\Controllers\ReportController@incomeVsexpense',
        'as' => 'report.incomeVsexpense'
    ]);

    Route::get('/report/profitAndloss', [
        'uses' => 'App\Http\Controllers\ReportController@profitAndloss',
        'as' => 'report.profitAndloss'
    ]);

    Route::get('/report/tax', [
        'uses' => 'App\Http\Controllers\ReportController@tax',
        'as' => 'report.tax'
    ]);

    Route::get('salary/individualSalary', 'App\Http\Controllers\ReportController@individualSalary')->name('salary.individualSalary');
    Route::post('salary/actionIndividualSalary', 'App\Http\Controllers\ReportController@actionIndividualSalary')->name('salary.actionIndividualSalary');

    Route::get('/salary/branchSalary', 'App\Http\Controllers\ReportController@branchSalary')->name('salary.branchSalary');

    Route::post('/salary/actionbranchSalary', 'App\Http\Controllers\ReportController@actionbranchSalary')->name('salary.actionbranchSalary');

    Route::get('users/{user}/read-items', 'App\Http\Controllers\UserController@readItemsOutOfStock');

    Route::post('loan/loanStatus', 'App\Http\Controllers\LoanController@loanStatus')->name('loan.loanStatus');
    Route::get('loan-receive', 'App\Http\Controllers\LoanController@loanReceiveList')->name('loan-receive');
    Route::get('loan-receive/adjustment/{id}', 'App\Http\Controllers\LoanController@adjustmentLoan')->name('loan-receive.adjustment');
    Route::post('loan/receiveLoanAction', 'App\Http\Controllers\LoanController@receiveLoanAction')->name('loan.receiveLoanAction');
    Route::get('loan/view-loan-action/{id}', 'App\Http\Controllers\LoanController@viewLoanAction')->name('loan.viewLoanAction');

    Route::get('/loan/selectedEmployeeData', 'App\Http\Controllers\LoanController@selectedEmployeeData');

    Route::get(
        '/employee/uniqueEidCheck',
        [
            'uses' => 'App\Http\Controllers\EmployeeController@uniqueEidCheck',
            'as' => 'employee.uniqueEidCheck'
        ]
    );

    Route::post('advance-salary/status', 'App\Http\Controllers\AdvanceSalaryController@advanceSalaryStatus')->name('advance-salary.status');
    Route::get('/attendance', 'App\Http\Controllers\AttendanceController@index')->name('attendance.index');
    Route::post('attendance/updateAttendanceAction', 'App\Http\Controllers\AttendanceController@storeUpdate');
    Route::get('attendance/getInTimeOutTime', 'App\Http\Controllers\AttendanceController@getInTimeOutTime');
    Route::get('attendance/selectedLeaveType', 'App\Http\Controllers\AttendanceController@selectedLeaveType');
    Route::get('/attendance/dayWiseAttendance', 'App\Http\Controllers\AttendanceController@dayWiseAttendance')->name('attendance.dayWiseAttendance');
    Route::get('/attendance/employeeWiseAttendance', 'App\Http\Controllers\AttendanceController@employeeWiseAttendance')->name('attendance.employeeWiseAttendance');
    Route::get('/attendance/selectedEmployeeData', 'App\Http\Controllers\AttendanceController@selectedEmployeeData')->name('attendance.selectedEmployeeData');

    Route::get('/salary/selectedEmployeeData', 'App\Http\Controllers\SalaryController@selectedEmployeeData');

    Route::resources([
        'roles' => App\Http\Controllers\RoleController::class,
        'users' => App\Http\Controllers\UserController::class,
        'customer' => App\Http\Controllers\CustomerController::class,
        'revenue' => App\Http\Controllers\RevenueController::class,
        'vendor' => App\Http\Controllers\VendorController::class,
        'payment' => App\Http\Controllers\PaymentController::class,
        'currency' => App\Http\Controllers\CurrencyController::class,
        'category' => App\Http\Controllers\CategoryController::class,
        'tax' => App\Http\Controllers\TaxController::class,
        'smtp' => App\Http\Controllers\SmtpConfigurationController::class,
        'company' => App\Http\Controllers\CompanyController::class,
        'invoice' => App\Http\Controllers\InvoiceController::class,
        'bill' => App\Http\Controllers\BillController::class,
        'item' => App\Http\Controllers\ItemController::class,
        'account' => App\Http\Controllers\AccountController::class,
        'transfer' => App\Http\Controllers\TransferController::class,
        'transaction' => App\Http\Controllers\TransactionController::class,
        'offline-payment' => App\Http\Controllers\OfflinePaymentController::class,
        'salary' => App\Http\Controllers\SalaryController::class,
        'location' => App\Http\Controllers\LocationController::class,
        'branch' => App\Http\Controllers\BranchController::class,
        'department' => App\Http\Controllers\DepartmentController::class,
        'designation' => App\Http\Controllers\DesignationController::class,
        'notice' => App\Http\Controllers\NoticeController::class,
        'policy' => App\Http\Controllers\PolicyController::class,
        'employee' => App\Http\Controllers\EmployeeController::class,
        'leave' => App\Http\Controllers\LeaveController::class,
        'loan' => App\Http\Controllers\LoanController::class,
        'advance-salary' => App\Http\Controllers\AdvanceSalaryController::class,
    ]);



    Route::get('/getItems', 'App\Http\Controllers\InvoiceController@getItems')->name('invoice.getItems');
    Route::get('/getBillItems', 'App\Http\Controllers\BillController@getBillItems')->name('bill.getBillItems');
    //Route::get('/getProduct', 'App\Http\Controllers\ProductController@getProduct')->name('product.getProduct');

    Route::post('/invoice/generateItemData', [
        'uses' => 'App\Http\Controllers\InvoiceController@generateItemData',
        'as' => 'invoice.generateItemData'
    ]);

    Route::get('/c/c', [App\Http\Controllers\CurrencyController::class, 'code'])->name('currency.code');

    Route::get('/profile/setting', [
        'uses' => 'App\Http\Controllers\ProfileController@setting',
        'as' => 'profile.setting'
    ]);

    Route::post('/profile/updateSetting', [
        'uses' => 'App\Http\Controllers\ProfileController@updateSetting',
        'as' => 'profile.updateSetting'
    ]);
    Route::get('/profile/password', [
        'uses' => 'App\Http\Controllers\ProfileController@password',
        'as' => 'profile.password'
    ]);

    Route::post('/profile/updatePassword', [
        'uses' => 'App\Http\Controllers\ProfileController@updatePassword',
        'as' => 'profile.updatePassword'
    ]);
    Route::get('/profile/view', [
        'uses' => 'App\Http\Controllers\ProfileController@view',
        'as' => 'profile.view'
    ]);
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', [
        'uses' => 'App\Http\Controllers\DashboardController@index',
        'as' => 'dashboard'
    ]);
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/appsetting', [
        'uses' => 'App\Http\Controllers\ApplicationSettingController@index',
        'as' => 'appsetting'
    ]);

    Route::post('/appsetting/update', [
        'uses' => 'App\Http\Controllers\ApplicationSettingController@update',
        'as' => 'appsetting.update'
    ]);
});

// general Setting
Route::group(['middleware' => ['auth']], function () {

    Route::get('/general', [
        'uses' => 'App\Http\Controllers\GeneralController@index',
        'as' => 'general'
    ]);

    Route::post('/general', [
        'uses' => 'App\Http\Controllers\GeneralController@edit',
        'as' => 'general'
    ]);

    Route::post('/general/localisation', [
        'uses' => 'App\Http\Controllers\GeneralController@localisation',
        'as' => 'general.localisation'
    ]);

    Route::post('/general/invoice', [
        'uses' => 'App\Http\Controllers\GeneralController@invoice',
        'as' => 'general.invoice'
    ]);

    Route::post('/general/bill', [
        'uses' => 'App\Http\Controllers\GeneralController@bill',
        'as' => 'general.bill'
    ]);

    Route::post('/general/defaults', [
        'uses' => 'App\Http\Controllers\GeneralController@defaults',
        'as' => 'general.defaults'
    ]);
});

Route::get('/home', function () {
    return redirect()->to('dashboard');
});
