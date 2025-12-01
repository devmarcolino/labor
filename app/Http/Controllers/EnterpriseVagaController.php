<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vaga;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request)
    {
        // --- 1. TRATAMENTO DE DADOS (ANTES DE VALIDAR) ---
        $input = $request->all();

        // A) Limpeza do Dinheiro
        if ($request->has('valor_vaga')) {
            $limpo = str_replace(['R$', '.', ' '], '', $request->valor_vaga); // Tira R$, ponto e espaço
            $input['valor_vaga'] = str_replace(',', '.', $limpo); // Troca vírgula por ponto
        }

        // B) Conversão da Data (BR -> Banco)
        if ($request->has('dataVaga')) {
            try {
                // Tenta converter d/m/Y para Y-m-d
                $data = \Carbon\Carbon::createFromFormat('d/m/Y', $request->dataVaga);
                $input['dataVaga'] = $data->format('Y-m-d');
            } catch (\Exception $e) {
                // Se falhar, mantém o original para a validação acusar erro
            }
        }

        // C) Junção do Horário
        if ($request->filled('hora_inicio') && $request->filled('hora_fim')) {
            $input['horario'] = $request->hora_inicio . ' - ' . $request->hora_fim;
        }

        // Substitui os dados da request pelos dados tratados
        $request->replace($input);

        // --- 2. VALIDAÇÃO ---
        $validated = $request->validate([
            'tipoVaga'   => 'required|string|max:100',
            'funcVaga'   => 'required|exists:habilidades_tb,id', // Valida se a habilidade existe
            'descVaga'   => 'required|string',
            'valor_vaga' => 'required|numeric|min:0',
            'dataVaga'   => 'required|date|after_or_equal:today', // Bloqueia passado
            'horario'    => 'required|string|max:50',
            'imgVaga'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // --- 3. SALVAR NO BANCO ---
        $vagaData = [
            'idEmpresa'  => auth()->guard('empresa')->id(),
            'tipoVaga'   => $validated['tipoVaga'],
            'funcVaga'   => $validated['funcVaga'],
            'descVaga'   => $validated['descVaga'],
            'valor_vaga' => $validated['valor_vaga'],
            'dataVaga'   => $validated['dataVaga'],
            'horario'    => $validated['horario'],
            'status'     => 1, // Vaga Ativa
        ];

        // Upload da Imagem (Se tiver)
        if ($request->hasFile('imgVaga')) {
            $vagaData['imgVaga'] = $request->file('imgVaga')->store('vagas_img', 'public');
        }

        Vaga::create($vagaData);

        return redirect()
            ->route('enterprises.dashboard')
            ->with('sucess', 'Vaga criada com sucesso!');
    }

    public function concluir($id)
{
    $vaga = Vaga::where('id', $id)
        ->where('idEmpresa', auth()->guard('empresa')->id())
        ->first();

    if (!$vaga) {
        return back()->with('error', 'Vaga não encontrada.');
    }

    // REGRA: Só pode concluir se tiver candidatos
    
    if ($vaga->candidaturas()->count() == 0) {
        // Aqui usamos o 'warning' para o Toast amarelo
        return back()->with('danger', 'Você precisa ter pelo menos 1 candidato para concluir a vaga.');
    }
        

    // Atualiza status para 0 (Concluída)
    $vaga->status = 0;
    $vaga->save();

    return back()->with('success', 'Vaga concluída com sucesso! Ela não aparecerá mais para novos candidatos.');
}
    public function destroy($id)
    {
        // 1. Busca a vaga garantindo que pertence à empresa logada
        $vaga = Vaga::where('id', $id)
            ->where('idEmpresa', Auth::guard('empresa')->id()) 
            ->first();

        if ($vaga) {
            // (Opcional) Apagar a imagem da vaga se existir
            if ($vaga->imgVaga) {
                Storage::disk('public')->delete($vaga->imgVaga);
            }

            // Deleta a vaga
            $vaga->delete();
            
            // Retorna com mensagem de sucesso (para o Toast)
            return back()->with('success', 'Vaga removida com sucesso!');
        }

        // Retorna com mensagem de erro se não achar ou não for dono
        return back()->with('error', 'Erro: Vaga não encontrada ou sem permissão.');
    }
}
