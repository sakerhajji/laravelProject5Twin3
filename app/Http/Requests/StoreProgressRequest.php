<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'objective_id' => ['required','exists:objectives,id'],
            'entry_date' => ['required','date'],
            'value' => ['required','numeric','min:0'],
            'note' => ['nullable','string','max:500'],
        ];
    }
}


