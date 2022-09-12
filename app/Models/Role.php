<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Scopes\UserScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Role
 *
 * @property int $id
 * @property string $name
 * @property string $permissions
 * @property string $client_ref
 * @property string $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'roles';

	protected $fillable = [
		'name',
		'permissions',
		'client_ref',
		'created_by'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new UserScope());
        }
    }
}
