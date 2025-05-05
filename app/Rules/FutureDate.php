<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class FutureDate implements ValidationRule
{
    /**
     * Validate that the value is a future date.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value is a future date
        if (strtotime($value) <= time()) {
            // If the date is not in the future, throw a validation error
            $fail("The :attribute must be a future date.");
        }
    }
}
