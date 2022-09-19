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
 * @property int $trx_type
 * @property string $status
 * @property string|null $txt_data
 * @property string $file_data
 * @property string $url
 * @property int $maker
 * @property int $checker1
 * @property int $checker2
 * @property string $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $description
 *
 * @package App\Models
 */
class MakerCheckerTrx extends Model
{
	protected $table = 'maker_checker_trx';

	protected $casts = [
		'mc_type' => 'int',
		'trx_type' => 'int',
		'maker' => 'int',
		'checker1' => 'int',
		'checker2' => 'int'
	];

	protected $fillable = [
		'mc_type',
		'trx_type',
		'status',
		'txt_data',
		'file_data',
		'url',
		'maker',
		'checker1',
		'checker2',
		'client_ref',
		'description'
	];



    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new UserScope());
        }
    }
}
