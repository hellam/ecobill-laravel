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
 * @property string $bcc_email
 * @property string $tax_no
 * @property string $default_currency
 * @property string $logo
 * @property string $timezone
 * @property string $fiscal_year
 * @property string $client_ref
 * @property string $created_by
 * @property string $supervised_by
 * @property string $updated_by
 * @property int $tax_period
 * @property bool|null $is_main
 * @property Carbon|null $tax_start_date
 * @property Carbon|null $created_at
 * @property Carbon|null $supervised_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Branch extends Model
{
	protected $table = 'branches';

    protected $casts = [
        'is_main' => 'bool',
        'tax_period' => 'int',
    ];
	protected $fillable = [
		'name',
		'address',
		'phone',
		'email',
		'is_main',
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
        'created_at',
        'updated_at',
	];



    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
