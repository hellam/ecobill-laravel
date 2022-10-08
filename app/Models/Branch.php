<?php

namespace App\Models;

use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property int $default_bank_account
 * @property string|null $logo
 * @property string $timezone
 * @property string $fiscal_year
 * @property int $tax_period
 * @property string $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property bool|null $is_main
 * @property int $inactive
 *
 * @package App\Models
 */
class Branch extends Model
{
	protected $table = 'branches';

	protected $casts = [
		'default_bank_account' => 'int',
		'tax_period' => 'int',
		'inactive' => 'int',
		'is_main' => 'bool'
	];

	protected $dates = [
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
		'default_bank_account',
		'logo',
		'timezone',
		'fiscal_year',
		'tax_period',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at',
		'is_main',
		'inactive'
	];

    public function bank_account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'default_bank_account');
    }

    public function fiscalyear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year');
    }

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
