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
 * Class CustomerTrxDetail
 *
 * @property int $id
 * @property int $trans_no
 * @property string $trx_type
 * @property string $barcode
 * @property int $stock_id
 * @property string $description
 * @property float $unit_price
 * @property float $unit_tax
 * @property float $qty
 * @property float $discount
 * @property float $cost
 * @property float $qty_done
 * @property string|null $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class CustomerTrxDetail extends Model
{
	protected $table = 'customer_trx_details';

	protected $casts = [
		'trans_no' => 'int',
		'stock_id' => 'int',
		'unit_price' => 'float',
		'unit_tax' => 'float',
		'qty' => 'float',
		'discount' => 'float',
		'cost' => 'float',
		'qty_done' => 'float'
	];

	protected $fillable = [
		'trans_no',
		'trx_type',
		'barcode',
		'stock_id',
		'description',
		'unit_price',
		'unit_tax',
		'qty',
		'discount',
		'cost',
		'qty_done',
		'client_ref'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
