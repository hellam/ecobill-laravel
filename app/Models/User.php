<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $uuid
 * @property Carbon|null $password_expiry_date
 * @property Carbon|null $account_expiry_date
 * @property string $full_name
 * @property int $role_id
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $image
 * @property int|null $failed_login_attempts
 * @property bool|null $account_locked
 * @property string $language
 * @property string $date_format
 * @property string $date_sep
 * @property string $tho_sep
 * @property string $dec_sep
 * @property string $prices_dec
 * @property string $qty_dec
 * @property string $rates_dec
 * @property string $theme
 * @property string $startup_tab
 * @property int $transaction_days
 * @property string|null $def_print_destination
 * @property string|null $created_by
 * @property string|null $last_updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $inactive
 * @property string|null $remember_token
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	protected $table = 'users';

	protected $casts = [
		'role_id' => 'int',
		'failed_login_attempts' => 'int',
		'account_locked' => 'bool',
		'transaction_days' => 'int',
		'inactive' => 'bool'
	];

	protected $dates = [
		'password_expiry_date',
		'account_expiry_date'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'username',
		'password',
		'uuid',
		'password_expiry_date',
		'account_expiry_date',
		'full_name',
		'role_id',
		'phone',
		'email',
		'image',
		'failed_login_attempts',
		'account_locked',
		'language',
		'date_format',
		'date_sep',
		'tho_sep',
		'dec_sep',
		'prices_dec',
		'qty_dec',
		'rates_dec',
		'theme',
		'startup_tab',
		'transaction_days',
		'def_print_destination',
		'created_by',
		'last_updated_by',
		'inactive',
		'remember_token'
	];
}
