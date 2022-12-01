<?php

namespace App\Models;

use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

/**
 * Class Customer
 *
 * @property int $id
 * @property string $f_name
 * @property string|null $l_name
 * @property string $short_name
 * @property string|null $address
 * @property string|null $company
 * @property string $country
 * @property string|null $image
 * @property int|null $tax_id
 * @property string $currency
 * @property int|null $payment_terms
 * @property float|null $credit_limit
 * @property int|null $credit_status
 * @property string|null $sales_type
 * @property float|null $discount
 * @property string|null $notes
 * @property string $language
 * @property string|null $client_ref
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property string $inactive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Customer extends Model
{
    protected $table = 'customers';

    protected $casts = [
        'tax_id' => 'int',
        'payment_terms' => 'int',
        'credit_limit' => 'float',
        'credit_status' => 'int',
        'discount' => 'float'
    ];

    protected $dates = [
        'supervised_at'
    ];

    protected $fillable = [
        'f_name',
        'l_name',
        'short_name',
        'address',
        'company',
        'country',
        'image',
        'tax_id',
        'currency',
        'payment_terms',
        'credit_limit',
        'credit_status',
        'sales_type',
        'discount',
        'notes',
        'language',
        'client_ref',
        'created_by',
        'updated_by',
        'supervised_by',
        'supervised_at',
        'inactive'
    ];

    public function customer_branch(): HasOne
    {
        return $this->hasOne(CustomerBranch::class, 'customer_id');
    }

    public function customer_branches(): HasMany
    {
        return $this->hasMany(CustomerBranch::class, 'customer_id');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function getUnpaidInvoices(){
        return CustomerTrx::where('customer_id',$this->id)
            ->where('alloc','<','amount')
            ->limit(100)
            ->get();
    }

    public static function booted()
    {
        if (Auth::guard('user')->check()) {
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
