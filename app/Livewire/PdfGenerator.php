<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Periodo;
use App\Models\Municipio;

class PdfGenerator extends Component
{
    public $periodoAtual;
    public $municipios;
    public $municipioSelecionado;
    public $mostrarModal = false;

    public function mount()
    {
        $this->carregarDados();
    }

    public function carregarDados()
    {
        $this->periodoAtual = Periodo::where('status', 'publicado')
            ->orderBy('periodo_inicio', 'desc')
            ->first();

        $this->municipios = Municipio::orderBy('nome')->get();
    }

    public function abrirModal()
    {
        $this->mostrarModal = true;
    }

    public function fecharModal()
    {
        $this->mostrarModal = false;
        $this->municipioSelecionado = null;
    }

    public function gerarPdfCompleto()
    {
        if (!$this->periodoAtual) {
            session()->flash('erro', 'Nenhum período publicado encontrado para gerar o PDF.');
            return;
        }

        return redirect()->route('espelho.pdf.completo');
    }

    public function gerarPdfMunicipio()
    {
        if (!$this->municipioSelecionado) {
            session()->flash('erro', 'Selecione um município para gerar o PDF.');
            return;
        }

        if (!$this->periodoAtual) {
            session()->flash('erro', 'Nenhum período publicado encontrado para gerar o PDF.');
            return;
        }

        return redirect()->route('espelho.pdf.municipio', ['municipioId' => $this->municipioSelecionado]);
    }

    public function visualizarPdf()
    {
        if (!$this->periodoAtual) {
            session()->flash('erro', 'Nenhum período publicado encontrado para visualizar o PDF.');
            return;
        }

        return redirect()->route('espelho.pdf.visualizar');
    }

    public function render()
    {
        return view('livewire.componentes.pdf-generator');
    }
}
