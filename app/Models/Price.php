<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Price
 *
 * @property int $id
 * @property string $barcode
 * @property int|null $sub_id
 * @property int $sales_type
 * @property float $price
 * @property string|null $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Price extends Model
{
	protected $table = 'prices';

	protected $casts = [
		'sub_id' => 'int',
		'sales_type' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'barcode',
		'sub_id',
		'sales_type',
		'price',
		'client_ref'
	];
}
