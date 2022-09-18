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
    private int $min_history;

    public function __construct($min_history)
    {
        $this->min_history = $min_history;
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
        return check_password_re_use($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->min_history.' unique password(s) must be used before re-use of old password.';
    }
}
