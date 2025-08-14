Dependências entre migrations (ordem lógica e FKs)
Tabelas base do framework:
users → base para historico.
sessions, personal_access_tokens → infra.
Domínio:
municipios
grupo_promotorias → FK municipios_id → municipios.
promotores
promotorias → FK promotor_id → promotores, FK grupo_promotoria_id → grupo_promotorias.
periodos
eventos → FKs: promotoria_id → promotorias, periodo_id → periodos, promotor_titular_id e promotor_designado_id → promotores.
plantao_atendimento → FKs: periodo_id → periodos, promotor_designado_id → promotores.
historico → FK users_id → users.
Trechos que mostram as FKs mais críticas:
Schema::create('eventos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('promotoria_id')->constrained();
    ...
    $table->foreignId('periodo_id')->constrained('periodos');
    ...
    $table->foreignId('promotor_titular_id')->constrained('promotores');
    $table->foreignId('promotor_designado_id')->constrained('promotores');
    ...
});

Schema::create('plantao_atendimento', function (Blueprint $table) {
    $table->id();
    $table->foreignId('periodo_id')->constrained('periodos');
    ...
    $table->foreignId('promotor_designado_id')->references('id')->on('promotores');
    ...
});
Schema::create('grupo_promotorias', function (Blueprint $table) {
    $table->id();
    ...
    $table->unsignedBigInteger('municipios_id'); 
    $table->foreign('municipios_id')->references('id')->on('municipios');
    ...
});

Como os dados são inseridos (fluxo do Espelho)
Pré-edição/Preview:
A página App\Filament\Pages\Espelho mantém arrays temporários: eventosTemporarios, plantoesTemporarios, periodosTemporarios.
O usuário adiciona/edita itens nesses arrays e visualiza no modo “Preview”.
Confirmação/Gravação:
Ao clicar “Salvar Todas as Alterações” roda uma transação que:
Cria os períodos temporários (se houver).
Define o “último período” a ser usado.
Insere todos os eventos temporários na tabela eventos vinculando a periodo_id do último período.
Insere os plantões temporários via PlantaoUrgenciaController::salvarPlantaoUrgencia, que também usa o último período.
Registra tudo em historico.
Trecho que mostra a gravação dos eventos na confirmação:

foreach ($this->eventosTemporarios as $evento) {
    DB::table('eventos')->insert([
        'titulo' => $evento['titulo'],
        'tipo' => $evento['tipo'],
        'periodo_inicio' => $evento['periodo_inicio'],
        'periodo_fim' => $evento['periodo_fim'],
        'promotor_titular_id' => $evento['promotor_titular'],
        'promotor_designado_id' => $evento['promotor_designado'],
        'promotoria_id' => $evento['promotoria_id'],
        'is_urgente' => $evento['is_urgente'] ?? false,
        'periodo_id' => $ultimoPeriodo->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

E a gravação dos plantões (via controller) usando o último período:

DB::table('plantao_atendimento')->insert([
    'periodo_inicio' => $dados['periodo_inicio'],
    'periodo_fim' => $dados['periodo_fim'],
    'promotor_designado_id' => $dados['promotor_designado_id'],
    'periodo_id' => $ultimoPeriodo->id,
    'created_at' => now(),
    'updated_at' => now()
]);

Edição/Exclusão de eventos:
Atualização: EventoController::updateEvento (valida, atualiza, registra no historico).
Exclusão: EventoController::deleteEvento (apaga e registra no historico).
Qual é a “regra” central do Espelho
O Espelho sempre trabalha associado a um “período vigente”.
Por padrão, usa o último Periodo (created_at mais recente).
Em preview, você pode montar novos períodos/eventos/plantões sem gravar; só persistem ao confirmar.
Regras de integridade:
Não há evento sem promotoria, periodo, promotor_titular e promotor_designado (validação e FKs).
Plantões sempre apontam para um periodo válido e um promotor existente.
Toda ação relevante registra auditoria em historico.
O que compõe o Espelho (conceitualmente)
Um período selecionado (o último por padrão, ou um novo em preview).
Eventos do período selecionado (por periodo_id).
Plantões de urgência do período (por periodo_id e intervalo).
Contexto de apresentação:
Município → Grupo de Promotoria → Promotoria → Membro (Promotor)
A listagem junta essas entidades e exibe os eventos por promotor.
“Quantos itens o espelho tem?”
É dinâmico. O Espelho exibe:
Quantos “Eventos” existirem para o periodo corrente.
Quantos “Plantões de urgência” existirem no mesmo periodo.
Para ver os totais atuais, há widgets:
Total de Eventos = Evento::count().
Total de Plantões de Emergência = PlantaoAtendimento::count().
Se quiser o total apenas do período vigente (último):
Eventos: select count(*) from eventos where periodo_id = :ultimoPeriodoId
Plantões: select count(*) from plantao_atendimento where periodo_id = :ultimoPeriodoId
Observações importantes (coisas a ajustar)
periodos (migration) não possui promotor_id, mas o código tenta gravá-lo em alguns pontos e modelos assumem esse campo (ex.: Promotoria::periodos(), Espelho::adicionarPeriodo). Isso pode falhar na gravação ou quebrar relacionamentos.
Na geração de PDF, o join de plantões usa users para recuperar o “promotor designado”, mas o domínio usa promotores. Pode ser um equívoco de tabela no relatório.
Se quiser, eu corrijo esses dois pontos (adição de promotor_id em periodos ou remoção do uso, e ajuste do join no PDF) em edits pequenos e sem alterar o resto.
Histórico: mapeei as FKs e o fluxo de inserção (preview → confirmar com transação), e o que compõe o Espelho por período.
Itens do Espelho: período + eventos do período + plantões do período; quantidade é dinâmica (há contadores gerais e é simples filtrar por período).