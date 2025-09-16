<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Evento;
use App\Models\Promotoria;
use App\Models\Promotor;
use App\Models\EventoPromotor;
use App\Models\Portaria;
use App\Models\PortariaPessoa;
use Illuminate\Support\Str;


class EDiario extends Component
{
    public $mostrarModal = false;
    public $eventoId = null;
    public $evento = null;
    
    // Propriedades do formulário
    public $tipoPortaria = '';
    public $assunto = '';
    public $mes = '';
    public $anoPortaria = '';
    public $portariaVinculada = '';
    public $processo = '';
    public $dataExpedicao = '';
    public $descricao = '';


    //eventos e promotoria
    public $titulo = '';
    public $tipo = '';
    public $periodo_inicio = '';
    public $periodo_fim = '';
    public $promotoria_id = '';
    public $promotoresDesignacoes = '';

    private $templates_assuntos = [
        'coordenacao_coletiva' => 'RESOLVE:
                    HOMOLOGAR a designação dos Promotores de Justiça do Ministério Público do Estado do
                    Amapá, para, sem prejuízo das atribuições, responderem pelas Coordenadorias das Promotorias de
                    Justiça, conforme abaixo',
        'coordenacao' => 'HOMOLOGAR a designação da [NOME], Promotora de Justiça
Substituta, matrícula n. [MATRICULA], para, sem prejuízo das atribuições, responder pela Coordenadoria das
Promotorias de Justiça Cíveis e das Famílias da Comarca de [COMARCA], no período de 11 a
[DATA_INICIO] a [DATA_FIM].',
        'alterar_escala_de_plantao' => 'ESOLVE:
ALTERAR, por permuta, a Escala de Plantão dos Promotores de Justiça do Ministério Público do
Estado do Amapá, com atribuições na Comarca de Macapá, referente a Atendimentos em Caráter de
Urgência, estabelecida pela Portaria n. 1413/2025 - GAB-PGJ/MP-AP, conforme abaixo',
        'plantao_de_promotorias' => 'RESOLVE:
HOMOLOGAR: a designação dos Promotores de Justiça do Ministério Público do Estado do
Amapá, para atuarem no Plantão Judiciário da Comarca de Macapá, referente às audiências de custódia e
atendimentos em caráter de urgência, nos dias úteis das 14h30 às 22h e aos sábados, domingos, feriados
e nos dias que não houver expediente, das 08h à 22h, conforme abaixo',
        'suspensao_de_ferias' => 'RESOLVE:
SUSPENDER, a pedido da Dra. [NOME], Promotora de Justiça,
matrícula n. [MATRICULA], as férias referentes ao 1º período aquisitivo de 2025, concedidas pela Portaria n.
1528/2025-GAB/PGJ, no período de 1º a 20/9/2025, para usufruto posterior.',
        'suspensao_de_licenca_premio' =>'RESOLVE:
HOMOLOGAR a suspensão, por absoluta necessidade de serviço, a Licença Prêmio por
Assiduidade da Dra. [NOME], Promotora de Justiça de Entrância Final, matrícula
n. 10084, titular da [PROMOTORIA], concedidas pela Portaria n. 512/2025-
GAB/PGJ, referente ao 5º quinquênio, nos meses de agosto, setembro e outubro de 2025',
        'licenca_premio'=>'RESOLVE:
AUTORIZAR à Dra. [PROMOTOR], Promotora de Justiça de entrância final, matrícula
n. [MATRICULA], titular da [PROMOTORIA], a Licença-Prêmio por Assiduidade
referente ao 5º quinquênio, suspensa pela Portaria n. 1415/2024-GAB/PGJ, para usufruto nos meses de
agosto, setembro e outubro de 2025.',
        'licenca_por_luto' => 'RESOLVE:
HOMOLOGAR a Licença por Luto ao Dr. [NOME], Promotor de Justiça de
Entrância Final, matricula n. [MATRICULA], no período de [PERIODO_INICIAL] a [PERIODO_FINAL], nos termos do Art, 134, Inciso VI, da
Lei Complementar Estadual n. 079/2013.',
        'licenca_medica' => 'RESOLVE:
HOMOLOGAR a Licença para Tratamento de Saúde da Dra. [NOME], Promotora de Justiça de Entrância Inicial, matrícula n. [MATRICULA], no dia..., nos termos
do Artigo 134, Inciso I, da Lei Complementar Estadual nº 079/2013.',
        'licenca_familiar'=> 'RESOLVE:
CONCEDER a licença por motivo de doença em pessoa da familia da Dra.[NOME], Promotora de Justiça de Entrância Final, matrícula n. [MATRICULA], no período
de 6 a 15/8//2025, nos termos do Artigo 134, Inciso II, da Lei Complementar Estadual nº 079/2013',
        'justica_eleitoral' => 'RESOLVE:
AUTORIZAR ao Dr. [NOME], Promotor de Justiça de Entrância Final, matrícula n.
[MATRICULA], titular da Promotoria de Justiça do Juizado Especial, Criminal e de Violência Doméstica e
Familiar contra a Mulher de Santana, o gozo das férias remanescentes do 1º período aquisitivo de 2025,
para usufruto de 1º a 10/9/2025.',
        'gozo_de_ferias' => 'RESOLVE:
AUTORIZAR ao Dr. [NOME], Promotor de Justiça de Entrância Final, matrícula n.
[MATRICULA], titular da Promotoria de Justiça do Juizado Especial, Criminal e de Violência Doméstica e
Familiar contra a Mulher de Santana, o gozo das férias remanescentes do 1º período aquisitivo de 2025,
para usufruto de 1º a 10/9/2025.',
        'folgas_de_plantao' => 'RESOLVE:
AUTORIZAR ao Dr. [NOME], Promotor de Justiça de Entrância Final,
matrícula n. [MATRICULA], a conversão de plantão exercido aos sábados, domingos, feriados e nas audiências
de custódia/atendimentos de urgência, em folga nos dias 21 a 24/10/2025 e 27 a 31/11/2025, conforme
Certidão da SEC/PGJ, de 27/8/2025.',
        'ferias_regulamentares' => 'RESOLVE:
CONCENDER à Dra. [NOME[, Promotora de Justiça Substituta, matrícula nº
[MATRICULA], férias regulamentares referentes ao 1º período aquisitivo de 2025, a serem usufruídas no
período de 29/9 a 18/10/2025, nos termos dos artigos 131 e 133, §4º, da Lei Estadual Complementar nº
79/2013',
        'designacao_coletivo' => 'RESOLVE:
DESIGNAR a Dra. [NOME], Procuradora de Justiça e
Corregedora-Geral do Ministério Público do Estado do Amapá, bem como o Promotor de Justiça, Dr. [NOME],
, para se deslocarem até o Município de [MUNICIPIO] no dia [DATA]
(ida e volta), em razão do calendário da Correição Ordinária 2025.',
        'cursos_congressos_eventos' => 'RESOLVE:
DESIGNAR os membros do Ministério Público do Estado do Amapá abaixo relacionados, a
participarem do I Itinerário do Ministério Público Resolutivo - municípios da 5ª Região (Oiapoque e
Calçoene), a ocorrer nos Municípios de Oiapoque/AP e Calçoene/AP, no período de 25 a 29 de agosto
de 2025, com ônus para a Instituição.
MIGUEL ANGEL MONTIEL FERREIRA - Promotor de Justiça e Coordenador-Geral dos Centros
de Apoio Operacional;
WUEBER DUARTE PENAFORT - Promotor de Justiça, Coordenador do Centro e Apoio
Operacional da Saúde e Ouvidor Substituto do MP-AP.',
        'cumulacao' => 'RESOLVE:
DESIGNAR a Dra. [NOME], Promotora de Justiça Substituta, matrícula n.
    [MATRICULA], para, sem prejuízo de suas atribuições, responder pela 1ª Promotoria de Justiça Cível e das
Famílias da Comarca de Santana/AP, nos dias 25 a 29/8/2025.',
        'cumulacao_coletivo' => 'RESOLVE:
HOMOLOGAR a designação dos Promotores de Justiça desta Instituição, para, sem prejuízo das
atribuições, atuarem nas Promotorias de Justiça, conforme abaixo',
    ];

    private array $assuntoSlugToId = [
        'cursos_congressos_eventos_coletivo' => 181,
        'cumulacao_coletivo' => 184,
        'alterar_escala_de_plantao' => 3,
        'coordenacao' => 18,
        'cumulacao' => 20,
        'cursos_congressos_eventos' => 21,
        'designacao' => 23,
        'folgas_de_plantao' => 36,
        'ferias_regulamentares' => 38,
        'gozo_de_ferias' => 39,
        'justica_eleitoral' => 52,
        'licenca_familiar' => 55,
        'licenca_medica' => 57,
        'licenca_por_luto' => 59,
        'licenca_recesso' => 60,
        'designacao_coletivo' => 189,
        'coordenacao_coletiva' => 192,
        'plantao_promotorias' => 200,
        'portaria_de_teletrabalho' => 221,
        'suspensao_de_ferias' => 96,
        'suspensao_de_licenca_premio' => 216,
        'suspensao_licenca_premio' => 101,
    ];

    private function getAssuntoIdFromSlug(?string $slug): ?int
    {
        if (!$slug) {
            return null;
        }

        return $this->assuntoSlugToId[$slug] ?? null;
    }



    //o on change tem que ser sempre o nome da funcao e da variavel q eu to usando do model.live
    public function updatedAssunto($value)
    {
         $this->descricao = $this->templates_assuntos[$value] ?? '';
    }



    protected $rules = [
        'tipoPortaria' => 'required|string',
        'assunto' => 'required|string',
        'mes' => 'required|string',
        'anoPortaria' => 'required|integer|min:1900|max:2100',
        'portariaVinculada' => 'nullable|string',
        'processo' => 'nullable|string',
        'dataExpedicao' => 'required|date',
        'descricao' => 'required|string|min:10',
    ];

    protected $listeners = ['abrir-ediario' => 'abrirModal'];

    public function mount()
    {
        $this->resetForm();
    }

 
    public function abrirModal($eventoId)
    {
        
        $this->eventoId = $eventoId;
        if($eventoId){

        } else {
            flash()->session("Evento não encontrado");
        }


        $evento = Evento::with(['designacoes.promotor', 'promotoria'])->find($eventoId);
        
        if (!$evento) {
            session()->flash('erro', 'Evento não encontrado.');
            return;
        }
        
        $this->titulo = $evento->titulo ?? '';
        $this->tipo = $evento->tipo ?? '';
        $this->periodo_inicio = $evento->periodo_inicio ? $evento->periodo_inicio->format('Y-m-d') : '';
        $this->periodo_fim = $evento->periodo_fim ? $evento->periodo_fim->format('Y-m-d') : '';
        $this->promotoria_id = $evento->promotoria_id;
        $this->evento = $evento;


        $this->promotoresDesignacoes = $evento->designacoes->map(function ($designacao) {
            return [
                'uid' => (string) Str::uuid(),
                'promotor_id' => (string) $designacao->promotor_id,
                'tipo' => $designacao->tipo ?? 'titular',
                'data_inicio_designacao' => optional($designacao->data_inicio_designacao)?->format('Y-m-d') ?: '',
                'data_fim_designacao' => optional($designacao->data_fim_designacao)?->format('Y-m-d') ?: '',
                'observacoes' => $designacao->observacoes ?? ''
            ];
        })->toArray();
    
        if (empty($this->promotoresDesignacoes)) {
            $this->promotoresDesignacoes = [[
                'uid' => (string) Str::uuid(),
                'promotor_id' => '',
                'tipo' => 'titular',
                'data_inicio_designacao' => $this->periodo_inicio ?: '',
                'data_fim_designacao' => $this->periodo_fim ?: '',
                'observacoes' => ''
            ]];
        }


        $this->mostrarModal = true;
    }







    public function fecharModal()
    {
        $this->mostrarModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->tipoPortaria = '3';
        $this->assunto = '';
        $this->mes = '';
        $this->anoPortaria = date('Y');
        $this->portariaVinculada = '';
        $this->processo = '';
        $this->dataExpedicao = '';
        $this->descricao = '';
        $this->eventoId = null;
        $this->evento = null;
    }

    public function gerarPortaria()
    {
        $this->validate();


        $numeroSequencial  = null;
        
        $matriculaCorregedoria = 20609; // essa matricula vai ser do  usuario que gera a portaria


        $novaPortaria = Portaria::create([
            'fk_tipo_port' => 3,
            'fk_assunto' => $this->getAssuntoIdFromSlug($this->assunto),
            'fk_status' => 1,
            'fk_signatario' => 92,
            'num_seq' => $numeroSequencial,
            'processo' => $this->processo,
            'ano' => $this->anoPortaria ?: date('Y'),
            'data_publicacao' => $this->dataExpedicao ?: date('Y-m-d'),
            'texto_portaria' => $this->descricao,
            'id_categoria' => 1,
            'conteudo_temp' =>  '',
            'mes' => $this->mes ?: date('m'),
            'criado_por' => $matriculaCorregedoria,
        ]);

        


     /*
        foreach ($solicitacoes as $solicitacao) {
            // Calcular data fim baseada nos dias solicitados
            $diasSolicitados = $solicitacao->dias_solicitados ?? 1;
            $dataInicio = $solicitacao->data_inicio;
            $dataFim = $dataInicio->copy()->addDays($diasSolicitados - 1); 
            
            PortariaPessoa::create([
                'fk_portaria' => $novaPortaria->id,
                'fk_pessoa' => $solicitacao->matricula_membro,
                'ordem' => $ordem,
                'periodo_ini' => $dataInicio->format('Y-m-d'),
                'periodo_fim' => $dataFim->format('Y-m-d'),
                'periodo_aquisitivo' => $solicitacao->solicitacaoMembro->periodo ?: '',
                'id_solicitacao_membro' => $solicitacao->solicitacao_membro_id
            ]);
            $ordem++;
        }  
            
        */

        /**
         * 
         * recesso coletivo servidor - verificar depois
         */


        dd([
            'id' => $novaPortaria->getKey(),
            'attributes' => $novaPortaria->getAttributes(),
            'toArray' => $novaPortaria->toArray(),
            'original' => $novaPortaria->getOriginal(),
        ]);

      
        
        session()->flash('mensagem', 'Portaria gerada com sucesso!');
        $this->fecharModal();
    }



    /**
     * 
     * select "p"."id" as "id", "p"."num_seq" as "num_seq", "t"."nome" as "tipo", "a"."nome_assunto" as "assunto", "p"."numero" as "numero", "p"."ano" as "ano", "p"."processo" as "processo", "p"."texto_portaria" as "descricao", "p"."data_publicacao" as "data_publicacao", "p"."fk_status" as "status", "p"."data_criacao" as "data_criacao", "s"."descricao" as "status", "s"."id_status" as "id_status" from "portaria"."portarias" as "p" left join "portaria"."assunto_portaria" as "a" on "a"."id_assunto" = "p"."fk_assunto" left join "portaria"."tipo_portaria" as "t" on "t"."id_tipo_portaria" = "p"."fk_tipo_port" left join "portaria"."status_portaria" as "s" on "s"."id_status" = "p"."fk_status" where "p"."fk_status" in (2, 3) order by "p"."id" desc
     */


    public function render()
    {
        return view('livewire.e-diario.e-diario');
    }
}
