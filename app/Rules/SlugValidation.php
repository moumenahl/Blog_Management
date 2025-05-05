<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SlugValidation implements ValidationRule
{
    /**
     * Validate that the value is a valid slug.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Ensure the slug contains only lowercase letters, numbers, and hyphens
        if (!preg_match('/^[a-z0-9-]+$/', $value)) {
            // If validation fails, throw a validation error
            $fail(":attribute must contain only lowercase letters, numbers, and hyphens.");
        }
    }
}
