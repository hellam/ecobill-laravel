<?php

namespace App\Models;

use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class BranchUser
 *
 * @property int $id
 * @property int $user_id
 * @property int $branch_id
 * @property int $role_id
 * @property string $client_ref
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
        'default',
        'client_ref'
	];


    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
        }
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
