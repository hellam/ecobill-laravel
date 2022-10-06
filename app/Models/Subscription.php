<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class Subscription
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $image
 * @property string|null $description
 * @property string|null $features
 * @property float|null $price
 * @property float|null $cost
 * @property int $validity
 * @property string|null $client_ref
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property bool|null $inactive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $order
 *
 * @package App\Models
 */
class Subscription extends Model
{
	protected $table = 'subscriptions';

	protected $casts = [
		'product_id' => 'int',
		'price' => 'float',
		'cost' => 'float',
		'validity' => 'int',
		'inactive' => 'bool',
		'order' => 'int'
	];

	protected $dates = [
		'supervised_at'
	];

	protected $fillable = [
		'product_id',
		'name',
		'image',
		'description',
		'features',
		'price',
		'cost',
		'validity',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at',
		'inactive',
		'order'
	];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public static function booted()
    {
        if (Auth::guard('user')->check()) {
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
