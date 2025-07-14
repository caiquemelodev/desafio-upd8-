<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRepresentanteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required',
            'cpf' => 'required|unique:representantes,cpf',
            'telefone' => 'nullable',
            'cidade_id' => 'required|exists:cidades,id',
        ];
    }
}
