<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Aqui você pode colocar regras de permissão. Por enquanto, deixamos true.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Movemos as regras de validação do controller para cá
        return [
            'nome' => ['required', 'string', 'max:255'],
            'user' => ['required', 'string', 'max:255', 'unique:'.User::class.',user'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'telefone' => ['required', 'string', 'min:10', 'max:11', 'unique:'.User::class.',telefone'],
            'datanasc' => ['required', 'date_format:d/m/Y'],
            'cpf' => ['required', 'string', 'size:11', 'unique:'.User::class.',cpf'],
            'cidade' => ['required','string', 'max:100'],
            'estado' => ['required','string', 'max:2'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // A MÁGICA ACONTECE AQUI!
        // Este método roda ANTES da validação.
        $this->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $this->cpf),
            'telefone' => preg_replace('/[^0-9]/', '', $this->telefone),
            
        ]);
    }

    private function formatarData($data)
{
    // Transforma "06/03/2008" em "2008-03-06"
    try {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
    } catch (\Exception $e) {
        return $data; // deixa como está se falhar
    }
}

}