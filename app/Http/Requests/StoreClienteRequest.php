<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required',
            'cpf' => 'required|unique:clientes,cpf',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'endereco' => 'nullable',
            'cidade_id' => 'required|exists:cidades,id',
        ];
    }
}
