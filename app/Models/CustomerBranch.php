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
 * Class CustomerBranch
 *
 * @property int $id
 * @property string|null $customer_id
 * @property string $f_name
 * @property string|null $l_name
 * @property string|null $short_name
 * @property string|null $branch
 * @property string|null $country
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $client_ref
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $supervised_by
 * @property Carbon|null $supervised_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $inactive
 *
 * @package App\Models
 */
class CustomerBranch extends Model
{
	protected $table = 'customer_branch';

	protected $casts = [
		'inactive' => 'int'
	];

	protected $dates = [
		'supervised_at'
	];

	protected $fillable = [
		'customer_id',
		'f_name',
		'l_name',
		'short_name',
		'branch',
		'country',
		'phone',
		'email',
		'address',
		'client_ref',
		'created_by',
		'updated_by',
		'supervised_by',
		'supervised_at',
		'inactive'
	];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public static function booted()
    {
        if (Auth::guard('user')->check()){
            static::addGlobalScope(new ClientRefScope());
        }
    }
}
