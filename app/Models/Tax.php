<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tax
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $rate
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property bool|null $inactive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Tax extends Model
{
	protected $table = 'tax';

	protected $casts = [
		'rate' => 'int',
		'inactive' => 'bool'
	];

	protected $dates = [
		'supervised_at'
	];

	protected $fillable = [
		'name',
		'description',
		'rate',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at',
		'inactive'
	];
}
