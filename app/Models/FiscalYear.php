<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class FiscalYear
 *
 * @property int $id
 * @property Date $begin
 * @property Date $end
 * @property string $client_ref
 * @property bool $closed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class FiscalYear extends Model
{
	protected $table = 'fiscal_year';

	protected $casts = [
		'closed' => 'bool'
	];

	protected $dates = [
		'begin',
		'end'
	];

	protected $fillable = [
		'begin',
		'end',
		'client_ref',
		'closed'
	];


    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
