<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordHistory
 *
 * @property int $id
 * @property int $user_id
 * @property string $password
 * @property int|null $created_by
 * @property int|null $last_updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PasswordHistory extends Model
{
	protected $table = 'password_history';

	protected $casts = [
		'user_id' => 'int',
		'created_by' => 'int',
		'last_updated_by' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'user_id',
		'password',
		'created_by',
		'last_updated_by'
	];
}
