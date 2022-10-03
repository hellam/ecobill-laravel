<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExchangeRate
 * 
 * @property int $id
 * @property string $currency
 * @property float $buy_rate
 * @property float $sell_rate
 * @property int $branch
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
		'branch' => 'int'
	];

	protected $dates = [
		'date',
		'supervised_at'
	];

	protected $fillable = [
		'currency',
		'buy_rate',
		'sell_rate',
		'branch',
		'date',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at'
	];
}
