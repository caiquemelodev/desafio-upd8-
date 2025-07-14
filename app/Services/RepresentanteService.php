<?php

namespace App\Services;

use App\Models\Representante;

class RepresentanteService
{
    public function create(array $data)
    {
        return Representante::create($data);
    }

    public function update(Representante $representante, array $data)
    {
        $representante->update($data);
        return $representante;
    }

    public function delete($id)
    {
        return Representante::destroy($id);
    }
}
