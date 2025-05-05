<?php

namespace App\Http\Requests;

use App\Rules\FutureDate;
use App\Rules\KeywordsValidation;
use App\Rules\SlugValidation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $postId = $this->route('post')?->id;

        return [
            'title' => 'required|string|max:255|unique:posts,title,' . $postId, // Exclude the current post from uniqueness check
            'slug' => ['nullable', 'string', 'unique:posts,slug,' . $postId, new SlugValidation()], // Custom slug validation rule
            'body' => 'required|string',
            'is_published' => 'nullable|boolean',
            'publish_date' => ['nullable', 'date', new FutureDate()], // Custom publish_date validation rule
            'meta_description' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'keywords' => ['nullable', 'string', new KeywordsValidation(5)], // Max 5 keywords
        ];
    }

    /**
     * Customize error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'The title has already been taken, please choose another one.',

            'slug.string' => 'The slug must be a string.',
            'slug.unique' => 'This slug has already been taken.',

            'body.required' => 'The content field is required.',
            'body.string' => 'The content must be a string.',

            'is_published.boolean' => 'The publish status must be true or false.',

            'publish_date.date' => 'The publish date format is invalid.',

            'meta_description.string' => 'The meta description must be a string.',
            'meta_description.max' => 'The meta description may not be greater than 255 characters.',

            'tags.string' => 'Tags must be a string.',
            'tags.max' => 'Tags may not exceed 255 characters.',

            'keywords.string' => 'The keywords must be a string.',
            'keywords.max' => 'The field may contain a maximum of 5 keywords.',
        ];
    }

    /**
     * Customize attribute names for better readability in errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'slug' => 'Slug',
            'body' => 'Content',
            'is_published' => 'Publish Status',
            'publish_date' => 'Publish Date',
            'meta_description' => 'Meta Description',
            'tags' => 'Tags',
            'keywords' => 'Keywords',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // If 'slug' is empty, generate it from the title
            'slug' => $this->filled('slug') ? $this->input('slug') : Str::slug($this->input('title')),
    
            // Ensure 'is_published' is a boolean
            'is_published' => filter_var($this->input('is_published', false), FILTER_VALIDATE_BOOLEAN),
    
            // Use a default value for 'keywords' if not provided
            'keywords' => $this->input('keywords') ?: 'Updated Post',
    
            // Generate 'meta_description' if it's not provided
            'meta_description' => $this->input('meta_description') ?: 'Automatically generated meta description for ' . $this->input('title'),
        ]);
    }

    /**
     * create one based on the title 
     * after successful validation.
     */
    protected function passedValidation()
    {
        // Logging or any additional action you want to perform after validation.
        \Log::info('Post data passed validation', [
            'title' => $this->title,
            'slug' => $this->slug,
            'meta_description' => $this->meta_description,
        ]);
    }
    
    /**
     * Customize the response for failed validation.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'There are validation errors in your input.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
