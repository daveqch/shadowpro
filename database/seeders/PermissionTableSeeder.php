<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(['name' => 'profile-read', 'display_name' => 'Profile']);
        Permission::firstOrCreate(['name' => 'profile-update', 'display_name' => 'Profile']);
        Permission::firstOrCreate(['name' => 'role-read', 'display_name' => 'Role']);
        Permission::firstOrCreate(['name' => 'role-create', 'display_name' => 'Role']);
        Permission::firstOrCreate(['name' => 'role-update', 'display_name' => 'Role']);
        Permission::firstOrCreate(['name' => 'role-delete', 'display_name' => 'Role']);
        Permission::firstOrCreate(['name' => 'role-export', 'display_name' => 'Role']);
        Permission::firstOrCreate(['name' => 'user-read', 'display_name' => 'User']);
        Permission::firstOrCreate(['name' => 'user-create', 'display_name' => 'User']);
        Permission::firstOrCreate(['name' => 'user-update', 'display_name' => 'User']);
        Permission::firstOrCreate(['name' => 'user-delete', 'display_name' => 'User']);
        Permission::firstOrCreate(['name' => 'user-export', 'display_name' => 'User']);
        Permission::firstOrCreate(['name' => 'offline-payment-read', 'display_name' => 'Offline Payment']);
        Permission::firstOrCreate(['name' => 'offline-payment-create', 'display_name' => 'Offline Payment']);
        Permission::firstOrCreate(['name' => 'offline-payment-update', 'display_name' => 'Offline Payment']);
        Permission::firstOrCreate(['name' => 'offline-payment-delete', 'display_name' => 'Offline Payment']);
        Permission::firstOrCreate(['name' => 'company-read', 'display_name' => 'Company']);
        Permission::firstOrCreate(['name' => 'company-create', 'display_name' => 'Company']);
        Permission::firstOrCreate(['name' => 'company-update', 'display_name' => 'Company']);
        Permission::firstOrCreate(['name' => 'company-delete', 'display_name' => 'Company']);
        Permission::firstOrCreate(['name' => 'company-export', 'display_name' => 'Company']);

        Permission::firstOrCreate(['name' => 'salary-read', 'display_name' => 'Salary']);
        Permission::firstOrCreate(['name' => 'salary-create', 'display_name' => 'Salary']);
        Permission::firstOrCreate(['name' => 'salary-update', 'display_name' => 'Salary']);
        Permission::firstOrCreate(['name' => 'salary-delete', 'display_name' => 'Salary']);
        Permission::firstOrCreate(['name' => 'salary-export', 'display_name' => 'Salary']);

        Permission::firstOrCreate(['name' => 'location-read', 'display_name' => 'Location']);
        Permission::firstOrCreate(['name' => 'location-create', 'display_name' => 'Location']);
        Permission::firstOrCreate(['name' => 'location-update', 'display_name' => 'Location']);
        Permission::firstOrCreate(['name' => 'location-delete', 'display_name' => 'Location']);
        Permission::firstOrCreate(['name' => 'location-export', 'display_name' => 'Location']);

        Permission::firstOrCreate(['name' => 'branch-read', 'display_name' => 'Branch']);
        Permission::firstOrCreate(['name' => 'branch-create', 'display_name' => 'Branch']);
        Permission::firstOrCreate(['name' => 'branch-update', 'display_name' => 'Branch']);
        Permission::firstOrCreate(['name' => 'branch-delete', 'display_name' => 'Branch']);
        Permission::firstOrCreate(['name' => 'branch-export', 'display_name' => 'Branch']);

        Permission::firstOrCreate(['name' => 'employee-read', 'display_name' => 'Employee']);
        Permission::firstOrCreate(['name' => 'employee-create', 'display_name' => 'Employee']);
        Permission::firstOrCreate(['name' => 'employee-update', 'display_name' => 'Employee']);
        Permission::firstOrCreate(['name' => 'employee-delete', 'display_name' => 'Employee']);
        Permission::firstOrCreate(['name' => 'employee-export', 'display_name' => 'Employee']);

        Permission::firstOrCreate(['name' => 'leave-read', 'display_name' => 'Leave']);
        Permission::firstOrCreate(['name' => 'leave-create', 'display_name' => 'Leave']);
        Permission::firstOrCreate(['name' => 'leave-update', 'display_name' => 'Leave']);
        Permission::firstOrCreate(['name' => 'leave-delete', 'display_name' => 'Leave']);
        Permission::firstOrCreate(['name' => 'leave-export', 'display_name' => 'Leave']);

        Permission::firstOrCreate(['name' => 'attendance-create', 'display_name' => 'Attendance']);
        Permission::firstOrCreate(['name' => 'attendance-dayWiseAttendance', 'display_name' => 'Attendance']);
        Permission::firstOrCreate(['name' => 'attendance-employeeAttendance', 'display_name' => 'Attendance']);

        Permission::firstOrCreate(['name' => 'department-read', 'display_name' => 'Department']);
        Permission::firstOrCreate(['name' => 'department-create', 'display_name' => 'Department']);
        Permission::firstOrCreate(['name' => 'department-update', 'display_name' => 'Department']);
        Permission::firstOrCreate(['name' => 'department-delete', 'display_name' => 'Department']);
        Permission::firstOrCreate(['name' => 'department-export', 'display_name' => 'Department']);
        Permission::firstOrCreate(['name' => 'designation-read', 'display_name' => 'Designation']);
        Permission::firstOrCreate(['name' => 'designation-create', 'display_name' => 'Designation']);
        Permission::firstOrCreate(['name' => 'designation-update', 'display_name' => 'Designation']);
        Permission::firstOrCreate(['name' => 'designation-delete', 'display_name' => 'Designation']);
        Permission::firstOrCreate(['name' => 'designation-export', 'display_name' => 'Designation']);
        Permission::firstOrCreate(['name' => 'notice-read', 'display_name' => 'Notice']);
        Permission::firstOrCreate(['name' => 'notice-create', 'display_name' => 'Notice']);
        Permission::firstOrCreate(['name' => 'notice-update', 'display_name' => 'Notice']);
        Permission::firstOrCreate(['name' => 'notice-delete', 'display_name' => 'Notice']);
        Permission::firstOrCreate(['name' => 'notice-export', 'display_name' => 'Notice']);
        Permission::firstOrCreate(['name' => 'policy-read', 'display_name' => 'Policy']);
        Permission::firstOrCreate(['name' => 'policy-create', 'display_name' => 'Policy']);
        Permission::firstOrCreate(['name' => 'policy-update', 'display_name' => 'Policy']);
        Permission::firstOrCreate(['name' => 'policy-delete', 'display_name' => 'Policy']);
        Permission::firstOrCreate(['name' => 'policy-export', 'display_name' => 'Policy']);

        Permission::firstOrCreate(['name' => 'loan-read', 'display_name' => 'Loan']);
        Permission::firstOrCreate(['name' => 'loan-create', 'display_name' => 'Loan']);
        Permission::firstOrCreate(['name' => 'loan-update', 'display_name' => 'Loan']);
        Permission::firstOrCreate(['name' => 'loan-delete', 'display_name' => 'Loan']);
        Permission::firstOrCreate(['name' => 'loan-approve', 'display_name' => 'Loan']);
        Permission::firstOrCreate(['name' => 'loan-receive', 'display_name' => 'Loan']);

        Permission::firstOrCreate(['name' => 'advanceSalary-read', 'display_name' => 'Advance Salary']);
        Permission::firstOrCreate(['name' => 'advanceSalary-create', 'display_name' => 'Advance Salary']);
        Permission::firstOrCreate(['name' => 'advanceSalary-update', 'display_name' => 'Advance Salary']);
        Permission::firstOrCreate(['name' => 'advanceSalary-delete', 'display_name' => 'Advance Salary']);
        Permission::firstOrCreate(['name' => 'advanceSalary-approve', 'display_name' => 'Advance Salary']);
        Permission::firstOrCreate(['name' => 'advanceSalary-receive', 'display_name' => 'Advance Salary']);


        Permission::firstOrCreate(['name' => 'category-read', 'display_name' => 'Category']);
        Permission::firstOrCreate(['name' => 'category-create', 'display_name' => 'Category']);
        Permission::firstOrCreate(['name' => 'category-update', 'display_name' => 'Category']);
        Permission::firstOrCreate(['name' => 'category-delete', 'display_name' => 'Category']);
        Permission::firstOrCreate(['name' => 'category-export', 'display_name' => 'Category']);
        Permission::firstOrCreate(['name' => 'category-import', 'display_name' => 'Category']);
        Permission::firstOrCreate(['name' => 'currencies-read', 'display_name' => 'Currencies']);
        Permission::firstOrCreate(['name' => 'currencies-create', 'display_name' => 'Currencies']);
        Permission::firstOrCreate(['name' => 'currencies-update', 'display_name' => 'Currencies']);
        Permission::firstOrCreate(['name' => 'currencies-delete', 'display_name' => 'Currencies']);
        Permission::firstOrCreate(['name' => 'currencies-export', 'display_name' => 'Currencies']);
        Permission::firstOrCreate(['name' => 'currencies-import', 'display_name' => 'Currencies']);
        Permission::firstOrCreate(['name' => 'tax-rate-read', 'display_name' => 'Tax Rate']);
        Permission::firstOrCreate(['name' => 'tax-rate-create', 'display_name' => 'Tax Rate']);
        Permission::firstOrCreate(['name' => 'tax-rate-update', 'display_name' => 'Tax Rate']);
        Permission::firstOrCreate(['name' => 'tax-rate-delete', 'display_name' => 'Tax Rate']);
        Permission::firstOrCreate(['name' => 'tax-rate-export', 'display_name' => 'Tax Rate']);
        Permission::firstOrCreate(['name' => 'tax-rate-import', 'display_name' => 'Tax Rate']);
        Permission::firstOrCreate(['name' => 'item-read', 'display_name' => 'Item']);
        Permission::firstOrCreate(['name' => 'item-create', 'display_name' => 'Item']);
        Permission::firstOrCreate(['name' => 'item-update', 'display_name' => 'Item']);
        Permission::firstOrCreate(['name' => 'item-delete', 'display_name' => 'Item']);
        Permission::firstOrCreate(['name' => 'item-export', 'display_name' => 'Item']);
        Permission::firstOrCreate(['name' => 'item-import', 'display_name' => 'Item']);
        Permission::firstOrCreate(['name' => 'customer-read', 'display_name' => 'Customer']);
        Permission::firstOrCreate(['name' => 'customer-create', 'display_name' => 'Customer']);
        Permission::firstOrCreate(['name' => 'customer-update', 'display_name' => 'Customer']);
        Permission::firstOrCreate(['name' => 'customer-delete', 'display_name' => 'Customer']);
        Permission::firstOrCreate(['name' => 'customer-export', 'display_name' => 'Customer']);
        Permission::firstOrCreate(['name' => 'customer-import', 'display_name' => 'Customer']);
        Permission::firstOrCreate(['name' => 'invoice-read', 'display_name' => 'Invoice']);
        Permission::firstOrCreate(['name' => 'invoice-create', 'display_name' => 'Invoice']);
        Permission::firstOrCreate(['name' => 'invoice-update', 'display_name' => 'Invoice']);
        Permission::firstOrCreate(['name' => 'invoice-delete', 'display_name' => 'Invoice']);
        Permission::firstOrCreate(['name' => 'invoice-export', 'display_name' => 'Invoice']);
        Permission::firstOrCreate(['name' => 'revenue-read', 'display_name' => 'Revenue']);
        Permission::firstOrCreate(['name' => 'revenue-create', 'display_name' => 'Revenue']);
        Permission::firstOrCreate(['name' => 'revenue-update', 'display_name' => 'Revenue']);
        Permission::firstOrCreate(['name' => 'revenue-delete', 'display_name' => 'Revenue']);
        Permission::firstOrCreate(['name' => 'revenue-export', 'display_name' => 'Revenue']);
        Permission::firstOrCreate(['name' => 'vendor-read', 'display_name' => 'Vendor']);
        Permission::firstOrCreate(['name' => 'vendor-create', 'display_name' => 'Vendor']);
        Permission::firstOrCreate(['name' => 'vendor-update', 'display_name' => 'Vendor']);
        Permission::firstOrCreate(['name' => 'vendor-delete', 'display_name' => 'Vendor']);
        Permission::firstOrCreate(['name' => 'vendor-export', 'display_name' => 'Vendor']);
        Permission::firstOrCreate(['name' => 'vendor-import', 'display_name' => 'Vendor']);
        Permission::firstOrCreate(['name' => 'bill-read', 'display_name' => 'Bill']);
        Permission::firstOrCreate(['name' => 'bill-create', 'display_name' => 'Bill']);
        Permission::firstOrCreate(['name' => 'bill-update', 'display_name' => 'Bill']);
        Permission::firstOrCreate(['name' => 'bill-delete', 'display_name' => 'Bill']);
        Permission::firstOrCreate(['name' => 'bill-export', 'display_name' => 'Bill']);
        Permission::firstOrCreate(['name' => 'payment-read', 'display_name' => 'Payment']);
        Permission::firstOrCreate(['name' => 'payment-create', 'display_name' => 'Payment']);
        Permission::firstOrCreate(['name' => 'payment-update', 'display_name' => 'Payment']);
        Permission::firstOrCreate(['name' => 'payment-delete', 'display_name' => 'Payment']);
        Permission::firstOrCreate(['name' => 'payment-export', 'display_name' => 'Payment']);
        Permission::firstOrCreate(['name' => 'account-read', 'display_name' => 'Account']);
        Permission::firstOrCreate(['name' => 'account-create', 'display_name' => 'Account']);
        Permission::firstOrCreate(['name' => 'account-update', 'display_name' => 'Account']);
        Permission::firstOrCreate(['name' => 'account-delete', 'display_name' => 'Account']);
        Permission::firstOrCreate(['name' => 'account-export', 'display_name' => 'Account']);
        Permission::firstOrCreate(['name' => 'transfer-read', 'display_name' => 'Transfer']);
        Permission::firstOrCreate(['name' => 'transfer-create', 'display_name' => 'Transfer']);
        Permission::firstOrCreate(['name' => 'transfer-update', 'display_name' => 'Transfer']);
        Permission::firstOrCreate(['name' => 'transfer-delete', 'display_name' => 'Transfer']);
        Permission::firstOrCreate(['name' => 'transfer-export', 'display_name' => 'Transfer']);
        Permission::firstOrCreate(['name' => 'transaction-read', 'display_name' => 'Transaction']);
        Permission::firstOrCreate(['name' => 'income-report-read', 'display_name' => 'Reports']);
        Permission::firstOrCreate(['name' => 'expense-report-read', 'display_name' => 'Reports']);
        Permission::firstOrCreate(['name' => 'tax-report-read', 'display_name' => 'Reports']);
        Permission::firstOrCreate(['name' => 'profit-loss-report-read', 'display_name' => 'Reports']);
        Permission::firstOrCreate(['name' => 'income-expense-report-read', 'display_name' => 'Reports']);
        Permission::firstOrCreate(['name' => 'individual-salary-report-read', 'display_name' => 'Reports']);
        Permission::firstOrCreate(['name' => 'branch-salary-report-read', 'display_name' => 'Reports']);
    }
}
