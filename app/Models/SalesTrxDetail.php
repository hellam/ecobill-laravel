<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SalesTrxDetail
 *
 * @property int $id
 * @property int $trans_no
 * @property string $trx_type
 * @property string $barcode
 * @property string $description
 * @property float $qty_sent
 * @property float $unit_price
 * @property float $discount
 * @property float $invoiced
 * @property float $qty
 * @property string|null $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SalesTrxDetail extends Model
{
	protected $table = 'sales_trx_details';

	protected $casts = [
		'trans_no' => 'int',
		'qty_sent' => 'float',
		'unit_price' => 'float',
		'discount' => 'float',
		'invoiced' => 'float',
		'qty' => 'float'
	];

	protected $fillable = [
		'trans_no',
		'trx_type',
		'barcode',
		'description',
		'qty_sent',
		'unit_price',
		'discount',
		'invoiced',
		'qty',
		'client_ref'
	];
}
