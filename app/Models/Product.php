<?php

namespace App\Models;

use App\Scopes\ClientRefScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class Product
 *
 * @property int $id
 * @property string $barcode
 * @property string|null $image
 * @property string|null $name
 * @property string|null $description
 * @property float|null $price
 * @property float|null $cost
 * @property int|null $order
 * @property int $category_id
 * @property int $tax_id
 * @property string|null $client_ref
 * @property int $type
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property int $inactive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Product extends Model
{
    protected $table = 'products';

    protected $casts = [
        'price' => 'float',
        'cost' => 'float',
        'order' => 'int',
        'category_id' => 'int',
        'tax_id' => 'int',
        'type' => 'int',
        'inactive' => 'int'
    ];

    protected $dates = [
        'supervised_at'
    ];

    protected $fillable = [
        'barcode',
        'image',
        'name',
        'description',
        'price',
        'cost',
        'order',
        'category_id',
        'tax_id',
        'client_ref',
        'type',
        'created_by',
        'updated_by',
        'supervised_by',
        'supervised_at',
        'inactive'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'tax_id');
    }

    public static function subscriptions($query){
        return $query->where('type', 1);
    }
    public static function products($query){
        return $query->where('type', 0);
    }

    public function type_name(): string
    {
        return $this->type == 0 ? 'product' : 'subscription';
    }

    public static function booted()
    {
        if (Auth::guard('user')->check()) {
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
