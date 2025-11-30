<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vaga;

class EnterpriseVagaController extends Controller
{
    // Busca a última vaga da empresa logada para o dashboard
    public static function ultimaVaga($empresaId)
    {
        return Vaga::where('idEmpresa', $empresaId)->latest('created_at')->first();
    }

    // Lista as vagas da empresa logada
    public function list()
    {
        $empresaId = Auth::guard('empresa')->id();
        $vagas = Vaga::where('idEmpresa', $empresaId)->latest('created_at')->get();
        // Adiciona visualizações reais em cada vaga
        foreach ($vagas as $vaga) {
            $vaga->visualizacoes = $vaga->visualizacoesCount();
        }
        $skills = \App\Models\Skill::all();
        return view('enterprises.vagas-list', compact('vagas', 'skills'));
    }

    // Exibe o formulário de criação de vaga
    public function create()
    {
        $skills = \App\Models\Skill::all();
        return view('enterprises.vagas-create', compact('skills'));
    }

    // Salva a vaga no banco
    public function store(Request $request)
    {
        $valorLimpo = str_replace(['R$', '.', ' '], '', $request->valor_vaga);
        $valorLimpo = str_replace(',', '.', $valorLimpo);
        
        // Injeta o valor limpo de volta na request para validar
        $request->merge(['valor_vaga' => $valorLimpo]);
        
        $request->validate([
            'tipoVaga' => 'required|string|max:100',
            'valor_vaga' => 'required|numeric',
            'dataVaga' => 'required|date',
            'descVaga' => 'required|string|max:255',
            'horario' => 'required|string|max:50',
            'funcVaga' => 'required|integer|exists:habilidades_tb,id',
            'imgVaga' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $vagaData = $request->only([
            'tipoVaga',
            'valor_vaga',
            'dataVaga',
            'descVaga',
            'horario',
            'imgVaga',
            'funcVaga'
        ]);

        $vagaData['idEmpresa'] = Auth::guard('empresa')->id();

        if ($request->hasFile('imgVaga')) {
            $vagaData['imgVaga'] = $request->file('imgVaga')->store('vagas_img', 'public');
        }

        Vaga::create($vagaData);

        return redirect()
            ->route('enterprises.dashboard')
            ->with('sucess', 'Vaga criada com sucesso!');
    }

    // Deleta uma vaga
    public function destroy($id)
    {
        $empresaId = Auth::guard('empresa')->id();
        $vaga = Vaga::where('id', $id)->where('idEmpresa', $empresaId)->firstOrFail();
        $vaga->delete();
        return redirect()->route('enterprises.vagas.list')->with('success', 'Vaga deletada com sucesso!');
    }
}
