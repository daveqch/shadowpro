@php
$c = Request::segment(1);
$m = Request::segment(2);
$RoleName = Auth::user()->getRoleNames();
@endphp

<aside class="main-sidebar elevation-4 sidebar-light-info">
    <a href="{{ route('dashboard')  }}" class="brand-link navbar-info">
        <img src="{{ asset('img/logo-text.png') }}" alt="{{ $ApplicationSetting->item_name }}" class="brand-image" style="opacity: .8; width :32px; height : 32px">
        <span class="brand-text font-weight-light">{{ $ApplicationSetting->item_name }}</span>
    </a>
    <div class="sidebar">
        <?php
            if(Auth::user()->photo == NULL)
            {
                $photo = "img/profile/male.png";
            } else {
                $photo = Auth::user()->photo;
            }
        ?>
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset($photo) }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info my-auto">
                {{ Auth::user()->name }}
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @if($c == 'dashboard') active @endif">
                        <span class="nav-icon mdi mdi-view-dashboard-outline"></span>
                        <p>@lang('Dashboard')</p>
                    </a>
                </li>
                @canany(['item-read', 'item-create', 'item-update', 'item-delete', 'item-export'])
                    <li class="nav-item">
                        <a href="{{ route('item.index') }}" class="nav-link @if($c == 'item') active @endif ">
                            <span class="mdi mdi-layers-outline nav-icon"></span>
                            <p>@lang('Items')</p>
                        </a>
                    </li>
                @endcanany

                @canany(['invoice-read', 'invoice-create', 'invoice-update', 'invoice-delete', 'invoice-export', 'revenue-read', 'revenue-create', 'revenue-update', 'revenue-delete', 'revenue-export', 'customer-read', 'customer-create', 'customer-update', 'customer-delete', 'customer-export'])
                    <li class="nav-item has-treeview @if($c == 'customer' || $c == 'invoice' || $c == 'revenue') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'customer' || $c == 'invoice' || $c == 'revenue') active @endif">
                            <span class="nav-icon mdi mdi-plus-box-multiple-outline"></span>
                            <span class=""></span>

                            <p>
                                @lang('Incomes')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['invoice-read', 'invoice-create', 'invoice-update', 'invoice-delete', 'invoice-export'])
                                <li class="nav-item">
                                    <a href="{{ route('invoice.index') }}" class="nav-link @if($c == 'invoice') active @endif ">
                                        <span class="nav-icon mdi mdi-receipt-text-outline"></span>
                                        <p>@lang('Invoice')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['revenue-read', 'revenue-create', 'revenue-update', 'revenue-delete', 'revenue-export'])
                                <li class="nav-item">
                                    <a href="{{ route('revenue.index') }}" class="nav-link @if($c == 'revenue') active @endif ">
                                        <span class="nav-icon mdi mdi-cash-plus"></span>
                                        <p>@lang('Revenue')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['customer-read', 'customer-create', 'customer-update', 'customer-delete', 'customer-export'])
                                <li class="nav-item">
                                    <a href="{{ route('customer.index') }}" class="nav-link @if($c == 'customer') active @endif ">
                                        <span class="nav-icon mdi mdi-account-plus-outline"></span>
                                        <p>@lang('Customer')</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
                @canany(['bill-read', 'bill-create', 'bill-update', 'bill-delete', 'bill-export', 'payment-read', 'payment-create', 'payment-update', 'payment-delete', 'payment-export', 'vendo-read', 'vendo-create', 'vendo-update', 'vendo-delete', 'vendo-export'])
                    <li class="nav-item has-treeview @if($c == 'vendor' || $c == 'payment' || $c == 'bill') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'vendor' || $c == 'payment' || $c == 'bill') active @endif">
                            <span class="nav-icon mdi mdi-minus-box-multiple-outline"></span>
                            <p>
                                @lang('Expenses')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['bill-read', 'bill-create', 'bill-update', 'bill-delete', 'bill-export'])
                                <li class="nav-item">
                                    <a href="{{ route('bill.index') }}" class="nav-link @if($c == 'bill') active @endif ">
                                        <span class="nav-icon mdi mdi-receipt-text-outline"></span>
                                        <p>@lang('Bill')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['payment-read', 'payment-create', 'payment-update', 'payment-delete', 'payment-export'])
                                <li class="nav-item">
                                    <a href="{{ route('payment.index') }}" class="nav-link @if($c == 'payment') active @endif ">
                                        <span class="nav-icon mdi mdi-cash-minus"></span>
                                        <p>@lang('Payment')</p>
                                    </a>
                                </li>
                            @endcanany
                            <li class="nav-item">
                                <a href="{{ route('vendor.index') }}" class="nav-link @if($c == 'vendor') active @endif ">
                                    <span class="nav-icon mdi mdi-account-minus-outline"></span>
                                    <p>@lang('Vendor')</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany
                @canany(['account-read', 'account-create', 'account-update', 'account-delete', 'account-export', 'transfer-read', 'transfer-create', 'transfer-update', 'transfer-delete', 'transfer-export', 'transaction-read', 'transaction-export'])
                    <li class="nav-item has-treeview @if($c == 'account' || $c == 'transfer' || $c == 'transaction') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'account' || $c == 'transfer' || $c == 'transaction' ) active @endif">
                            <span class="nav-icon mdi mdi-bank-outline"></span>
                            <p>
                                @lang('Banking')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['account-read', 'account-create', 'account-update', 'account-delete', 'account-export'])
                                <li class="nav-item">
                                    <a href="{{ route('account.index') }}" class="nav-link @if($c == 'account') active @endif ">
                                        <span class="nav-icon mdi mdi-account-outline"></span>
                                        <p>@lang('Accounts')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['transfer-read', 'transfer-create', 'transfer-update', 'transfer-delete', 'transfer-export'])
                                <li class="nav-item">
                                    <a href="{{ route('transfer.index') }}" class="nav-link @if($c == 'transfer') active @endif ">
                                        <span class="nav-icon mdi mdi-swap-horizontal"></span>
                                        <p>@lang('Transfers')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['transaction-read'])
                                <li class="nav-item">
                                    <a href="{{ route('transaction.index') }}" class="nav-link @if($c == 'transaction') active @endif ">
                                        <span class="nav-icon mdi mdi-handshake-outline"></span>
                                        <p>@lang('Transactions')</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
                @canany(['employee-read', 'employee-create', 'employee-update', 'employee-delete', 'employee-export','attendance-create','attendance-dayWiseAttendance','attendance-employeeAttendance'])
                    <li class="nav-item has-treeview @if($c == 'employee' || $c == 'attendance' || $c == 'loan' || $c == 'loan-receive' || $c == 'advance-salary' || $m == 'individualSalary' || $m == 'branchSalary' || $c == 'leave') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'employee' || $c == 'attendance' || $c == 'loan' || $c == 'loan-receive' || $c == 'advance-salary' || $m == 'individualSalary' || $m == 'branchSalary' || $c == 'leave') active @endif">
                            <span class="nav-icon mdi mdi-cube-outline"></span>
                            <p>
                                @lang('HRM')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['employee-read', 'employee-create', 'employee-update', 'employee-delete'])
                            <li class="nav-item">
                                <a href="{{ route('employee.index') }}" class="nav-link @if($c == 'employee') active @endif ">
                                    <span class="nav-icon mdi mdi-account-outline"></span>
                                    <p>@lang('Employee')</p>
                                </a>
                            </li>
                            @endcanany

                            @canany(['attendance-create', 'attendance-dayWiseAttendance', 'attendance-employeeAttendance'])
                            <li class="nav-item @if($c == 'attendance') menu-open @endif">
                                <a href="javascript:void(0)" class="nav-link @if($c == 'attendance') active @endif">
                                        <span class="nav-icon mdi mdi-clock-outline"></span>
                                    <p>
                                        @lang('Attendance')
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @canany(['attendance-create'])
                                    <li class="nav-item">
                                        <a href="{{ route('attendance.index') }}" class="nav-link @if($c == 'attendance' && $m == null) active @endif ">
                                            <span class="nav-icon mdi mdi-clock-check-outline"></span>
                                            <p>@lang('Attendance Create')</p>
                                        </a>
                                    </li>
                                    @endcan
                                    @canany(['attendance-dayWiseAttendance'])
                                    <li class="nav-item">
                                        <a href="{{ route('attendance.dayWiseAttendance') }}" class="nav-link @if($m == 'dayWiseAttendance') active @endif ">
                                            <span class="nav-icon mdi mdi-calendar-today-outline"></span>
                                            <p>@lang('Day Wise Attendance')</p>
                                        </a>
                                    </li>
                                    @endcan
                                    @canany(['attendance-employeeAttendance'])
                                    <li class="nav-item">
                                        <a href="{{ route('attendance.employeeWiseAttendance') }}" class="nav-link @if($m == 'employeeWiseAttendance') active @endif ">
                                            <span class="nav-icon mdi mdi-account-check-outline"></span>
                                            <p>@lang('Employee Attendance')</p>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                            @endcanany

                            @canany(['loan-read', 'loan-create', 'loan-update', 'loan-delete','loan-show','loan-approve'])
                            <li class="nav-item @if($c == 'loan' || $c == 'loan-receive') menu-open @endif">
                                <a href="javascript:void(0)" class="nav-link @if($c == 'loan' || $c == 'loan-receive') active @endif">
                                    <i class="nav-icon mdi mdi-handshake-outline"></i>
                                    <p>
                                        @lang('Loan')
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @canany(['loan-read', 'loan-create', 'loan-update', 'loan-delete','loan-show','loan-approve','loan-receive'])
                                        <li class="nav-item">
                                            <a href="{{ route('loan.index') }}" class="nav-link @if($c == 'loan') active @endif ">
                                                <span class="nav-icon mdi mdi-tray-arrow-down"></span>
                                                <p>@lang('Loan')</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @canany(['loan-receive'])
                                        <li class="nav-item">
                                            <a href="{{ route('loan-receive') }}" class="nav-link @if($c == 'loan-receive') active @endif ">
                                                <span class="nav-icon mdi mdi-call-received"></span>
                                                <p>@lang('Loan Receive')</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                            @endcanany

                            @canany(['advanceSalary-read', 'advanceSalary-create', 'advanceSalary-update', 'advanceSalary-delete','advanceSalary-show','advanceSalary-approve']) 
                                <li class="nav-item">
                                    <a href="{{ route('advance-salary.index') }}" class="nav-link @if($c == 'advance-salary') active @endif ">
                                        <i class="nav-icon mdi mdi-currency-usd"></i>
                                        <p>@lang('Advance Salary')</p>
                                    </a>
                                </li>
                            @endcanany

                            <li class="nav-item @if($c == 'salary') menu-open @endif">
                                <a href="javascript:void(0)" class="nav-link @if($c == 'salary') active @endif">
                                    <i class="nav-icon mdi mdi-chart-line"></i>
                                    <p>
                                        @lang('Reports')
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @canany(['individual-salary-report-read'])
                                        <li class="nav-item">
                                            <a href="{{ route('salary.individualSalary') }}" class="nav-link @if($m == 'individualSalary') active @endif ">
                                                <span class="nav-icon mdi mdi-badge-account-outline"></span>
                                                <p>@lang('Individual Salary')</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @canany(['branch-salary-report-read'])
                                        <li class="nav-item">
                                            <a href="{{ route('salary.branchSalary') }}" class="nav-link @if($m == 'branchSalary') active @endif ">
                                                <span class="nav-icon mdi mdi-source-branch"></span>
                                                <p>@lang('Branch Salary')</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>

                            @canany(['leave-read', 'leave-create', 'leave-update', 'leave-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('leave.index') }}" class="nav-link @if($c == 'leave') active @endif ">
                                        <span class="nav-icon mdi mdi-cog-outline"></span>
                                        <p>@lang('Leave Settings')</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
                @canany(['income-report-read', 'expense-report-read', 'tax-report-read', 'profit-loss-report-read', 'income-expense-report-read'])
                    <li class="nav-item has-treeview @if($c == 'report') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'report') active @endif">
                            <span class="nav-icon mdi mdi-chart-line"></span>
                            <p>
                                @lang('Reports')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('income-report-read')
                                <li class="nav-item">
                                    <a href="{{ route('report.income') }}" class="nav-link @if($c == 'report' && $m == 'income') active @endif ">
                                        <span class="nav-icon mdi mdi-plus-box-outline"></span>
                                        <p>@lang('Income')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('expense-report-read')
                                <li class="nav-item">
                                    <a href="{{ route('report.expense') }}" class="nav-link @if($c == 'report' && $m == 'expense') active @endif ">
                                        <span class="nav-icon mdi mdi-minus-box-outline"></span>
                                        <p>@lang('Expense')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('tax-report-read')
                                <li class="nav-item">
                                    <a href="{{ route('report.tax') }}" class="nav-link @if($c == 'report' && $m == 'tax') active @endif ">
                                        <span class="nav-icon mdi mdi-elevator"></span>
                                        <p>@lang('Tax')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('profit-loss-report-read')
                                <li class="nav-item">
                                    <a href="{{ route('report.profitAndloss') }}" class="nav-link @if($c == 'report' && $m == 'profitAndloss') active @endif ">
                                        <span class="nav-icon mdi mdi-sine-wave"></span>
                                        <p>@lang('Profit &amp; Loss')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('income-expense-report-read')
                                <li class="nav-item">
                                    <a href="{{ route('report.incomeVsexpense') }}" class="nav-link @if($c == 'report' && $m == 'incomeVsexpense') active @endif ">
                                        <span class="nav-icon mdi mdi-plus-minus-variant"></span>
                                        <p>@lang('Income VS Expense')</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['category-read', 'category-create', 'category-update', 'category-delete', 'category-export', 'category-import', 'currencies-read', 'currencies-create', 'currencies-update', 'currencies-delete', 'currencies-export', 'currencies-import','tax-rate-read', 'tax-rate-create', 'tax-rate-update', 'tax-rate-delete', 'tax-rate-export', 'tax-rate-import'])
                    <li class="nav-item has-treeview @if($c == 'category' || $c == 'currency' || $c == 'tax') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'category' || $c == 'currency' || $c == 'tax') active @endif">
                            <span class="nav-icon mdi mdi-format-quote-close-outline"></span>
                            <p>
                                @lang('Types')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['category-read', 'category-create', 'category-update', 'category-delete', 'category-export', 'category-import'])
                                <li class="nav-item">
                                    <a href="{{ route('category.index') }}" class="nav-link @if($c == 'category') active @endif ">
                                        <span class="nav-icon mdi mdi-source-branch"></span>
                                        <p>@lang('Category')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['currencies-read', 'currencies-create', 'currencies-update', 'currencies-delete', 'currencies-export', 'currencies-import'])
                                <li class="nav-item">
                                    <a href="{{ route('currency.index') }}" class="nav-link @if($c == 'currency') active @endif ">
                                        <span class="nav-icon mdi mdi-currency-usd"></span>
                                        <p>@lang('Currencies')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['tax-rate-read', 'tax-rate-create', 'tax-rate-update', 'tax-rate-delete', 'tax-rate-export', 'tax-rate-import'])
                                <li class="nav-item">
                                    <a href="{{ route('tax.index') }}" class="nav-link @if($c == 'tax') active @endif ">
                                        <span class="nav-icon mdi mdi-percent-outline"></span>
                                        <p>@lang('Tax rates')</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

                @canany(['salary-read', 'salary-create', 'salary-update', 'salary-delete', 'location-read', 'location-create', 'location-update', 'location-delete', 'branch-read', 'branch-create', 'branch-update', 'branch-delete','department-read', 'department-create', 'department-update', 'department-delete', 'designation-read', 'designation-create', 'designation-update', 'designation-delete', 'notice-read', 'notice-create', 'notice-update', 'notice-delete', 'policy-read', 'policy-create', 'policy-update', 'policy-delete'])
                <li class="nav-item has-treeview @if(($c == 'location' || $c == 'branch' || $c == 'salary' || $c == 'department' || $c == 'designation' || $c == 'notice'|| $c == 'policy')
                && ($m != 'individualSalary' && $m != 'branchSalary')) menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if($c == 'location' || $c == 'salary' || $c == 'branch' || $c == 'department' || $c == 'designation' || $c == 'notice' || $c == 'policy') active @endif">
                        <span class="nav-icon mdi mdi-sitemap-outline"></span>
                        <p>
                            @lang('Organization')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @canany(['salary-read', 'salary-create', 'salary-update', 'salary-delete'])
                            <li class="nav-item">
                                <a href="{{ route('salary.index') }}" class="nav-link @if($c == 'salary') active @endif ">
                                    <span class="nav-icon mdi mdi-currency-usd"></span>
                                    <p>@lang('Salary')</p>
                                </a>
                            </li>
                        @endcan
                        @canany(['location-read', 'location-create', 'location-update', 'location-delete'])
                            <li class="nav-item">
                                <a href="{{ route('location.index') }}" class="nav-link @if($c == 'location') active @endif ">
                                    <span class="nav-icon mdi mdi-map-marker-outline"></span>
                                    <p>@lang('Location')</p>
                                </a>
                            </li>
                        @endcan
                        @canany(['branch-read', 'branch-create', 'branch-update', 'branch-delete']) 
                        <li class="nav-item">
                            <a href="{{ route('branch.index') }}" class="nav-link @if($c == 'branch') active @endif ">
                                <span class="nav-icon mdi mdi-source-branch"></span>
                                <p>@lang('Branch')</p>
                            </a>
                        </li>
                        @endcan
                        @canany(['department-read', 'department-create', 'department-update', 'department-delete']) 
                        <li class="nav-item">
                            <a href="{{ route('department.index') }}" class="nav-link @if($c == 'department') active @endif ">
                                <span class="nav-icon mdi mdi-graph-outline"></span>
                                <p>@lang('Department')</p>
                            </a>
                        </li>
                        @endcan 
                        @canany(['designation-read', 'designation-create', 'designation-update', 'designation-delete']) 
                        <li class="nav-item">
                            <a href="{{ route('designation.index') }}" class="nav-link @if($c == 'designation') active @endif ">
                                <span class="nav-icon mdi mdi-selection-multiple"></span>
                                <p>@lang('Designation')</p>
                            </a>
                        </li>
                        @endcan 
                        @canany(['notice-read', 'notice-create', 'notice-update', 'notice-delete']) 
                        <li class="nav-item">
                            <a href="{{ route('notice.index') }}" class="nav-link @if($c == 'notice') active @endif ">
                                <span class="nav-icon mdi mdi-bulletin-board"></span>
                                <p>@lang('Notice')</p>
                            </a>
                        </li>
                        @endcan

                        @canany(['policy-read', 'policy-create', 'policy-update', 'policy-delete']) 
                        <li class="nav-item">
                            <a href="{{ route('policy.index') }}" class="nav-link @if($c == 'policy') active @endif ">
                                <span class="nav-icon mdi mdi-notebook-outline"></span>
                                <p>@lang('Policy')</p>
                            </a>
                        </li>
                        @endcan 
                    </ul>
                </li>
                @endcanany

                @canany(['company-read', 'company-update'])
                    <li class="nav-item">
                        <a href="{{ route('general') }}" class="nav-link @if($c == 'general') active @endif ">
                            <span class="nav-icon mdi mdi-format-align-left"></span>
                            <p>@lang('My Company')</p>
                        </a>
                    </li>
                @endcanany

                @canany(['role-read', 'role-create', 'role-update', 'role-delete', 'role-export', 'user-read', 'user-create', 'user-update', 'user-delete', 'user-export', 'offline-payment-read', 'offline-payment-create', 'offline-payment-update', 'offline-payment-delete'])
                    <li class="nav-item has-treeview @if($c == 'roles' || $c == 'users' || $c == 'appsetting' || $c == 'smtp' || $c == 'offline-payment' ) menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'roles' || $c == 'users' || $c == 'appsetting' || $c == 'smtp' || $c == 'offline-payment' ) active @endif">
                            <span class="nav-icon mdi mdi-cog-outline"></span>
                            <p>
                                @lang('Settings')
                                <i class="right fas fa-angle-left"></i>
                                
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['role-read', 'role-create', 'role-update', 'role-delete', 'role-export'])
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" class="nav-link @if($c == 'roles') active @endif ">
                                        <span class="nav-icon mdi mdi-cube-outline"></span>
                                        <p>@lang('Role Management')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['user-read', 'user-create', 'user-update', 'user-delete', 'user-export'])
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link @if($c == 'users') active @endif ">
                                        <span class="nav-icon mdi mdi-account-group-outline"></span>
                                        <p>@lang('User Management')</p>
                                    </a>
                                </li>
                            @endcanany
                            @if ($roleName['0'] = "Super Admin")
                                <li class="nav-item">
                                    <a href="{{ route('appsetting') }}" class="nav-link @if($c == 'appsetting' && $m == null) active @endif ">
                                        <span class="nav-icon mdi mdi-web"></span>
                                        <p>@lang('Application Settings')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('smtp.index') }}" class="nav-link @if($c == 'smtp') active @endif ">
                                        <span class="nav-icon mdi mdi-email-outline"></span>
                                        <p>@lang('Smtp Settings')</p>
                                    </a>
                                </li>
                            @endif
                            @canany(['offline-payment-read', 'offline-payment-create', 'offline-payment-update', 'offline-payment-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('offline-payment.index') }}" class="nav-link @if($c == 'offline-payment') active @endif ">
                                        <span class="nav-icon mdi mdi-currency-usd"></span>
                                        <p>@lang('Offline Payments')</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
            </ul>
        </nav>
    </div>
</aside>
