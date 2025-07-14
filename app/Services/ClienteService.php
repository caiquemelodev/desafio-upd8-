<?php

namespace App\Services;

use App\Models\Cliente;

class ClienteService
{
    public function create(array $data)
    {
        return Cliente::create($data);
    }

    public function update(Cliente $cliente, array $data)
    {
        $cliente->update($data);
        return $cliente;
    }

    public function delete($id)
    {
        return Cliente::destroy($id);
    }
}
