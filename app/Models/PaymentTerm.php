<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentTerm
 * 
 * @property int $id
 * @property string $terms
 * @property int $type
 * @property int $days
 * @property string $client_ref
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property int $inactive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PaymentTerm extends Model
{
	protected $table = 'payment_terms';

	protected $casts = [
		'type' => 'int',
		'days' => 'int',
		'inactive' => 'int'
	];

	protected $dates = [
		'supervised_at'
	];

	protected $fillable = [
		'terms',
		'type',
		'days',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at',
		'inactive'
	];
}
