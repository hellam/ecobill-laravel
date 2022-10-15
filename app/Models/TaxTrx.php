<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class TaxTrx
 *
 * @property int $id
 * @property string $trx_type
 * @property int $trx_no
 * @property Carbon $trx_date
 * @property int $tax_id
 * @property float $rate
 * @property int $included_in_price
 * @property float $net_amount
 * @property float $amount
 * @property string $reference
 * @property int $branch_id
 * @property string|null $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TaxTrx extends Model
{
	protected $table = 'tax_trx';

	protected $casts = [
		'trx_no' => 'int',
		'tax_id' => 'int',
		'rate' => 'float',
		'included_in_price' => 'int',
		'net_amount' => 'float',
		'amount' => 'float',
		'branch_id' => 'int'
	];

	protected $dates = [
		'trx_date'
	];

	protected $fillable = [
		'trx_type',
		'trx_no',
		'trx_date',
		'tax_id',
		'rate',
		'included_in_price',
		'net_amount',
		'amount',
		'reference',
		'branch_id',
		'client_ref'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
            static::addGlobalScope(new BranchScope());
        }
    }
}
