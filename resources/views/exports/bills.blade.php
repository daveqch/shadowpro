<table>
    <thead>
        <tr>
            <th>Bill Number</th>
            <th>Vendor</th>
            <th>Amount</th>
            <th>Bill Data</th>
            <th>Due Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bills as $bill)
        <tr>
            <td>{{ $bill->bill_number }}</td>
            <td>{{ $bill->vendor_name }}</td>
            <td>@money($bill->amount, $bill->currency_code, true)</td>
            <td>{{ date($companyDateFormat, strtotime($bill->billed_at)) }}</td>
            <td>{{ date($companyDateFormat, strtotime($bill->due_at)) }}</td>
            <td>{{ $bill->bill_status_code }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
