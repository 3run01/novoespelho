<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Historico;

class PlantaoUrgenciaController extends Controller
{
    public function salvarPlantaoUrgencia($dados)
    {
        if (empty($dados['periodo_inicio']) || empty($dados['periodo_fim']) || empty($dados['promotor_designado_id'])) {
            throw new \Exception('Dados incompletos para salvar o plantão');
        }

        $ultimoPeriodo = DB::table('periodos')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$ultimoPeriodo) {
            throw new \Exception('Nenhum período encontrado. Por favor, cadastre um período primeiro.');
        }

        DB::table('plantao_atendimento')->insert([
            'periodo_inicio' => $dados['periodo_inicio'],
            'periodo_fim' => $dados['periodo_fim'],
            'promotor_designado_id' => $dados['promotor_designado_id'],
            'periodo_id' => $ultimoPeriodo->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return true;
    }

    public function atualizarPlantaoUrgencia(Request $request, $id)
    {
        $request->validate([
            'periodo_fim' => 'required|date|after_or_equal:periodo_inicio',
        ]);

        DB::table('plantao_atendimento')
            ->where('id', $id)
            ->update([
                'periodo_fim' => $request->input('periodo_fim'),
                'updated_at' => now(),
            ]);
    }

    public function excluirPlantaoUrgencia($id)
    {
        DB::table('plantao_atendimento')->where('id', $id)->delete();
    }

    public function listarPlantaoUrgencia()
    {
        return DB::table('plantao_atendimento as pa')
            ->join('promotores as p', 'pa.promotor_designado_id', '=', 'p.id')
            ->select('pa.id as plantao_id', 'pa.periodo_inicio', 'pa.periodo_fim', 'pa.promotor_designado_id', 'p.nome as promotor_designado')
            ->get();
    }
}