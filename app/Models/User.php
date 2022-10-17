<?php

namespace App\Models;

use App\Scopes\UserTableScope;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

/**
 * Class User
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $uuid
 * @property Carbon|null $password_expiry_date
 * @property Carbon|null $account_expiry_date
 * @property string $full_name
 * @property int $role_id
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $image
 * @property int|null $failed_login_attempts
 * @property bool|null $account_locked
 * @property string $language
 * @property string $date_format
 * @property string $date_sep
 * @property string $tho_sep
 * @property string $dec_sep
 * @property string $prices_dec
 * @property string $qty_dec
 * @property string $rates_dec
 * @property string $theme
 * @property string $startup_tab
 * @property int $transaction_days
 * @property string|null $def_print_destination
 * @property string|null $created_by
 * @property string|null $supervised_by
 * @property string|null $updated_by
 * @property string|null $two_factor
 * @property int|null $first_time
 * @property Carbon|null $created_at
 * @property Carbon|null $supervised_at
 * @property Carbon|null $updated_at
 * @property int $inactive
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $guard = 'user';

    protected $casts = [
        'role_id' => 'int',
        'failed_login_attempts' => 'int',
        'account_locked' => 'bool',
        'transaction_days' => 'int',
        'first_time' => 'int',
        'inactive' => 'int'
    ];

    protected $dates = [
        'password_expiry_date',
        'account_expiry_date'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'username',
        'password',
        'uuid',
        'password_expiry_date',
        'account_expiry_date',
        'full_name',
        'role_id',
        'phone',
        'email',
        'image',
        'failed_login_attempts',
        'account_locked',
        'language',
        'date_format',
        'date_sep',
        'tho_sep',
        'dec_sep',
        'prices_dec',
        'qty_dec',
        'rates_dec',
        'theme',
        'startup_tab',
        'transaction_days',
        'def_print_destination',
        'created_by',
        'updated_by',
        'supervised_by',
        'supervised_at',
        'created_at',
        'updated_at',
        'two_factor',
        'first_time',
        'inactive'
    ];


    public static function booted()
    {
        if (Auth::guard('user')->check()) {
            static::addGlobalScope(new UserTableScope());
        }
    }

    public function permissions(): array
    {
        $branch_user = BranchUser::where(['branch_id' => get_active_branch(), 'user_id' => auth('user')->id()])->first();
        return explode(',', Role::where('id', $branch_user->role_id)->first()->permissions);
    }

    public static function logo()
    {
        $general_settings = json_decode(BusinessSetting::where('key', 'general_settings')->first()?->value, true);
        return route('user.files',
            [
                'folder' => 'company',
                'fileName' => $general_settings['logo'] ?? 'null'
            ]
        );
    }

    public static function active_branches()
    {
        $branch_user = User::with('user_branches:id,name,id')->first()
            ->whereHas('user_branches', function ($q) {
                $q->where('inactive', 0);
            })->find(auth::id());
        $branch_user->user_branches->makeHidden('pivot');
        return $branch_user->user_branches;
    }

    public function user_branches()
    {
        return $this->belongsToMany(Branch::class, BranchUser::class, 'user_id', 'branch_id')
            ->withPivot('role_id');
    }
}
