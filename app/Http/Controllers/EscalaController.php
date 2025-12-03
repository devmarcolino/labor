<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

class EscalaController extends Controller
{
    public function criar(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:user_tb,id',
            'empresa_id' => 'required|integer|exists:empresa_tb,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }


        $escala = DB::table('escala_tb')->insertGetId([
            'idUser' => $request->user_id,
            'idEmpresa' => $request->empresa_id,
            'dataCriacao' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Opcional: Broadcast para atualizar schedule
        // Broadcast::event(new NovaEscalaCriada($escala));

        return response()->json(['success' => true, 'escala_id' => $escala]);
    }
}
