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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class BranchUser extends Model
{
	protected $table = 'branch_user';

	protected $casts = [
		'user_id' => 'int',
		'branch_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'branch_id'
	];
}
