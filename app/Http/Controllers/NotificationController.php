<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Vaga;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'vaga_id' => 'required|integer|exists:vagas_tb,id',
            'empresa_id' => 'required|integer|exists:empresa_tb,id',
        ]);

        $worker = Auth::user();
        $vaga = Vaga::findOrFail($request->vaga_id);
        $mensagem = $worker->name . ' curtiu a vaga ' . $vaga->tipoVaga;

        Notification::create([
            'empresa_id' => $request->empresa_id,
            'worker_id' => $worker->id,
            'vaga_id' => $vaga->id,
            'mensagem' => $mensagem,
        ]);

        return response()->json(['success' => true]);
    }
}
