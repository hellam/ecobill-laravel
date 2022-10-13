<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class CustAllocation
 *
 * @property int $id
 * @property float $amount
 * @property Carbon $date_alloc
 * @property int $trans_no_from
 * @property string $trans_type_from
 * @property int $trans_no_to
 * @property string $trans_type_to
 * @property int $branch_id
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
class CustAllocation extends Model
{
	protected $table = 'cust_allocations';

	protected $casts = [
		'amount' => 'float',
		'trans_no_from' => 'int',
		'trans_no_to' => 'int',
		'branch_id' => 'int'
	];

	protected $dates = [
		'date_alloc',
		'supervised_at'
	];

	protected $fillable = [
		'amount',
		'date_alloc',
		'trans_no_from',
		'trans_type_from',
		'trans_no_to',
		'trans_type_to',
		'branch_id',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
            static::addGlobalScope(new BranchScope());
        }
    }
}
