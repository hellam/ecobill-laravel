<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property string $barcode
 * @property string|null $name
 * @property string|null $description
 * @property float|null $price
 * @property float|null $cost
 * @property int|null $order
 * @property int $category_id
 * @property int $tax_id
 * @property int $type
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
class Product extends Model
{
	protected $table = 'products';

	protected $casts = [
		'price' => 'float',
		'cost' => 'float',
		'order' => 'int',
		'category_id' => 'int',
		'tax_id' => 'int',
		'type' => 'int',
		'inactive' => 'bool'
	];

	protected $dates = [
		'supervised_at'
	];

	protected $fillable = [
		'barcode',
		'name',
		'description',
		'price',
		'cost',
		'order',
		'category_id',
		'tax_id',
		'type',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at',
		'inactive'
	];
}