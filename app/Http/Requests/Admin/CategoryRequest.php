<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

  public function rules(): array
{
    return [
        'name'  => 'required|string|max:255',
        // Gunakan 'image' untuk memastikan itu benar-benar file gambar
        'photo' => 'required',
    ];
}
}