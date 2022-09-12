<?php

/**
 * Created by Reliese Model.
 */

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
 *
 * @package App\Models
 */
class Permission extends Model
{
	protected $table = 'permissions';

	protected $fillable = [
		'code',
		'name',
		'parent_id'
	];


    public function permission_group(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class, 'parent_id');
    }
}
