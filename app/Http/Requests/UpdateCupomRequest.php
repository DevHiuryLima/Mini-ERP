<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCupomRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'ativo' => $this->has('ativo'),
        ]);
    }

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
        $id = $this->route('cupom')->id;
        return [
            'codigo'         => "required|string|unique:cupons,codigo,{$id}|max:50",
            'tipo_desconto'  => 'required|in:percentual,fixo',
            'valor'          => 'required|numeric|min:0.01',
            'minimo_subtotal'=> 'required|numeric|min:0',
            'validade'       => 'required|date|after_or_equal:today',
            'ativo'          => 'boolean',
        ];
    }
}
