<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CandidatoCurtido;

class CandidatoCurtidoController extends Controller
{
        public function store(Request $request)
        {
            $validated = $request->validate([
                'vaga_id' => 'required|integer',
                'user_id' => 'required|integer',
            ]);

            CandidatoCurtido::create([
                'empresa_id' => auth()->user()->empresa_id,
                'user_id' => $validated['user_id'],
                'vaga_id' => $validated['vaga_id'],
            ]);

            return response()->json(['success' => true]);
        }

        public function rejeitar(Request $request)
{
    $validated = $request->validate([
        'vaga_id' => 'required|integer',
        'user_id' => 'required|integer'
    ]);

    \DB::table('candidaturas_tb')
        ->where('vaga_id', $validated['vaga_id'])
        ->where('user_id', $validated['user_id'])
        ->update(['status' => 0]);

    return response()->json(['success' => true]);
}
}
