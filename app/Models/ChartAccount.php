<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChartAccount
 * 
 * @property int $account_code
 * @property string|null $account_name
 * @property int $account_group
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
class ChartAccount extends Model
{
	protected $table = 'chart_accounts';
	protected $primaryKey = 'account_code';
	public $incrementing = false;

	protected $casts = [
		'account_code' => 'int',
		'account_group' => 'int',
		'inactive' => 'bool'
	];

	protected $dates = [
		'supervised_at'
	];

	protected $fillable = [
		'account_name',
		'account_group',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at',
		'inactive'
	];
}
