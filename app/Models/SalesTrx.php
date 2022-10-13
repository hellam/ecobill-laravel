<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SalesTrx
 *
 * @property int $id
 * @property int $trans_no
 * @property string $trx_type
 * @property int $customer_id
 * @property int $customer_branch_id
 * @property Carbon|null $trx_date
 * @property Carbon|null $due_date
 * @property string $reference
 * @property string|null $comments
 * @property string|null $delivery_address
 * @property string|null $contact_phone
 * @property string|null $contact_email
 * @property string|null $delivery_to
 * @property int $payment_terms
 * @property float $amount
 * @property float $alloc
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
class SalesTrx extends Model
{
	protected $table = 'sales_trx';

	protected $casts = [
		'trans_no' => 'int',
		'customer_id' => 'int',
		'customer_branch_id' => 'int',
		'payment_terms' => 'int',
		'amount' => 'float',
		'alloc' => 'float',
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
		'comments',
		'delivery_address',
		'contact_phone',
		'contact_email',
		'delivery_to',
		'payment_terms',
		'amount',
		'alloc',
		'is_tax_included',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at'
	];
}
