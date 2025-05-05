<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class KeywordsValidation implements ValidationRule
{
    protected int $maxWords;

    /**
     * Constructor to set the maximum number of words.
     *
     * @param  int  $maxWords
     * @return void
     */
    public function __construct(int $maxWords)
    {
        $this->maxWords = $maxWords;
    }

    /**
     * Validate that the keywords do not exceed the maximum word count.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If the value is not empty
        if ($value && str_word_count($value) > $this->maxWords) {
            // If word count exceeds the limit, throw a validation error
            $fail(":attribute may contain a maximum of " . $this->maxWords . " words.");
        }
    }
}
