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
 * Class MakerCheckerRule
 *
 * @property int $id
 * @property string $permission_code
 * @property int $maker_type
 * @property string $created_by
 * @property string $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MakerCheckerRule extends Model
{
	protected $table = 'maker_checker_rules';

	protected $casts = [
		'maker_type' => 'int'
	];

	protected $fillable = [
		'permission_code',
		'maker_type',
		'created_by',
		'client_ref'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new UserScope());
        }
    }
}
