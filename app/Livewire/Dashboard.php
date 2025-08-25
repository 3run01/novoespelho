<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }
}


using System;

class CarroMonitoramento
{
    public static string VerificarAptidao(string modelo, int anoFabricacao, int anoAtual)
    {
        int idadeCarro = anoAtual - anoFabricacao;
        
        if (idadeCarro <= 10)
        {
            return $"{modelo}: Apto";
        }
        else
        {
            return $"{modelo}: Nao apto";
        }
    }

    static void Main()
    {
        string modelo = Console.ReadLine();
        
        int anoFabricacao = int.Parse(Console.ReadLine());
        int anoAtual = int.Parse(Console.ReadLine());

        string resultado = VerificarAptidao(modelo, anoFabricacao, anoAtual);

        Console.WriteLine(resultado);
    }
}
