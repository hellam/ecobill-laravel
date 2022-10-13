<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class GlTrx
 *
 * @property int $id
 * @property int $trans_no
 * @property string $trx_type
 * @property Carbon|null $trx_date
 * @property int $chart_code
 * @property string $narration
 * @property float $amount
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
class GlTrx extends Model
{
	protected $table = 'gl_trx';

	protected $casts = [
		'trans_no' => 'int',
		'chart_code' => 'int',
		'amount' => 'float',
		'branch_id' => 'int'
	];

	protected $dates = [
		'trx_date',
		'supervised_at'
	];

	protected $fillable = [
		'trans_no',
		'trx_type',
		'trx_date',
		'chart_code',
		'narration',
		'amount',
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
