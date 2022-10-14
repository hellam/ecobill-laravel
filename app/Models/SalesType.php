<?php

namespace App\Models;

use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class SalesType
 *
 * @property int $id
 * @property string $name
 * @property int $tax_included
 * @property float $factor
 * @property int $is_default
 * @property string $inactive
 * @property string|null $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SalesType extends Model
{
	protected $table = 'sales_types';

	protected $casts = [
		'tax_included' => 'int',
		'factor' => 'float',
		'is_default' => 'int'
	];

	protected $fillable = [
		'name',
		'tax_included',
		'factor',
		'is_default',
		'inactive',
		'client_ref'
	];

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
