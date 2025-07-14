<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRepresentanteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Corrige o nome do parâmetro de rota para garantir que o ID seja obtido corretamente
        $id = $this->route('representante') ?? $this->route('id');
        return [
            'nome' => 'required',
            'cpf' => 'required|unique:representantes,cpf,' . $id,
            'telefone' => 'nullable',
            'cidade_id' => 'required|exists:cidades,id',
        ];
    }
}
