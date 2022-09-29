<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Permission
 *
 * @property int $id
 * @property string $name
 * @property string|null $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $code
 * @property bool|null $requires_maker_checker
 * @property bool|null $requires_hq
 * @property string|null $maker_validator_function
 *
 * @package App\Models
 */
class Permission extends Model
{
	protected $table = 'permissions';

	protected $casts = [
		'requires_maker_checker' => 'bool',
		'requires_hq' => 'bool'
	];

	protected $fillable = [
		'name',
		'parent_id',
		'code',
		'requires_hq',
		'requires_maker_checker',
		'maker_validator_function'
	];

    public function maker_checker_rules(){
        $this->hasOne(MakerCheckerRule::class, 'permissions_code');
    }

    public function permission_group(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class, 'parent_id');
    }
}
