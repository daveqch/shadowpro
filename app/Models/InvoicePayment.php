<?php

namespace App\Models;

use Session;
use App\Traits\DateTime;
use Akaunting\Money\Money;
use Akaunting\Money\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoicePayment extends Model
{
    use HasFactory, DateTime, SoftDeletes;

    protected $fillable = [
        'company_id',
        'invoice_id',
        'account_id',
        'paid_at',
        'amount',
        'currency_code',
        'currency_rate',
        'description',
        'payment_method',
        'reference'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('paid_at', 'desc');
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (float) $value;
    }

    public function setCurrencyRateAttribute($value)
    {
        $this->attributes['currency_rate'] = (float) $value;
    }

    public function scopePaid($query)
    {
        return $query->sum('amount');
    }

    public function getDivideConvertedAmount($format = false)
    {
        return $this->divide($this->amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function divide($amount, $code, $rate, $format = false)
    {
        if ($format) {
            $money = Money::$code($amount, true)->divide((float) $rate)->format();
        } else {
            $money = Money::$code($amount)->divide((float) $rate)->getAmount();
        }
        return $money;
    }

    public function convert($amount, $code, $rate, $format = false)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $default = new Currency($company->default_currency);

        if ($format) {
            $money = Money::$code($amount, true)->convert($default, (float) $rate)->format();
        } else {
            $money = Money::$code($amount)->convert($default, (float) $rate)->getAmount();
        }

        return $money;
    }

    public function getDynamicConvertedAmount($format = false)
    {
        return $this->dynamicConvert($this->default_currency_code, $this->amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function dynamicConvert($default, $amount, $code, $rate, $format = false)
    {
        $code = new Currency($code);
        if ($format) {
            $money = Money::$default($amount, true)->convert($code, (float) $rate)->format();
        } else {
            $money = Money::$default($amount)->convert($code, (float) $rate)->getAmount();
        }
        return $money;
    }

    public function getConvertedAmount($format = false)
    {
        return $this->convert($this->amount, $this->currency_code, $this->currency_rate, $format);
    }
}
