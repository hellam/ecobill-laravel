<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BranchUser
 *
 * @property int $id
 * @property int $user_id
 * @property int $branch_id
 * @property int $role_id
 * @property bool|null $default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class BranchUser extends Model
{
	protected $table = 'branch_user';

	protected $casts = [
        'role_id' => 'int',
		'user_id' => 'int',
		'branch_id' => 'int',
		'default' => 'bool'
	];

	protected $fillable = [
        'role_id',
		'user_id',
		'branch_id',
        'default'
	];
}
