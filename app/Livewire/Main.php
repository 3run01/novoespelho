<?php

namespace App\Livewire;

use Livewire\Component;

class Main extends Component
{


    public function teste()
    {
        logger('Método teste foi chamado');
        dd("teste");
    }
    public function render()
    {
        return view('livewire.espelho.main');
    }
}
