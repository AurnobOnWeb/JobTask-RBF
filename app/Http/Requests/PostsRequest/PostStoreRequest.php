<?php

namespace App\Http\Requests\PostsRequest;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'description' => 'required|string',
            'images' => 'image|mimes:jpeg,png,jpg,gif,svg|',
            'user_id' => 'required|exists:users,id'
        ];
        
    }
}
