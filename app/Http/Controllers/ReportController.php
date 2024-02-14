<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Bill;
use App\Models\Loan;
use App\Models\Branch;
use App\Models\Salary;
use App\Models\Vendor;
use App\Models\Account;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Revenue;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\LoansPay;
use App\Traits\DateTime;
use App\Models\Attendance;
use App\Models\BillPayment;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\AdvanceSalary;
use App\Models\InvoicePayment;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class ReportController extends Controller
{

    use DateTime;

    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:income-report-read', ['only' => ['income']]);
        $this->middleware('permission:expense-report-read', ['only' => ['expense']]);
        $this->middleware('permission:income-expense-report-read', ['only' => ['incomeVsexpense']]);
        $this->middleware('permission:tax-report-read', ['only' => ['tax']]);
        $this->middleware('permission:profit-loss-report-read', ['only' => ['profitAndloss']]);
        $this->middleware('permission:individual-salary-report-read', ['only' => ['individualSalary', 'actionIndividualSalary']]);
        $this->middleware('permission:branch-salary-report-read', ['only' => ['branchSalary', 'actionbranchSalary']]);
    }

    public function income(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $totals = $incomes = $incomes_graph = $categories = [];

        ($request->year) ?  $year = $request->year : $year = Carbon::now()->year;

        ($request->status) ?  $status = $request->status : $status = 'all';

        $financial_start = $this->getFinancialStart();
        if ($financial_start->month != 1) {
            if (!is_null($request->year)) {
                $financial_start->year = $year;
            }
            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subMonth();
        }

        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');

        if ($categories_filter = $request->categories) {
            $cats = collect($categories)->filter(function ($value, $key) use ($categories_filter) {
                return in_array($key, $categories_filter);
            });
        } else {
            $cats = $categories;
        }

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addMonth()->format('Y-m') : $year . '-' . $j;
            $dates[$j] = Carbon::parse($ym_string)->format('F');
            $incomes_graph[Carbon::parse($ym_string)->format('F-Y')] = 0;
            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            );

            foreach ($cats as $category_id => $category_name) {
                $incomes[$category_id][$dates[$j]] = [
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                ];
            }
        }

        $revenues = Revenue::monthsOfYear('paid_at')->isNotTransfer()->get();
        if ($request->accounts)
            $revenues = $revenues->where('account_id', $request->accounts);

        switch ($status) {
            case 'paid':
                // Invoices
                $invoices = InvoicePayment::monthsOfYear('paid_at')->get();
                if ($request->accounts)
                    $revenues->where('account_id', $request->accounts);
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'paid_at');

                // Revenues
                $this->setAmount($incomes_graph, $totals, $incomes, $revenues, 'revenue', 'paid_at');
                break;
            default:
                // Invoices
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->get();
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'invoiced_at');

                // Revenues
                $this->setAmount($incomes_graph, $totals, $incomes, $revenues, 'revenue', 'paid_at');
                break;
        }

        $statuses = collect(['all' => 'All', 'paid' => 'Paid']);
        $years = collect(['2020' => '2020', '2021' => '2021', '2022' => '2022', '2023' => '2023', '2024' => '2024', '2025' => '2025']);
        $thisYear = Carbon::now()->year;
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->pluck('name', 'id')->toArray();
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->pluck('name', 'id')->toArray();
        $myMonth = json_encode(array_values($dates));
        $myIncomesGraph = json_encode(array_values($incomes_graph));


        return view('report.income', compact('years', 'thisYear', 'dates', 'categories', 'statuses', 'accounts', 'customers', 'incomes', 'totals', 'company', 'myMonth', 'myIncomesGraph'));
    }

    private function setAmount(&$graph, &$totals, &$incomes, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if ($item->getTable() == 'invoice_payments') {
                $invoice = $item->invoice;

                if ($customers = request('customers')) {
                    if (!in_array($invoice->customer_id, $customers)) {
                        continue;
                    }
                }

                $item->category_id = $invoice->category_id;
            }

            if ($item->getTable() == 'invoices') {
                if ($accounts = request('accounts')) {
                    foreach ($item->payments as $payment) {
                        if (!in_array($payment->account_id, $accounts)) {
                            continue 2;
                        }
                    }
                }
            }

            $month = Carbon::parse($item->$date_field)->format('F');
            $month_year = Carbon::parse($item->$date_field)->format('F-Y');

            if (!isset($incomes[$item->category_id]) || !isset($incomes[$item->category_id][$month]) || !isset($graph[$month_year])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // dd($amount);

            // Forecasting
            if (($type == 'invoice') && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $incomes[$item->category_id][$month]['amount'] += $amount;
            $incomes[$item->category_id][$month]['currency_code'] = $item->currency_code;
            $incomes[$item->category_id][$month]['currency_rate'] = $item->currency_rate;

            $graph[$month_year] += $amount;

            $totals[$month]['amount'] += $amount;
        }
    }

    public function expense(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $totals = $expenses = $expenses_graph = $categories = [];

        ($request->year) ?  $year = $request->year : $year = Carbon::now()->year;
        ($request->status) ?  $status = $request->status : $status = 'all';

        $financial_start = $this->getFinancialStart();
        if ($financial_start->month != 1) {
            if (!is_null($request->year)) {
                $financial_start->year = $year;
            }
            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subMonth();
        }

        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'expense')->orderBy('name')->pluck('name', 'id');


        if ($categories_filter = $request->categories) {
            $cats = collect($categories)->filter(function ($value, $key) use ($categories_filter) {
                return in_array($key, $categories_filter);
            });
        } else {
            $cats = $categories;
        }

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addMonth()->format('Y-m') : $year . '-' . $j;
            $dates[$j] = Carbon::parse($ym_string)->format('F');
            $expenses_graph[Carbon::parse($ym_string)->format('F-Y')] = 0;
            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            );

            foreach ($cats as $category_id => $category_name) {
                $expenses[$category_id][$dates[$j]] = array(
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                );
            }
        }

        $payments = Payment::monthsOfYear('paid_at')->isNotTransfer()->get();
        if ($request->accounts)
            $payments = $payments->where('account_id', $request->accounts);

        switch ($status) {
            case 'paid':
                // Bills
                $bills = BillPayment::monthsOfYear('paid_at')->get();
                if ($request->accounts)
                    $bills = $bills->where('account_id', $request->accounts);
                $this->setExpenseAmount($expenses_graph, $totals, $expenses, $bills, 'bill', 'paid_at');

                // Payments
                $this->setExpenseAmount($expenses_graph, $totals, $expenses, $payments, 'payment', 'paid_at');
                break;
            default:
                // Bills
                $bills = Bill::accrued()->monthsOfYear('billed_at')->get();
                $this->setExpenseAmount($expenses_graph, $totals, $expenses, $bills, 'bill', 'billed_at');

                // Payments
                $this->setExpenseAmount($expenses_graph, $totals, $expenses, $payments, 'payment', 'paid_at');
                break;
        }

        $statuses = collect(['all' => 'All', 'paid' => 'Paid']);
        $years = collect(['2020' => '2020', '2021' => '2021', '2022' => '2022', '2023' => '2023', '2024' => '2024', '2025' => '2025']);
        $thisYear = Carbon::now()->year;
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->pluck('name', 'id')->toArray();
        $vendors = Vendor::where('company_id', session('company_id'))->where('enabled', 1)->pluck('name', 'id')->toArray();
        $myMonth = json_encode(array_values($dates));
        $myExpensesGraph = json_encode(array_values($expenses_graph));

        return view('report.expense', compact('years', 'thisYear', 'dates', 'categories', 'statuses', 'accounts', 'vendors', 'expenses', 'totals', 'company', 'myMonth', 'myExpensesGraph'));
    }

    private function setExpenseAmount(&$graph, &$totals, &$expenses, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if ($item->getTable() == 'bill_payments') {
                $bill = $item->bill;

                if ($vendors = request('vendors')) {
                    if (!in_array($bill->vendor_id, $vendors)) {
                        continue;
                    }
                }

                $item->category_id = $bill->category_id;
            }

            if ($item->getTable() == 'bills') {
                if ($accounts = request('accounts')) {
                    foreach ($item->payments as $payment) {
                        if (!in_array($payment->account_id, $accounts)) {
                            continue 2;
                        }
                    }
                }
            }

            $month = Carbon::parse($item->$date_field)->format('F');
            $month_year = Carbon::parse($item->$date_field)->format('F-Y');

            if (!isset($expenses[$item->category_id]) || !isset($expenses[$item->category_id][$month]) || !isset($graph[$month_year])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if (($type == 'bill') && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $expenses[$item->category_id][$month]['amount'] += $amount;
            $expenses[$item->category_id][$month]['currency_code'] = $item->currency_code;
            $expenses[$item->category_id][$month]['currency_rate'] = $item->currency_rate;

            $graph[$month_year] += $amount;

            $totals[$month]['amount'] += $amount;
        }
    }

    public function incomeVsexpense(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $totals = $compares = $profit_graph = $categories = [];

        ($request->year) ?  $year = $request->year : $year = Carbon::now()->year;
        ($request->status) ?  $status = $request->status : $status = 'all';

        // check and assign year start
        $financial_start = $this->getFinancialStart();

        if ($financial_start->month != 1) {
            // check if a specific year is requested
            if (!is_null(request('year'))) {
                $financial_start->year = $year;
            }

            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subMonth();
        }

        $categories_filter = $request->categories;

        $income_categories = Category::where('enabled', 1)->where('type', 'income')->when($categories_filter, function ($query) use ($categories_filter) {
            return $query->whereIn('id', $categories_filter);
        })->orderBy('name')->pluck('name', 'id')->toArray();

        $expense_categories = Category::where('enabled', 1)->where('type', 'expense')->when($categories_filter, function ($query) use ($categories_filter) {
            return $query->whereIn('id', $categories_filter);
        })->orderBy('name')->pluck('name', 'id')->toArray();

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addMonth()->format('Y-m') : $year . '-' . $j;
            $dates[$j] = Carbon::parse($ym_string)->format('F');
            $profit_graph[Carbon::parse($ym_string)->format('F-Y')] = 0;

            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            );

            foreach ($income_categories as $category_id => $category_name) {
                $compares['income'][$category_id][$dates[$j]] = array(
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                );
            }

            foreach ($expense_categories as $category_id => $category_name) {
                $compares['expense'][$category_id][$dates[$j]] = array(
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                );
            }
        }

        $revenues = Revenue::monthsOfYear('paid_at')->isNotTransfer()->get();
        if ($request->accounts)
            $revenues = $revenues->where('account_id', $request->accounts);

        $payments = Payment::monthsOfYear('paid_at')->isNotTransfer()->get();
        if ($request->accounts)
            $payments = $payments->where('account_id', $request->accounts);

        switch ($status) {
            case 'paid':
                // Invoices
                $invoices = InvoicePayment::monthsOfYear('paid_at')->get();
                if ($request->accounts)
                    $invoices = $invoices->where('account_id', $request->accounts);
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $invoices, 'invoice', 'paid_at');

                // Revenues
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $revenues, 'revenue', 'paid_at');

                // Bills
                $bills = BillPayment::monthsOfYear('paid_at')->get();
                if ($request->accounts)
                    $bills = $bills->where('account_id', $request->accounts);
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $bills, 'bill', 'paid_at');

                // Payments
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $payments, 'payment', 'paid_at');
                break;
            default:
                // Invoices
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->get();
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $invoices, 'invoice', 'invoiced_at');

                // Revenues
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $revenues, 'revenue', 'paid_at');

                // Bills
                $bills = Bill::accrued()->monthsOfYear('billed_at')->get();
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $bills, 'bill', 'billed_at');

                // Payments
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $payments, 'payment', 'paid_at');
                break;
        }

        $statuses = collect(['all' => 'All', 'paid' => 'Paid']);
        $years = collect(['2020' => '2020', '2021' => '2021', '2022' => '2022', '2023' => '2023', '2024' => '2024', '2025' => '2025']);
        $thisYear = Carbon::now()->year;
        $myMonth = json_encode(array_values($dates));
        $myGraph = json_encode(array_values($profit_graph));

        $accounts = Account::where('enabled', 1)->pluck('name', 'id')->toArray();
        $categories = Category::where('enabled', 1)->where('type', ['income', 'expense'])->pluck('name', 'id')->toArray();

        return view('report.income_expense', compact('years', 'thisYear', 'company', 'myMonth', 'myGraph', 'dates', 'income_categories', 'expense_categories', 'categories', 'statuses', 'accounts', 'compares', 'totals'));
    }

    private function setIncomeExpenseAmount(&$graph, &$totals, &$compares, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if (($item->getTable() == 'bill_payments') || ($item->getTable() == 'invoice_payments')) {
                $type_item = $item->$type;
                $item->category_id = $type_item->category_id;
            }

            if ($item->getTable() == 'invoice_payments') {
                $invoice = $item->invoice;

                if ($customers = request('customers')) {
                    if (!in_array($invoice->customer_id, $customers)) {
                        continue;
                    }
                }
                $item->category_id = $invoice->category_id;
            }

            if ($item->getTable() == 'bill_payments') {
                $bill = $item->bill;

                if ($vendors = request('vendors')) {
                    if (!in_array($bill->vendor_id, $vendors)) {
                        continue;
                    }
                }
                $item->category_id = $bill->category_id;
            }

            if (($item->getTable() == 'invoices') || ($item->getTable() == 'bills')) {
                if ($accounts = request('accounts')) {
                    foreach ($item->payments as $payment) {
                        if (!in_array($payment->account_id, $accounts)) {
                            continue 2;
                        }
                    }
                }
            }

            $month = Carbon::parse($item->$date_field)->format('F');
            $month_year = Carbon::parse($item->$date_field)->format('F-Y');

            $group = (($type == 'invoice') || ($type == 'revenue')) ? 'income' : 'expense';

            if (!isset($compares[$group][$item->category_id]) || !isset($compares[$group][$item->category_id][$month]) || !isset($graph[$month_year])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if ((($type == 'invoice') || ($type == 'bill')) && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $compares[$group][$item->category_id][$month]['amount'] += $amount;
            $compares[$group][$item->category_id][$month]['currency_code'] = $item->currency_code;
            $compares[$group][$item->category_id][$month]['currency_rate'] = $item->currency_rate;

            if ($group == 'income') {
                $graph[$month_year] += $amount;

                $totals[$month]['amount'] += $amount;
            } else {
                $graph[$month_year] -= $amount;

                $totals[$month]['amount'] -= $amount;
            }
        }
    }

    private function setTaxAmount(&$items, &$totals, $rows, $type, $date_field)
    {
        foreach ($rows as $row) {
            if (($row->getTable() == 'bill_payments') || ($row->getTable() == 'invoice_payments')) {
                $type_row = $row->$type;
                $row->category_id = $type_row->category_id;
            }

            $date = Carbon::parse($row->$date_field)->format('M');

            ($date_field == 'paid_at') ? $row_totals = $row->$type->totals : $row_totals = $row->totals;

            foreach ($row_totals as $row_total) {
                if ($row_total->code != 'tax') {
                    continue;
                }

                if (!isset($items[$row_total->name])) {
                    continue;
                }

                if ($date_field == 'paid_at') {
                    $rate = ($row->amount * 100) / $type_row->amount;
                    $row_amount = ($row_total->amount / 100) * $rate;
                } else {
                    $row_amount = $row_total->amount;
                }

                $amount = $row->convert($row_amount, $row->currency_code, $row->currency_rate);

                $items[$row_total->name][$date]['amount'] += $amount;

                if ($type == 'invoice') {
                    $totals[$row_total->name][$date]['amount'] += $amount;
                } else {
                    $totals[$row_total->name][$date]['amount'] -= $amount;
                }
            }
        }
    }

    public function tax(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $incomes = $expenses = $totals = [];

        ($request->year) ?  $year = $request->year : $year = Carbon::now()->year;
        ($request->status) ?  $status = $request->status : $status = 'all';

        $financial_start = $this->getFinancialStart();
        if ($financial_start->month != 1) {
            if (!is_null($request->year)) {
                $financial_start->year = $year;
            }
            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subMonth();
        }

        $t = Tax::where('enabled', 1)->where('rate', '<>', '0')->pluck('name')->toArray();
        $taxes = array_combine($t, $t);

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addMonth()->format('Y-m') : $year . '-' . $j;
            $dates[$j] = Carbon::parse($ym_string)->format('M');
            foreach ($taxes as $tax_name) {
                $incomes[$tax_name][$dates[$j]] = [
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1,
                ];
                $expenses[$tax_name][$dates[$j]] = [
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1,
                ];
                $totals[$tax_name][$dates[$j]] = [
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1,
                ];
            }
        }

        switch ($status) {
            case 'paid':
                // Invoices
                $invoices = InvoicePayment::with(['invoice', 'invoice.totals'])->monthsOfYear('paid_at')->get();

                $this->setTaxAmount($incomes, $totals, $invoices, 'invoice', 'paid_at');
                // Bills
                $bills = BillPayment::with(['bill', 'bill.totals'])->monthsOfYear('paid_at')->get();
                $this->setTaxAmount($expenses, $totals, $bills, 'bill', 'paid_at');
                break;
            default:
                // Invoices
                $invoices = Invoice::with(['totals'])->accrued()->monthsOfYear('invoiced_at')->get();
                $this->setTaxAmount($incomes, $totals, $invoices, 'invoice', 'invoiced_at');
                // Bills
                $bills = Bill::with(['totals'])->accrued()->monthsOfYear('billed_at')->get();
                $this->setTaxAmount($expenses, $totals, $bills, 'bill', 'billed_at');
                break;
        }

        $statuses = collect(['all' => 'All', 'paid' => 'Paid']);
        $years = collect(['2020' => '2020', '2021' => '2021', '2022' => '2022', '2023' => '2023', '2024' => '2024', '2025' => '2025']);
        $thisYear = Carbon::now()->year;

        return view('report.tax', compact('years', 'thisYear', 'dates', 'taxes', 'incomes', 'expenses', 'totals', 'statuses', 'company'));
    }

    private function setProfitLossAmount(&$totals, &$compares, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if (($item->getTable() == 'bill_payments') || ($item->getTable() == 'invoice_payments')) {
                $type_item = $item->$type;
                $item->category_id = $type_item->category_id;
            }

            $date = Carbon::parse($item->$date_field)->quarter;

            $group = (($type == 'invoice') || ($type == 'revenue')) ? 'income' : 'expense';

            if (!isset($compares[$group][$item->category_id]))
                continue;

            $amount = $item->getConvertedAmount(false, false);

            // Forecasting
            if ((($type == 'invoice') || ($type == 'bill')) && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $compares[$group][$item->category_id][$date]['amount'] += $amount;
            $compares[$group][$item->category_id][$date]['currency_code'] = $item->currency_code;
            $compares[$group][$item->category_id][$date]['currency_rate'] = $item->currency_rate;
            $compares[$group][$item->category_id]['total']['amount'] += $amount;

            if ($group == 'income') {
                $totals[$date]['amount'] += $amount;
                $totals['total']['amount'] += $amount;
            } else {
                $totals[$date]['amount'] -= $amount;
                $totals['total']['amount'] -= $amount;
            }
        }
    }

    public function profitAndloss(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $totals = $compares = $categories = [];

        ($request->year) ?  $year = $request->year : $year = Carbon::now()->year;
        ($request->status) ?  $status = $request->status : $status = 'all';

        // check and assign year start
        $financial_start = $this->getFinancialStart();

        if ($financial_start->month != 1) {
            // check if a specific year is requested
            if (!is_null(request('year'))) {
                $financial_start->year = $year;
            }

            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subQuarter();
        }

        $income_categories = Category::where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id')->toArray();
        $expense_categories = Category::where('enabled', 1)->where('type', 'expense')->orderBy('name')->pluck('name', 'id')->toArray();

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addQuarter()->format('Y-m') : $year . '-' . $j;
            $dates[$j] = Carbon::parse($ym_string)->quarter;

            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            );

            foreach ($income_categories as $category_id => $category_name) {
                $compares['income'][$category_id][$dates[$j]] = [
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                ];
            }

            foreach ($expense_categories as $category_id => $category_name) {
                $compares['expense'][$category_id][$dates[$j]] = [
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                ];
            }

            $j += 2;
        }

        $totals['total'] = [
            'amount' => 0,
            'currency_code' => $company->default_currency,
            'currency_rate' => 1
        ];

        foreach ($dates as $date) {
            $gross['income'][$date] = 0;
            $gross['expense'][$date] = 0;
        }

        $gross['income']['total'] = 0;
        $gross['expense']['total'] = 0;

        foreach ($income_categories as $category_id => $category_name) {
            $compares['income'][$category_id]['total'] = [
                'category_id' => $category_id,
                'name' => 'Totals',
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            ];
        }

        foreach ($expense_categories as $category_id => $category_name) {
            $compares['expense'][$category_id]['total'] = [
                'category_id' => $category_id,
                'name' => 'Totals',
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            ];
        }

        // Invoices
        switch ($status) {
            case 'paid':
                $invoices = InvoicePayment::monthsOfYear('paid_at')->get();
                $this->setProfitLossAmount($totals, $compares, $invoices, 'invoice', 'paid_at');
                break;
            default:
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->get();
                $this->setProfitLossAmount($totals, $compares, $invoices, 'invoice', 'invoiced_at');
                break;
        }

        // Revenues
        $revenues = Revenue::monthsOfYear('paid_at')->isNotTransfer()->get();
        $this->setProfitLossAmount($totals, $compares, $revenues, 'revenue', 'paid_at');

        // Bills
        switch ($status) {
            case 'paid':
                $bills = BillPayment::monthsOfYear('paid_at')->get();
                $this->setProfitLossAmount($totals, $compares, $bills, 'bill', 'paid_at');
                break;
            default:
                $bills = Bill::accrued()->monthsOfYear('billed_at')->get();
                $this->setProfitLossAmount($totals, $compares, $bills, 'bill', 'billed_at');
                break;
        }

        // Payments
        $payments = Payment::monthsOfYear('paid_at')->isNotTransfer()->get();
        $this->setProfitLossAmount($totals, $compares, $payments, 'payment', 'paid_at');

        $statuses = collect(['all' => 'All', 'paid' => 'Paid']);
        $years = collect(['2020' => '2020', '2021' => '2021', '2022' => '2022', '2023' => '2023', '2024' => '2024', '2025' => '2025']);
        $thisYear = Carbon::now()->year;

        return view('report.profit_loss', compact('years', 'thisYear', 'dates', 'income_categories', 'expense_categories', 'compares', 'totals', 'gross', 'statuses', 'company'));
    }

    public function individualSalary()
    {
        $userId = Auth::user()->id;
        $selectCompanies = Company::orderBy('id', 'DESC')->where('id', Session::get('company_id'))->get();
        $branchs = Branch::orderBy('id', 'DESC')->where('company_id', Session::get('company_id'))->pluck('branch_name', 'id');
        return view(
            'report.individual_salary',
            [
                'selectCompanies' => $selectCompanies,
                'branchs' => $branchs,
                'userId' => $userId
            ]
        );
    }

    /**
     * Method to store individul salary data
     *
     * @access public
     * @param Request $request
     */
    public function actionIndividualSalary(Request $request)
    {
        $this->validate($request, [
            'branch' => 'required',
            'employee_id' => 'required',
            'attendance_date' => 'required',
        ]);

        $applicationSetting = ApplicationSetting::first();

        $year = date('Y', strtotime($request->attendance_date));
        $month = date('m', strtotime($request->attendance_date));
        $companies = Company::orderBy('id', 'DESC')->where('id', Session::get('company_id'))->first();
        $employee = Employee::orderBy('id', 'DESC')->where('id', $request->employee_id)->first();
        $salary = Salary::where('id', $employee->salary_id)->first();
        $unpaidDays = Attendance::whereYear('current_date', '=', $year)
            ->whereMonth('current_date', '=', $month)
            ->where('absent_leave_type', '=', 0)
            ->where('employee_id', '=', $request->employee_id)
            ->get();
        $daysCount = $unpaidDays->count();
        $basicSalary = $salary?->basic_salary;
        if ($employee->job_type == 'Permanent') {
            $houseRent = $salary?->house_rent_amount;
            $medicalAllowance = $salary?->medical_allowance_amount;
            $conveyanceAllowance = $salary?->conveyance_allowance_amount;
            $foodAllowance = $salary->food_allowance_amount;
            $communicationAllowance = $salary->communication_allowance_amount;
            $other = $salary->other_amount;
            $total = $basicSalary + $houseRent + $medicalAllowance + $conveyanceAllowance + $foodAllowance + $communicationAllowance + $other;
            $unpaidAmount = 0;
            if ($daysCount > 0) {
                $unpaidAmount = ($basicSalary / 30) * $daysCount;
                $unpaidAmount = number_format((float)$unpaidAmount, 2, '.', '');
            }
            $netSalary = $total - $unpaidAmount;
        } else {
            $houseRent = $salary?->house_rent_amount;
            $medicalAllowance = $salary?->medical_allowance_amount;
            $conveyanceAllowance = $salary?->conveyance_allowance_amount;
            $foodAllowance = $salary->food_allowance_amount;
            $communicationAllowance = $salary->communication_allowance_amount;
            $other = $salary->other_amount;
            $total = $basicSalary + $houseRent + $medicalAllowance + $conveyanceAllowance + $foodAllowance + $communicationAllowance + $other;
            $unpaidAmount = 0;
            if ($daysCount > 0) {
                $unpaidAmount = ($total / 30) * $daysCount;
                $unpaidAmount = number_format((float)$unpaidAmount, 2, '.', '');
            }
            $netSalary = $total - $unpaidAmount;
        }

        $loanPayDate = $year . "-" . $month . "-01";

        $loanInfo = Loan::with('loansPay')
            ->where('employee_id', $request->employee_id)
            ->where('loan_status', '3')
            ->where('receive_loan', 'salary')
            ->get();
        $salaryLoan = 0;

        //        dd($loanInfo);

        foreach ($loanInfo as $loan) {
            $monthLoanPayCheck = LoansPay::where('employee_id', $request->employee_id)
                ->where('pay_date', $loanPayDate)
                ->where('loan_id', $loan->id)
                ->first();

            if (isset($monthLoanPayCheck)) {
                $salaryLoan = $salaryLoan + $monthLoanPayCheck->pay_amount;
            }
        }

        if ($salaryLoan == 0) {
            $salaryLoanPay = 0;
        } else {
            $salaryLoanPay = $salaryLoan;
        }

        $deducadeAdvanceSalary = 0;

        $monthStart = $year . "-" . $month . "-01";

        $monthEnd = $year . "-" . $month . "-31";

        $advanceSalary = AdvanceSalary::where('employee_id', $request->employee_id)->where('status', '3')
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get("amount")->first();

        if ($advanceSalary == NULL) {
            $advanceSalary = 0;
        } else {
            $advanceSalary = $advanceSalary->amount + 0;
        }

        $totalDecuction = $unpaidAmount + $advanceSalary + $salaryLoanPay;
        $totalDecuction = number_format((float)$totalDecuction, 2, '.', '');
        $netSalary = $netSalary + $unpaidAmount - $totalDecuction;

        $netSalary = number_format((float)$netSalary, 2, '.', '');

        $word = $this->convertNumberToWords($netSalary);
        $salaryWord = ucwords($word) . " Only";
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDF</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
}

</style>

<div class="text-center" style="padding-bottom: 30px; padding-top: 30px;">
    <h3>' . $applicationSetting->item_name . '</h3>
    <p>' . $applicationSetting->company_address . '</p>
    <h4>Salary Slip</h4>
</div>

<div>
    <h5><b>Employee Name : </b>' . $employee->name . '</h5>
    <h5><b>Designation : </b>' . $employee->designation->designation_name . '</h5>
    <h5><b>Month : </b>' . $request->attendance_date . '</h5>
</div>

<table id="customers" class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">Earnings</th>
        <th scope="col"></th>
        <th scope="col">Deductions</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td scope="row">Basic Salary</td>
        <td>' . $basicSalary . '</td>
        <td>Paid Leave</td>
        <td>' . $unpaidAmount . '</td>
    </tr>
    <tr>
        <td scope="row">House Rent</td>
        <td>' . $houseRent . '</td>
        <td>Advance Salary</td>
        <td>' . $advanceSalary . '</td>
    </tr>
    <tr>
        <td scope="row">Medical Allowance</td>
        <td>' . $medicalAllowance . '</td>
        <td>Loan Pay</td>
        <td>' . $salaryLoanPay . '</td>
    </tr>
    <tr>
        <td scope="row">Conveyance Allowance</td>
        <td>' . $conveyanceAllowance . '</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td scope="row">Food Allowance</td>
        <td>' . $foodAllowance . '</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td scope="row">Communication Allowance</td>
        <td>' . $communicationAllowance . '</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td scope="row">Other</td>
        <td>' . $other . '</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <th scope="row">Total Earning</th>
        <td>' . $total . '</td>
        <th>Total Deduction</th>
        <td>' . $totalDecuction . '</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <th>NET Salary</th>
        <td>' . $netSalary . '</td>
    </tr>
    </tbody>
</table>
' . $salaryWord . '

<br>
<br>
<br>
<br>
<br>
<br>
<table style="width: 100%">
  <tr>
    <td style="width: 40%"><hr></td>
    <td style="width: 20%"></td>
    <td style="width: 40%"><hr></td>
  </tr>
  <tr>
    <td style="width: 40%; text-align: center">Employee Signature</td>
    <td style="width: 20%"></td>
    <td style="width: 40%; text-align: center">Director</td>
  </tr>
</table>
</body>
</html>';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    /**
     * Method for convert number to words
     *
     * @param $number
     * @access public
     * @return bool|mixed|string|null
     */
    public function convertNumberToWords($number)
    {

        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convertNumberToWords(abs($number));
        }

        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convertNumberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    /**
     * Method to load salary view
     *
     * @access public
     * @return mixed
     */
    public function branchSalary()
    {

        $userId = Auth::user()->id;
        $selectCompanies = Company::orderBy('id', 'DESC')->where('id', Session::get('company_id'))->get();
        $branchs = Branch::orderBy('id', 'DESC')->where('company_id', Session::get('company_id'))->pluck('branch_name', 'id');
        return view(
            'report.branch_salary',
            [
                'selectCompanies' => $selectCompanies,
                'branchs' => $branchs,
                'userId' => $userId
            ]
        );
    }

    /**
     * Store a newly created resource in storage
     *
     * @access public
     * @param Request $request
     */
    public function actionbranchSalary(Request $request)
    {
        $this->validate($request, [
            'branch' => 'required',
            'attendance_date' => 'required',
        ]);

        $applicationSetting = ApplicationSetting::first();

        $year = date('Y', strtotime($request->attendance_date));
        $month = date('m', strtotime($request->attendance_date));
        $companies = Company::orderBy('id', 'DESC')->where('id', Session::get('company_id'))->first();
        $branch = Branch::orderBy('id', 'DESC')->where('id', $request->branch)->first();
        $employee = DB::table('attendances')
            ->whereYear('current_date', '=', $year)
            ->whereMonth('current_date', '=', $month)
            ->select('employee_id')
            ->leftJoin('employees', 'attendances.employee_id', '=', 'employees.id')
            ->groupBy('employee_id')->get();

        $salarySheetForTheMonthOf = trans('Salary Sheet For The Month Of');
        $pInfo = trans('Info');
        $pEarnings = trans('Earnings');
        $pDeductions = trans('Deductions');
        $pSerial = trans('Serial');
        $pStaff = trans('Staff Name');
        $pDesignation = trans('Designation');
        $pBasic = trans('Basic Salary');
        $pHouse = trans('House Rent');
        $pMedical = trans('Medical Allowance');
        $pConveyance = trans('Conveyance Allowance');
        $pFood = trans('Food Allowance');
        $pCommunication = trans('Communication Allowance');
        $pOther = trans('Other');
        $pGross = trans('Gross Salary');
        $pUnpaid = trans('Unpaid Leave');
        $pSloan = trans('Salary Loan');
        $pASalary = trans('Advance Salary');
        $pTotald = trans('Total Deduction');
        $pNet = trans('Net Amount');
        $pRemark = trans('Remark');
        $html = '';
        $html .= '<!DOCTYPE html>
                <html lang="en">
                    <head>
                        <title>PDF</title>
                        <meta charset="utf-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
                    </head>
                    <body>
                        <style>
                            #customers {font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;}
                            #customers td, #customers th {border: 1px solid #ddd;padding: 8px;}
                            #customers tr:nth-child(even){background-color: #f2f2f2;}
                            #customers tr:hover {background-color: #ddd;}
                            #customers th { padding-top: 12px; padding-bottom: 12px;}
                        </style>
                        <div class="text-center">
                           <h3>' . $applicationSetting->item_name . '</h3>
                            <p>' . $applicationSetting->company_address . '</p>
                            <h4>' . $branch->branch_name . ' ' . $salarySheetForTheMonthOf . ' ' . $request->attendance_date . '</h4>
                        </div>
                        <br><br><br>
                        <table id="customers" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="3">' . $pInfo . '</th>
                                    <th colspan="8">' . $pEarnings . '</th>
                                    <th colspan="4">' . $pDeductions . '</th>
                                </tr>
                                <tr>
                                    <th scope="col">' . $pSerial . '</th>
                                    <th scope="col">' . $pStaff . '</th>
                                    <th scope="col">' . $pDesignation . '</th>
                                    <th scope="col">' . $pBasic . '</th>
                                    <th scope="col">' . $pHouse . '</th>
                                    <th scope="col">' . $pMedical . '</th>
                                    <th scope="col">' . $pConveyance . '</th>
                                    <th scope="col">' . $pFood . '</th>
                                    <th scope="col">' . $pCommunication . '</th>
                                    <th scope="col">' . $pOther . '</th>
                                    <th scope="col">' . $pGross . '</th>
                                    <th scope="col">' . $pUnpaid . '</th>
                                    <th scope="col">' . $pASalary . '</th>
                                    <th scope="col">' . $pSloan . '</th>
                                    <th scope="col">' . $pTotald . '</th>
                                    <th scope="col">' . $pNet . '</th>
                                    <th scope="col">' . $pRemark . '</th>
                                </tr>
                            </thead>';
        $i = 1;
        foreach ($employee as $value) {
            $sLoan = 0;
            $totalSalayLoan = LoansPay::where('employee_id', '=', $value->employee_id)
                ->whereYear('pay_date', '=', $year)
                ->whereMonth('pay_date', '=', $month)
                ->get();
            foreach ($totalSalayLoan as $tSLoan) {
                $sLoan = $sLoan + $tSLoan->pay_amount;
            }
            $totalUnpaidLeave = DB::table('attendances')
                ->whereYear('current_date', '=', $year)
                ->whereMonth('current_date', '=', $month)
                ->where('absent_leave_type', '=', 0)
                ->where('employee_id', '=', $value->employee_id)
                ->select(DB::raw('count(absent_leave_type) as total'))
                ->leftJoin('employees', 'attendances.employee_id', '=', 'employees.id')
                ->groupBy('employee_id')->get();

            $employeeSalaries = DB::table('employees')
                ->where('employees.id', '=', $value->employee_id)
                ->select('employees.name', 'employees.designation_id', 'employees.job_type', 'salaries.*')
                ->leftJoin('salaries', 'employees.sallary', '=', 'salaries.id')
                ->first();

            $designationName = Designation::select('designation_name')->where('id', '=', $employeeSalaries->designation_id)->first();
            $basicSalary = $employeeSalaries->basic_salary;
            if ($employeeSalaries->job_type == 'Permanent') {
                $houseRent = $employeeSalaries->house_rent_amount;
                $medicalAllowance = $employeeSalaries->medical_allowance_amount;
                $conveyanceAllowance = $employeeSalaries->conveyance_allowance_amount;
                $foodAllowance = $employeeSalaries->food_allowance_amount;
                $communicationAllowance = $employeeSalaries->communication_allowance_amount;
                $other = $employeeSalaries->other_amount;
                $total = $basicSalary + $houseRent + $medicalAllowance + $conveyanceAllowance + $foodAllowance + $communicationAllowance + $other;
                $unpaidAmount = 0;
                if (isset($totalUnpaidLeave['0']->total)) {
                    if ($totalUnpaidLeave['0']->total > 0) {
                        $unpaidAmount = ($basicSalary / 30) * $totalUnpaidLeave['0']->total;
                        $unpaidAmount = number_format((float)$unpaidAmount, 2, '.', '');
                    }
                }
                $totalDeduction = $unpaidAmount + $sLoan;
                $netSalary = $total - $totalDeduction;
            } else {
                $houseRent = $employeeSalaries->house_rent_amount;
                $houseRent = (float)$houseRent;
                $medicalAllowance = $employeeSalaries->medical_allowance_amount;
                $medicalAllowance = (float)$medicalAllowance;
                $conveyanceAllowance = $employeeSalaries->conveyance_allowance_amount;
                $conveyanceAllowance = (float)$conveyanceAllowance;
                $foodAllowance = $employeeSalaries->food_allowance_amount;
                $foodAllowance = (float)$foodAllowance;
                $communicationAllowance = $employeeSalaries->communication_allowance_amount;
                $communicationAllowance = (float)$communicationAllowance;
                $other = $employeeSalaries->other_amount;
                $other = (float)$other;

                $total = $basicSalary + $houseRent + $medicalAllowance + $conveyanceAllowance + $foodAllowance + $communicationAllowance + $other;

                $unpaidAmount = 0;
                if (isset($totalUnpaidLeave['0']->total)) {
                    if ($totalUnpaidLeave['0']->total > 0) {
                        $unpaidAmount = ($total / 30) * $totalUnpaidLeave['0']->total;
                        $unpaidAmount = number_format((float)$unpaidAmount, 2, '.', '');
                    }
                }
                $totalDeduction = $unpaidAmount + $sLoan;
                $netSalary = $total - $totalDeduction;
            }

            $deducadeAdvanceSalary = 0;
            $monthStart = $year . "-" . $month . "-01";
            $monthEnd = $year . "-" . $month . "-31";
            $advanceSalary = AdvanceSalary::where('employee_id', $value->employee_id)->where('status', '3')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->get("amount")->first();
            if ($advanceSalary == NULL) {
                $advanceSalary = 0;
            } else {
                $advanceSalary = $advanceSalary->amount + 0;
            }
            $totalDeduction = $totalDeduction + $advanceSalary;
            $netSalary = $netSalary - $advanceSalary;

            $html .= '
    <tbody>
    <tr>
        <td scope="col">' . $i . '</td>
        <td scope="col">' . $employeeSalaries->name . '</td>
        <td scope="col">' . $designationName->designation_name . '</td>
        <td scope="col">' . $basicSalary . '</td>
        <td scope="col">' . $houseRent . '</td>
        <td scope="col">' . $medicalAllowance . '</td>
        <td scope="col">' . $conveyanceAllowance . '</td>
        <td scope="col">' . $foodAllowance . '</td>
        <td scope="col">' . $communicationAllowance . '</td>
        <td scope="col">' . $other . '</td>
        <td scope="col">' . $total . '</td>
        <td scope="col">' . $unpaidAmount . '</td>
        <td scope="col">' . $advanceSalary . '</td>
        <td scope="col">' . $sLoan . '</td>
        <td scope="col">' . $totalDeduction . '</td>

        <td scope="col">' . $netSalary . '</td>
        <td scope="col" style="width: 120px"></td>
    </tr>
    </tbody>';
            $i++;
        }

        $html .= '
</table>
</body>
</html>';
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}
