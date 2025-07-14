<?php

namespace App\Http\Controllers;

use App\Models\Representante;
use App\Services\RepresentanteService;
use App\Http\Requests\StoreRepresentanteRequest;
use App\Http\Requests\UpdateRepresentanteRequest;
use Illuminate\Http\Request;

class RepresentanteController extends Controller
{
    protected $representanteService;

    public function __construct(RepresentanteService $representanteService)
    {
        $this->representanteService = $representanteService;
    }
    public function index(Request $request)
    {
        $query = Representante::with('cidade');
        if ($request->filled('cidade_id')) {
            $query->where('cidade_id', $request->cidade_id);
        }
        return $query->paginate(10);
    }

    public function store(StoreRepresentanteRequest $request)
    {
        $representante = $this->representanteService->create($request->validated());
        return response()->json($representante, 201);
    }

    public function show($id)
    {
        return Representante::with('cidade')->findOrFail($id);
    }

    public function update(UpdateRepresentanteRequest $request, $id)
    {
        $representante = Representante::findOrFail($id);
        $representante = $this->representanteService->update($representante, $request->validated());
        return response()->json($representante);
    }

    public function destroy($id)
    {
        $this->representanteService->delete($id);
        return response()->noContent();
    }
}
