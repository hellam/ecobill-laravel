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
 * Class BankAccount
 *
 * @property int $id
 * @property string $account_name
 * @property string|null $account_number
 * @property string|null $entity_name
 * @property string|null $entity_address
 * @property string $currency
 * @property bool|null $is_default
 * @property Carbon|null $last_reconcile_date
 * @property Carbon|null $ending_reconcile_balance
 * @property int $chart_code
 * @property int $charge_chart_code
 * @property string $client_ref
 * @property int $branch_id
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $inactive
 *
 * @package App\Models
 */
class BankAccount extends Model
{
	protected $table = 'bank_accounts';

	protected $casts = [
		'is_default' => 'bool',
		'chart_code' => 'int',
		'charge_chart_code' => 'int',
		'branch_id' => 'int',
		'inactive' => 'bool'
	];

	protected $dates = [
		'last_reconcile_date',
		'ending_reconcile_balance',
		'supervised_at'
	];

	protected $fillable = [
		'account_name',
		'account_number',
		'entity_name',
		'entity_address',
		'currency',
		'is_default',
		'last_reconcile_date',
		'ending_reconcile_balance',
		'chart_code',
		'charge_chart_code',
		'client_ref',
		'branch_id',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at',
		'inactive'
	];

    public function chart_account(){
        return $this->belongsTo(ChartAccount::class, 'chart_code', 'account_code');
    }

    public function charge_chart_account(){
        return $this->belongsTo(ChartAccount::class, 'charge_chart_code', 'account_code');
    }

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new BranchScope());
            static::addGlobalScope(new ClientRefScope());
        }

    }
}
