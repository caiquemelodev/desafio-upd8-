<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClienteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Corrige o nome do parâmetro de rota para garantir que o ID seja obtido corretamente
        $id = $this->route('cliente') ?? $this->route('id');
        return [
            'nome' => 'required',
            'cpf' => 'required|unique:clientes,cpf,' . $id,
            'data_nascimento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'endereco' => 'nullable',
            'cidade_id' => 'required|exists:cidades,id',
        ];
    }
}
