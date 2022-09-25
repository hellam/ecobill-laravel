<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Currency
 *
 * @property int $id
 * @property string $abbreviation
 * @property string $symbol
 * @property string $name
 * @property string $hundredths_name
 * @property string $country
 * @property bool|null $auto_fx
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Currency extends Model
{
	protected $table = 'currency';

	protected $casts = [
		'auto_fx' => 'bool'
	];

	protected $fillable = [
		'abbreviation',
		'symbol',
		'name',
		'hundredths_name',
		'country',
		'auto_fx'
	];
}
