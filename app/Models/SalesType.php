<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SalesType
 *
 * @property int $id
 * @property string $name
 * @property int $tax_included
 * @property float $factor
 * @property string $inactive
 * @property string|null $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SalesType extends Model
{
	protected $table = 'sales_types';

	protected $casts = [
		'tax_included' => 'int',
		'factor' => 'float'
	];

	protected $fillable = [
		'name',
		'tax_included',
		'factor',
		'inactive',
		'client_ref'
	];
}
