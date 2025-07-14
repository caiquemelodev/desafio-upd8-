<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::insert([
            [
                'nome' => 'Wesley Barbosa',
                'cpf' => '378.658.658-00',
                'data_nascimento' => '1990-06-06',
                'sexo' => 'M',
                'endereco' => 'Rua A, 123',
                'cidade_id' => 1
            ],
            [
                'nome' => 'Ricardo Menezes',
                'cpf' => '326.652.654-00',
                'data_nascimento' => '1980-06-06',
                'sexo' => 'M',
                'endereco' => 'Rua B, 456',
                'cidade_id' => 2
            ],
            [
                'nome' => 'Margaret Hamil',
                'cpf' => '235.326.148-12',
                'data_nascimento' => '1995-06-06',
                'sexo' => 'F',
                'endereco' => 'Rua C, 789',
                'cidade_id' => 3
            ],
            [
                'nome' => 'Joan Clarke',
                'cpf' => '032.324.674-78',
                'data_nascimento' => '2000-06-06',
                'sexo' => 'F',
                'endereco' => 'Rua D, 101',
                'cidade_id' => 4
            ],
        ]);
    }
}
