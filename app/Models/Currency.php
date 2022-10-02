<?php

namespace App\Models;

use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Currency
 *
 * @property int $id
 * @property string $abbreviation
 * @property string $symbol
 * @property string $name
 * @property string $hundredths_name
 * @property string $country
 * @property int $auto_fx
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $client_ref
 *
 * @package App\Models
 */
class Currency extends Model
{
	protected $table = 'currency';

	protected $casts = [
		'auto_fx' => 'int'
	];

	protected $fillable = [
		'abbreviation',
		'symbol',
		'name',
		'hundredths_name',
		'country',
		'auto_fx',
		'client_ref'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
