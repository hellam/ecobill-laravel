<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class ChartGroup
 *
 * @property int $id
 * @property string|null $name
 * @property int $class_id
 * @property string|null $client_ref
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property int|null $inactive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ChartGroup extends Model
{
	protected $table = 'chart_groups';

	protected $casts = [
		'class_id' => 'int',
		'inactive' => 'int'
	];

	protected $dates = [
		'supervised_at'
	];

	protected $fillable = [
		'name',
		'class_id',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at',
		'inactive'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
        }
    }

    public function classes(): BelongsTo
    {
        return $this->belongsTo(ChartClass::class, 'class_id');
    }
}
