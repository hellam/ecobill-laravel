<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Scopes\UserScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class MakerCheckerTrx
 *
 * @property int $id
 * @property int $mc_type
 * @property string $trx_type
 * @property string $status
 * @property string|null $txt_data
 * @property string $method
 * @property string $module
 * @property string $url
 * @property int $maker
 * @property int $checker1
 * @property int $checker2
 * @property string $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $description
 * @property int $branch_id
 *
 * @package App\Models
 */
class MakerCheckerTrx extends Model
{
	protected $table = 'maker_checker_trx';

	protected $casts = [
		'mc_type' => 'int',
		'maker' => 'int',
		'checker1' => 'int',
		'checker2' => 'int'
	];

	protected $fillable = [
		'mc_type',
		'trx_type',
		'status',
		'txt_data',
		'method',
		'module',
		'url',
		'maker',
		'checker1',
		'checker2',
		'client_ref',
		'description',
        'branch_id'
	];



    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new UserScope());
        }
    }
}
