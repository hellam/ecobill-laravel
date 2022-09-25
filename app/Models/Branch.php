<?php

namespace App\Models;

use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Branch
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string|null $bcc_email
 * @property string $tax_no
 * @property string $default_currency
 * @property string|null $logo
 * @property string $timezone
 * @property string $fiscal_year
 * @property int $tax_period
 * @property Carbon $tax_start_date
 * @property string $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property bool|null $is_main
 *
 * @package App\Models
 */
class Branch extends Model
{
	protected $table = 'branches';

	protected $casts = [
		'tax_period' => 'int',
		'is_main' => 'bool'
	];

	protected $dates = [
		'tax_start_date',
		'supervised_at'
	];

	protected $fillable = [
		'name',
		'address',
		'phone',
		'email',
		'bcc_email',
		'tax_no',
		'default_currency',
		'logo',
		'timezone',
		'fiscal_year',
		'tax_period',
		'tax_start_date',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at',
		'is_main'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
