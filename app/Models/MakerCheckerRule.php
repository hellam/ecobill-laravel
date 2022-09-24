<?php

namespace App\Models;

use App\Scopes\UserScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class MakerCheckerRule
 *
 * @property int $id
 * @property string $permission_code
 * @property int $maker_type
 * @property string $created_by
 * @property string $updated_by
 * @property string $supervised_by
 * @property string $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $supervised_at
 * @property Carbon|null $updated_at
 * @property int|null $inactive
 *
 * @package App\Models
 */
class MakerCheckerRule extends Model
{
	protected $table = 'maker_checker_rules';

	protected $casts = [
		'maker_type' => 'int',
		'inactive' => 'int'
	];

	protected $fillable = [
		'permission_code',
		'maker_type',
		'created_by',
		'client_ref',
        'created_by',
        'updated_by',
        'supervised_by',
        'supervised_at',
		'inactive'
	];

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_code', 'code');
    }

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new UserScope());
        }
    }
}
