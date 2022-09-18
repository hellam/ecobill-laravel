<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use function App\CentralLogics\check_password_re_use;

class PasswordHistoryRule implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private array $password_policy_array;

    public function __construct($password_policy_array)
    {
        $this->password_policy_array = $password_policy_array;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return check_password_re_use($value, $this->password_policy_array);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute unique password(s) must be used before re-use of old password.';
    }
}
