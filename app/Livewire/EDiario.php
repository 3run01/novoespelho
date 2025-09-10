<?php

namespace App\Livewire;

use Livewire\Component;

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
        $this->evento = \App\Models\Evento::with('promotoria')->find($eventoId);
        $this->mostrarModal = true;
        $this->resetForm();
        
        // Preencher dados do evento se disponível
        if ($this->evento) {
            $this->descricao = $this->evento->titulo ?? '';
            $this->dataExpedicao = now()->format('Y-m-d');
            $this->anoPortaria = now()->year;
            $this->mes = now()->format('F');
        }
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
