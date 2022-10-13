<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Price
 *
 * @property int $id
 * @property string $barcode
 * @property int|null $sub_id
 * @property int $sales_type
 * @property float $price
 * @property int $branch_id
 * @property string|null $client_ref
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Price extends Model
{
	protected $table = 'prices';

	protected $casts = [
		'sub_id' => 'int',
		'sales_type' => 'int',
		'price' => 'float',
		'branch_id' => 'int'
	];

	protected $fillable = [
		'barcode',
		'sub_id',
		'sales_type',
		'price',
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
