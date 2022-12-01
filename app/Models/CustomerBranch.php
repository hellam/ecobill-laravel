<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class CustomerBranch
 *
 * @property int $id
 * @property string|null $customer_id
 * @property string $f_name
 * @property string|null $l_name
 * @property string|null $short_name
 * @property string|null $branch
 * @property string|null $country
 * @property string|null $phone
 * @property string|null $email
 * @property int|null $sales_account
 * @property int|null $receivable_account
 * @property int|null $payment_discount_account
 * @property int|null $sales_discount_account
 * @property string|null $address
 * @property string|null $client_ref
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $inactive
 * @property string|null $currency
 * @property float|null $credit_limit
 *
 * @package App\Models
 */
class CustomerBranch extends Model
{
    protected $table = 'customer_branch';

    protected $casts = [
        'sales_account' => 'int',
        'receivable_account' => 'int',
        'payment_discount_account' => 'int',
        'sales_discount_account' => 'int',
        'inactive' => 'int',
        'credit_limit' => 'float'
    ];

    protected $dates = [
        'supervised_at'
    ];

    protected $fillable = [
        'customer_id',
        'f_name',
        'l_name',
        'short_name',
        'branch',
        'country',
        'phone',
        'email',
        'sales_account',
        'receivable_account',
        'payment_discount_account',
        'sales_discount_account',
        'address',
        'client_ref',
        'created_by',
        'updated_by',
        'supervised_by',
        'supervised_at',
        'inactive',
        'currency',
        'credit_limit'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function customer_balances()
    {
        return CustomerTrx::withoutGlobalScope(BranchScope::class)
            ->where('customer_branch_id', $this->id)
            ->sum('amount');
    }

    public function getUnpaidInvoices()
    {
        return CustomerTrx::where('customer_branch_id', $this->id)
            ->where('amount', '>', 'alloc')
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
