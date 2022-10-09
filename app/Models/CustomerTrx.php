<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerTrx
 * 
 * @property int $id
 * @property int $trans_no
 * @property string $trx_type
 * @property int $customer_id
 * @property int $customer_branch_id
 * @property Carbon|null $trx_date
 * @property Carbon|null $due_date
 * @property string $reference
 * @property int $order_id
 * @property float $amount
 * @property float $discount
 * @property float $alloc
 * @property int|null $payment_terms
 * @property int $is_tax_included
 * @property string|null $client_ref
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class CustomerTrx extends Model
{
	protected $table = 'customer_trx';

	protected $casts = [
		'trans_no' => 'int',
		'customer_id' => 'int',
		'customer_branch_id' => 'int',
		'order_id' => 'int',
		'amount' => 'float',
		'discount' => 'float',
		'alloc' => 'float',
		'payment_terms' => 'int',
		'is_tax_included' => 'int'
	];

	protected $dates = [
		'trx_date',
		'due_date',
		'supervised_at'
	];

	protected $fillable = [
		'trans_no',
		'trx_type',
		'customer_id',
		'customer_branch_id',
		'trx_date',
		'due_date',
		'reference',
		'order_id',
		'amount',
		'discount',
		'alloc',
		'payment_terms',
		'is_tax_included',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at'
	];
}