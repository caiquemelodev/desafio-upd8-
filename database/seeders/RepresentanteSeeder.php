<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Representante;

class RepresentanteSeeder extends Seeder
{
    public function run(): void
    {
        Representante::insert([
            ['nome' => 'Carlos Silva', 'cpf' => '111.111.111-11', 'telefone' => '11999999999', 'cidade_id' => 1],
            ['nome' => 'Ana Souza', 'cpf' => '222.222.222-22', 'telefone' => '21988888888', 'cidade_id' => 3],
            ['nome' => 'Pedro Lima', 'cpf' => '333.333.333-33', 'telefone' => '47977777777', 'cidade_id' => 4],
            ['nome' => 'Maria Oliveira', 'cpf' => '444.444.444-44', 'telefone' => '11966666666', 'cidade_id' => 2],
        ]);
    }
}
