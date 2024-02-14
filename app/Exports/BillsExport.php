<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BillsExport implements FromView
{
    protected $bills;
    protected $companyDateFormat;

    public function __construct(Request $request, $company_id=null)
    {
        $query = Bill::query();

        if ($company_id) {
            $company = Company::findOrFail($company_id);
            $company->setSettings();
            $companyDateFormat = $company->date_format;
            $query->where('company_id', $company_id);
        } else {
            $companyDateFormat = "d M Y";
        }

        $this->bills = $query->get();
        $this->companyDateFormat = $companyDateFormat;
    }

    public function view(): View
    {
        return view('exports.bills', [
            'bills' => $this->bills,
            'companyDateFormat' => $this->companyDateFormat,
        ]);
    }
}
