<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class ExchangeRate
 *
 * @property int $id
 * @property string $currency
 * @property float $buy_rate
 * @property float $sell_rate
 * @property int $branch_id
 * @property Carbon $date
 * @property string $client_ref
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ExchangeRate extends Model
{
	protected $table = 'exchange_rate';

	protected $casts = [
		'buy_rate' => 'float',
		'sell_rate' => 'float',
		'branch_id' => 'int'
	];

	protected $dates = [
		'date',
		'supervised_at'
	];

	protected $fillable = [
		'currency',
		'buy_rate',
		'sell_rate',
		'branch_id',
		'date',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at'
	];

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch');
    }

    public function curr(){
        return $this->belongsTo(Currency::class, 'currency','abbreviation');
    }

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new BranchScope());
            static::addGlobalScope(new ClientRefScope());
        }

    }
}
