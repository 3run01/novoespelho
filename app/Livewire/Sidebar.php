<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{


        public string $usuario = '';


        public function mount()
        {
            $user = Auth::user();
            $this->usuario = $user ? $user->name : '';

        }


        public function logout()
        {
            Auth::logout();
            session()->flush();
            return redirect('/login');
        }





    public function render()
    {
        return view('livewire.sidebar');
    }
}
