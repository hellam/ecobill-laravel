<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class ChartClass
 *
 * @property int $id
 * @property string|null $class_name
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
class ChartClass extends Model
{
	protected $table = 'chart_class';

	protected $casts = [
		'inactive' => 'int'
	];

	protected $dates = [
		'supervised_at'
	];

	protected $fillable = [
		'class_name',
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
}
