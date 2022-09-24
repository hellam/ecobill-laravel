<?php

namespace App\Models;

use App\Scopes\UserScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuditTrail
 *
 * @property int $id
 * @property string $type
 * @property int|null $trans_no
 * @property int|null $user
 * @property string|null $api_token
 * @property string|null $description
 * @property string|null $model
 * @property string $request_details
 * @property string $ip_address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $client_ref
 * @property int $branch_id
 *
 * @package App\Models
 */
class AuditTrail extends Model
{
	protected $table = 'audit_trail';

	protected $casts = [
		'trans_no' => 'int',
		'user' => 'int'
	];

	protected $hidden = [
		'api_token'
	];

	protected $fillable = [
		'type',
		'trans_no',
		'user',
		'api_token',
		'description',
		'model',
		'request_details',
		'ip_address',
		'client_ref',
		'branch_id'
	];


    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new UserScope());
        }

    }
}
