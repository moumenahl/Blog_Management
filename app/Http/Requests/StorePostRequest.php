<?php

namespace App\Http\Requests;

use App\Rules\FutureDate;
use App\Rules\KeywordsValidation;
use App\Rules\SlugValidation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class StorePostRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255|unique:posts,title',
            'slug' => ['nullable', 'string', 'unique:posts,slug', new SlugValidation()],
            'body' => 'required|string',
            'is_published' => 'nullable|boolean',
            'publish_date' => ['nullable', 'date', new FutureDate()],
            'meta_description' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'keywords' => ['nullable', 'string', new KeywordsValidation(5)], // Check up to 5 words

        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'The title has already been used, please choose another one.',
    
            'slug.string' => 'The slug must be a string.',
            'slug.unique' => 'This slug has already been taken.',
    
            'body.required' => 'The content field is required.',
            'body.string' => 'The content must be a string.',
    
            'is_published.boolean' => 'The publish status must be true or false.',
    
            'publish_date.date' => 'The publish date must be a valid date format.',
    
            'meta_description.string' => 'The meta description must be a string.',
            'meta_description.max' => 'The meta description may not be greater than 255 characters.',
    
            'tags.string' => 'The tags must be a string.',
            'tags.max' => 'The tags may not be greater than 255 characters.',
    
            'keywords.string' => 'The keywords must be a string.',
            'keywords.max' => 'The field must contain a maximum of 5 words.',
        ];
    }
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
    protected function prepareForValidation(): void
    {
        // Generate 'slug' if not provided
        $this->merge([
            'slug' => $this->filled('slug') ? $this->input('slug') : Str::slug($this->input('title')),
            
            // Generate 'meta_description' if not provided
            'meta_description' => $this->filled('meta_description') ? $this->input('meta_description') : 'Automatically generated meta description for ' . $this->input('title'),
    
            // Other fields
            'is_published' => filter_var($this->input('is_published', false), FILTER_VALIDATE_BOOLEAN),
            'keywords' => $this->input('keywords') ?: 'Updated Article',
        ]);
    }
    
    
    protected function passedValidation()
    {
        // Logging or any additional action you want to perform after validation.
        \Log::info('Post data passed validation', [
            'title' => $this->title,
            'slug' => $this->slug,
            'meta_description' => $this->meta_description,
        ]);
    }
    
    
    
// Customize error response when verification fails
protected function failedValidation(Validator $validator)
 {
    // Create a custom JSON response with error codes
     throw new HttpResponseException(
         response()->json([
             'message' => 'There are errors in the entered data.',
             'errors' => $validator->errors(),
         ], 422)
     );
 }

}
