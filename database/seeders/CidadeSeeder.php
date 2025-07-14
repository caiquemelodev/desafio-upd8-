<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cidade;

class CidadeSeeder extends Seeder
{
    public function run(): void
    {
        Cidade::insert([
            ['nome' => 'Guarulhos', 'estado' => 'SP'],
            ['nome' => 'São Paulo', 'estado' => 'SP'],
            ['nome' => 'Trindade', 'estado' => 'RJ'],
            ['nome' => 'Vilage', 'estado' => 'SC'],
        ]);
    }
}
