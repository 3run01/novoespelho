<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Evento;
use App\Models\Promotoria;
use App\Models\Promotor;
use App\Models\EventoPromotor;

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
        'coordenacao_coletiva' => '',
        'coordenacao' => 'teste agoraaaaaaaaaaaaaaaaaaaaaaaaaaa',
        'alterar_escala_de_plantao' => '',
        'plantao_de_promotorias' => '',
        'suspensao_de_ferias' => '',
        'suspensao_de_licenca_premio' =>'',
        'licenca_premio'=>'',
        'licenca_por_luto' => '',
        'licenca_medica' => '',
        'licenca_familiar'=> '',
        'justica_eleitoral' => '',
        'gozo_de_ferias' => '',
        'folgas_de_plantao' => '',
        'ferias_regulamentares' => '',
        'designacao_coletivo' => '',
        'cursos_congressos_eventos' => '',
        'cumulacao' => '',
        'assuntos_portarias' => '',
        'designacao_coletivo' => '',

    ];

    
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


        $evento = Evento::with('designacoes.promotor')->find($eventoId);
        
        if (!$evento) {
            session()->flash('erro', 'Evento não encontrado.');
            return;
        }
        
        $this->titulo = $evento->titulo ?? '';
        $this->tipo = $evento->tipo ?? '';
        $this->periodo_inicio = $evento->periodo_inicio ? $evento->periodo_inicio->format('Y-m-d') : '';
        $this->periodo_fim = $evento->periodo_fim ? $evento->periodo_fim->format('Y-m-d') : '';
        $this->promotoria_id = $evento->promotoria_id;


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
        $this->tipoPortaria = '';
        $this->assunto = '';
        $this->mes = '';
        $this->anoPortaria = '';
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

        // Aqui você pode implementar a lógica de geração da portaria
        // Por exemplo, gerar PDF, enviar email, etc.
        
        session()->flash('mensagem', 'Portaria gerada com sucesso!');
        $this->fecharModal();
    }

    public function render()
    {
        return view('livewire.e-diario.e-diario');
    }
}
