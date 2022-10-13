<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Attachment
 *
 * @property int $id
 * @property string $description
 * @property int $trans_no
 * @property Carbon|null $trx_date
 * @property string $trx_type
 * @property string $file_name
 * @property int $file_size
 * @property string $file_type
 * @property int $branch_id
 * @property string|null $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Attachment extends Model
{
	protected $table = 'attachments';

	protected $casts = [
		'trans_no' => 'int',
		'file_size' => 'int',
		'branch_id' => 'int'
	];

	protected $dates = [
		'trx_date'
	];

	protected $fillable = [
		'description',
		'trans_no',
		'trx_date',
		'trx_type',
		'file_name',
		'file_size',
		'file_type',
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
