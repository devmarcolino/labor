<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VisualizacaoVagaController extends Controller
{
    public function registrar(Request $request)
    {
        $vagaId = $request->input('vaga_id');
        $userId = Auth::id();
        if ($vagaId && $userId) {
            DB::table('visualizacao_vaga')->updateOrInsert(
                [
                    'idUser' => $userId,
                    'idVaga' => $vagaId,
                ],
                [
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        return response()->json(['success' => true]);
    }
}
