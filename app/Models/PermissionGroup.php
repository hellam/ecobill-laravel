<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PermissionGroup
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PermissionGroup extends Model
{
	protected $table = 'permission_groups';

	protected $fillable = [
		'name'
	];

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }
}
