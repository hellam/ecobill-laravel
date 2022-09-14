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
 * Class SecurityConfig
 *
 * @property int $id
 * @property string $client_ref
 * @property string $general_security
 * @property string $password_policy
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SecurityConfig extends Model
{
	protected $table = 'security_configs';

	protected $fillable = [
		'client_ref',
		'general_security',
		'password_policy'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()) {
            static::addGlobalScope(new UserScope());
        }
    }
}
