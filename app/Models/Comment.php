<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Comment
 *
 * @property int $id
 * @property string $trx_type
 * @property int $trx_no
 * @property Carbon $trx_date
 * @property string $comment
 * @property int $branch_id
 * @property string|null $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Comment extends Model
{
	protected $table = 'comments';

	protected $casts = [
		'trx_no' => 'int',
		'branch_id' => 'int'
	];

	protected $dates = [
		'trx_date'
	];

	protected $fillable = [
		'trx_type',
		'trx_no',
		'trx_date',
		'comment',
		'branch_id',
		'client_ref'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
            static::addGlobalScope(new BranchScope());
        }
    }
}
