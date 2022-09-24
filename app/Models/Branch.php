<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
 * @property string $tax_period
 * @property string $tax_last_period
 * @property string $client_ref
 * @property string $created_by
 * @property string $supervised_by
 * @property string $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $supervised_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Branch extends Model
{
	protected $table = 'branches';

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
		'tax_last_period',
		'client_ref',
        'created_by',
        'updated_by',
        'supervised_by',
        'supervised_at',
	];
}
