<?php

namespace App\Models;

use App\Scopes\ClientRefScope;
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
 * @property string $created_by
 * @property string $updated_by
 * @property string $supervised_by
 * @property Carbon|null $created_at
 * @property Carbon|null $supervised_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SecurityConfig extends Model
{
	protected $table = 'security_configs';


    protected $hidden = [
        'client_ref'
    ];

	protected $fillable = [
		'client_ref',
		'general_security',
		'password_policy',
        'created_by',
        'updated_by',
        'supervised_by',
        'supervised_at',
        'created_at',
        'updated_at',
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()) {
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
