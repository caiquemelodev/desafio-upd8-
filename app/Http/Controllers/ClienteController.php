<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\ClienteService;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    protected $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    public function index(Request $request)
    {
        $query = Cliente::with('cidade');
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%'.$request->nome.'%');
        }
        if ($request->filled('cpf')) {
            $query->where('cpf', $request->cpf);
        }
        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }
        if ($request->filled('cidade_id')) {
            $query->where('cidade_id', $request->cidade_id);
        }
        if ($request->filled('estado')) {
            $query->whereHas('cidade', function($q) use ($request) {
                $q->where('estado', $request->estado);
            });
        }
        if ($request->filled('data_nascimento')) {
            $query->where('data_nascimento', $request->data_nascimento);
        }
        return $query->paginate(10);
    }

    public function store(StoreClienteRequest $request)
    {
        $cliente = $this->clienteService->create($request->validated());
        return response()->json($cliente, 201);
    }

    public function show($id)
    {
        return Cliente::with('cidade')->findOrFail($id);
    }

    public function update(UpdateClienteRequest $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente = $this->clienteService->update($cliente, $request->validated());
        return response()->json($cliente);
    }

    public function destroy($id)
    {
        $this->clienteService->delete($id);
        return response()->noContent();
    }
}
