<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
                'mensaje_bienvenida' => ['nullable', 'string'],
                'activar_edad' => ['nullable', 'boolean'],
                'activar_modalidad' => ['nullable', 'boolean'],
                'activar_motivo' => ['nullable', 'boolean'],
                'activar_aviso_menores' => ['nullable', 'boolean'], 
                'mensaje_aviso_menores' => ['nullable', 'string'],
                'enviar_recordatorios' => ['nullable', 'string'],
                'frecuencia_recordatorio' => ['nullable', 'string'],
        ];
    }
}
