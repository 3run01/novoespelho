<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Login;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    public $sidebarCollapsed = false;
    public $sidebarOpen = false;

    public function toggleSidebar()
    {
        $this->sidebarCollapsed = !$this->sidebarCollapsed;
        $this->dispatch('sidebar-collapsed', collapsed: $this->sidebarCollapsed);
    }

    public function toggleMobileSidebar()
    {
        $this->sidebarOpen = !$this->sidebarOpen;
    }

    public function preventSidebarCollapse()
    {
        $this->sidebarCollapsed = false;
        $this->dispatch('sidebar-state-reset');
    }

    public function resetSidebarState()
    {
        $this->sidebarCollapsed = false;
    }


    public function testeSimples()
    {
        dd('Método testeSimples funcionando!');
    }

    public function render()
    {
        return view('livewire.componentes.sidebar');
    }
}
