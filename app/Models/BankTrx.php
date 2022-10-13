<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class BankTrx
 *
 * @property int $id
 * @property int $trans_no
 * @property string $trx_type
 * @property string $reference
 * @property int $bank_id
 * @property float $amount
 * @property Carbon|null $trx_date
 * @property Carbon|null $reconciled
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
class BankTrx extends Model
{
	protected $table = 'bank_trx';

	protected $casts = [
		'trans_no' => 'int',
		'bank_id' => 'int',
		'amount' => 'float',
		'branch_id' => 'int'
	];

	protected $dates = [
		'trx_date',
		'reconciled',
		'supervised_at'
	];

	protected $fillable = [
		'trans_no',
		'trx_type',
		'reference',
		'bank_id',
		'amount',
		'trx_date',
		'reconciled',
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
