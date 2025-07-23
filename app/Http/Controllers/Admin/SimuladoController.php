<?php

namespace App\Http\Controllers\Admin;



use App\Http\Responses\ApiResponse;use App\Repositories\SimuladoRepository;use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Simulado;
use App\Models\SimuladoQuestao;
use App\Models\SimuladoTentativa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SimuladoController extends BaseAdminController
{
    /**
     * Exibe a lista de simulados.
     */
        public function index(Request $request)
    {
        $items = $this->baseIndex(Simulado::class, $request, ['titulo', 'descricao']);
        $stats = $this->generateStats(Simulado::class);
        
        return $this->adminView('simulados.index', compact('items', 'stats'));
    }

    /**
     * Exibe o formulário para criar um novo simulado.
     */
    public function create(): View
    {
        return view('admin.simulados.create');
    }

    /**
     * Armazena um novo simulado.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categoria' => 'required|string|max:255',
            'nivel' => 'required|in:basic,intermediate,advanced',
            'duracao' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'pontos_recompensa' => 'required|integer|min:0',
            'status' => 'required|in:draft,active,inactive',
            'disponivel_em' => 'nullable|date',
            'expiracao_em' => 'nullable|date|after_or_equal:disponivel_em',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Criar o simulado
        $simulado = Simulado::create([
            'titulo' => $request->title,
            'descricao' => $request->description,
            'categoria' => $request->categoria,
            'nivel' => $request->nivel,
            'duracao' => $request->duracao,
            'questoes_count' => 0, // Será atualizado quando questões forem adicionadas
            'passing_score' => $request->passing_score,
            'pontos_recompensa' => $request->pontos_recompensa,
            'status' => $request->status ?? 'active',
            'disponivel_em' => $request->disponivel_em,
            'expiracao_em' => $request->expiracao_em,
        ]);
        
        return redirect()->route('admin.simulados.show', $simulado)
            ->with('success', 'Simulado criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um simulado.
     */
    public function show(Simulado $simulado): View
    {
        // Carregar questões
        $simulado->load('questoes');
        
        // Estatísticas
        $totalTentativas = SimuladoTentativa::where('simulado_id', $simulado->id)->count();
        $tentativasConcluidas = SimuladoTentativa::where('simulado_id', $simulado->id)
            ->where('status', 'completed')
            ->count();
        $mediaNotas = SimuladoTentativa::where('simulado_id', $simulado->id)
            ->where('status', 'completed')
            ->avg('score') ?? 0;
        $aprovados = SimuladoTentativa::where('simulado_id', $simulado->id)
            ->where('status', 'completed')
            ->where('passed', true)
            ->count();
        
        $taxaAprovacao = $tentativasConcluidas > 0 ? ($aprovados / $tentativasConcluidas) * 100 : 0;
        
        // Usuários atribuídos
        $usuariosAtribuidos = $simulado->users()->get();
        
        return view('admin.simulados.show', compact(
            'simulado', 
            'totalTentativas', 
            'tentativasConcluidas', 
            'mediaNotas', 
            'taxaAprovacao',
            'usuariosAtribuidos'
        ));
    }

    /**
     * Exibe o formulário para editar um simulado.
     */
    public function edit(Simulado $simulado): View
    {
        return view('admin.simulados.edit', compact('simulado'));
    }

    /**
     * Atualiza um simulado.
     */
    public function update(Request $request, Simulado $simulado)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categoria' => 'required|string|max:255',
            'nivel' => 'required|in:basic,intermediate,advanced',
            'duracao' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'pontos_recompensa' => 'required|integer|min:0',
            'status' => 'required|in:draft,active,inactive',
            'disponivel_em' => 'nullable|date',
            'expiracao_em' => 'nullable|date|after_or_equal:disponivel_em',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Atualizar o simulado
        $simulado->update([
            'titulo' => $request->title,
            'descricao' => $request->description,
            'categoria' => $request->categoria,
            'nivel' => $request->nivel,
            'duracao' => $request->duracao,
            'passing_score' => $request->passing_score,
            'pontos_recompensa' => $request->pontos_recompensa,
            'status' => $request->status,
            'disponivel_em' => $request->disponivel_em,
            'expiracao_em' => $request->expiracao_em,
        ]);
        
        return redirect()->route('admin.simulados.show', $simulado)
            ->with('success', 'Simulado atualizado com sucesso!');
    }

    /**
     * Remove um simulado.
     */
    public function destroy(Simulado $simulado)
    {
        // Verificar se há tentativas para este simulado
        $hasTentativas = $this->simuladoRepository->hasAttempts(simulado->id);
        
        if ($hasTentativas) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir este simulado pois existem tentativas registradas.');
        }
        
        // Remover questões do simulado
        $simulado->questoes()->delete();
        
        // Remover atribuições de usuários
        $simulado->users()->detach();
        
        // Excluir o simulado
        $simulado->delete();
        
        return redirect()->route('admin.simulados.index')
            ->with('success', 'Simulado excluído com sucesso!');
    }
    
    /**
     * Alterna o status de ativação do simulado.
     */
    public function toggleActive(Simulado $simulado)
    {
        $newStatus = $simulado->status === 'active' ? 'inactive' : 'active';
        $simulado->status = $newStatus;
        $simulado->save();
        
        $statusText = $newStatus === 'active' ? 'ativado' : 'desativado';
        
        return redirect()->back()
            ->with('success', "Simulado {$statusText} com sucesso!");
    }
    
    /**
     * Exibe a página de gerenciamento de questões do simulado.
     */
    public function questoes(Simulado $simulado): View
    {
        $simulado->load('questoes');
        
        return view('admin.simulados.questoes.index', compact('simulado'));
    }
    
    /**
     * Exibe o formulário para adicionar uma questão ao simulado.
     */
    public function createQuestao(Simulado $simulado): View
    {
        return view('admin.simulados.questoes.create', compact('simulado'));
    }
    
    /**
     * Armazena uma nova questão para o simulado.
     */
    public function storeQuestao(Request $request, Simulado $simulado)
    {
        $validator = Validator::make($request->all(), [
            'enunciado' => 'required|string',
            'tipo' => 'required|string|in:multipla_escolha,verdadeiro_falso,dissertativa',
            'pontos' => 'required|integer|min:1',
            'opcoes' => 'required_if:tipo,multipla_escolha|array|min:2',
            'opcoes.*' => 'required_if:tipo,multipla_escolha|string',
            'resposta_correta' => 'required_if:tipo,multipla_escolha,verdadeiro_falso|string',
            'explicacao' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Criar a questão
        $questao = new SimuladoQuestao([
            'simulado_id' => $simulado->id,
            'enunciado' => $request->enunciado,
            'tipo' => $request->tipo,
            'pontos' => $request->pontos,
            'opcoes' => $request->opcoes ?? [],
            'resposta_correta' => $request->resposta_correta,
            'explicacao' => $request->explicacao,
            'ordem' => $simulado->questoes()->count() + 1,
        ]);
        
        $simulado->questoes()->save($questao);
        
        return redirect()->route('admin.simulados.questoes', $simulado)
            ->with('success', 'Questão adicionada com sucesso!');
    }
    
    /**
     * Exibe o formulário para editar uma questão do simulado.
     */
    public function editQuestao(Simulado $simulado, SimuladoQuestao $questao): View
    {
        // Verificar se a questão pertence ao simulado
        if ($questao->simulado_id !== $simulado->id) {
            abort(404);
        }
        
        return view('admin.simulados.questoes.edit', compact('simulado', 'questao'));
    }
    
    /**
     * Atualiza uma questão do simulado.
     */
    public function updateQuestao(Request $request, Simulado $simulado, SimuladoQuestao $questao)
    {
        // Verificar se a questão pertence ao simulado
        if ($questao->simulado_id !== $simulado->id) {
            abort(404);
        }
        
        $validator = Validator::make($request->all(), [
            'enunciado' => 'required|string',
            'tipo' => 'required|string|in:multipla_escolha,verdadeiro_falso,dissertativa',
            'pontos' => 'required|integer|min:1',
            'opcoes' => 'required_if:tipo,multipla_escolha|array|min:2',
            'opcoes.*' => 'required_if:tipo,multipla_escolha|string',
            'resposta_correta' => 'required_if:tipo,multipla_escolha,verdadeiro_falso|string',
            'explicacao' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Atualizar a questão
        $questao->update([
            'enunciado' => $request->enunciado,
            'tipo' => $request->tipo,
            'pontos' => $request->pontos,
            'opcoes' => $request->opcoes ?? $questao->opcoes,
            'resposta_correta' => $request->resposta_correta,
            'explicacao' => $request->explicacao,
        ]);
        
        return redirect()->route('admin.simulados.questoes', $simulado)
            ->with('success', 'Questão atualizada com sucesso!');
    }
    
    /**
     * Remove uma questão do simulado.
     */
    public function destroyQuestao(Simulado $simulado, SimuladoQuestao $questao)
    {
        // Verificar se a questão pertence ao simulado
        if ($questao->simulado_id !== $simulado->id) {
            abort(404);
        }
        
        // Verificar se há tentativas para este simulado
        $hasTentativas = $this->simuladoRepository->hasAttempts(simulado->id);
        
        if ($hasTentativas) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir esta questão pois existem tentativas registradas para este simulado.');
        }
        
        // Excluir a questão
        $questao->delete();
        
        // Reordenar as questões restantes
        $simulado->questoes()
            ->orderBy('ordem')
            ->get()
            ->each(function ($q, $index) {
                $q->ordem = $index + 1;
                $q->save();
            });
        
        return redirect()->route('admin.simulados.questoes', $simulado)
            ->with('success', 'Questão removida com sucesso!');
    }
    
    /**
     * Reordena as questões do simulado.
     */
    public function reorderQuestoes(Request $request, Simulado $simulado)
    {
        $validator = Validator::make($request->all(), [
            'questoes' => 'required|array',
            'questoes.*.id' => 'required|exists:simulado_questoes,id',
            'questoes.*.ordem' => 'required|integer|min:1',
        ]);
        
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($request->questoes as $questaoData) {
                SimuladoQuestao::where('id', $questaoData['id'])
                    ->where('simulado_id', $simulado->id)
                    ->update(['ordem' => $questaoData['ordem']]);
            }
            
            DB::commit();
            return $this->successResponse();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao reordenar questões: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Exibe a página de atribuição de simulados a usuários.
     */
    public function atribuicoes(Simulado $simulado): View
    {
        // Usuários já atribuídos
        $usuariosAtribuidos = $simulado->users()->pluck('users.id')->toArray();
        
        // Todos os usuários ativos
        $usuarios = User::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('admin.simulados.atribuicoes', compact('simulado', 'usuarios', 'usuariosAtribuidos'));
    }
    
    /**
     * Atribui o simulado a usuários específicos.
     */
    public function atribuir(Request $request, Simulado $simulado)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Atribuir o simulado aos usuários selecionados
        $simulado->users()->sync($request->user_ids);
        
        return redirect()->route('admin.simulados.show', $simulado)
            ->with('success', 'Simulado atribuído aos usuários selecionados com sucesso!');
    }
    
    /**
     * Exibe os resultados das tentativas de um simulado.
     */
    public function resultados(Simulado $simulado): View
    {
        $tentativas = SimuladoTentativa::with('user')
            ->where('simulado_id', $simulado->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.simulados.resultados', compact('simulado', 'tentativas'));
    }
    
    /**
     * Exibe os detalhes de uma tentativa de simulado.
     */
    public function tentativa(Simulado $simulado, SimuladoTentativa $tentativa): View
    {
        // Verificar se a tentativa pertence ao simulado
        if ($tentativa->simulado_id !== $simulado->id) {
            abort(404);
        }
        
        $tentativa->load(['user', 'respostas.questao']);
        
        return view('admin.simulados.tentativa', compact('simulado', 'tentativa'));
    }
}