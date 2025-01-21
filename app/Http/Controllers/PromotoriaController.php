<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Promotoria;

class PromotoriaController extends Controller
{
    public function getPromotorias()
    {
        return DB::table('municipios AS m')
            ->select([
                'm.nome AS municipio',
                'gp.nome AS grupo_promotoria',
                'p.nome AS promotoria',
                'p.id AS promotoria_id',
                'pr.nome AS promotor',
                'pr.id AS promotor_id',
                'e.id AS evento_id',
                'e.titulo AS evento',
                'e.tipo AS tipo_evento',
                'e.periodo_inicio',
                'e.periodo_fim',
                'e.is_urgente'
            ])
            ->join('grupo_promotorias AS gp', 'gp.municipios_id', '=', 'm.id')
            ->join('promotorias AS p', 'p.grupo_promotoria_id', '=', 'gp.id')
            ->join('promotores AS pr', 'p.promotor_id', '=', 'pr.id')
            ->leftJoin('eventos AS e', 'e.promotoria_id', '=', 'p.id')
            ->orderBy('m.nome')
            ->orderBy('p.nome')
            ->orderBy('e.periodo_inicio')
            ->get();
    }

    public function getPromotoriasByPeriodo($periodoId)
    {
        return DB::table('municipios AS m')
            ->select([
                'm.nome AS municipio',
                'gp.nome AS grupo_promotoria',
                'p.nome AS promotoria',
                'p.id AS promotoria_id',
                'pr.nome AS promotor',
                'pr.id AS promotor_id',
                'e.id AS evento_id',
                'e.titulo AS evento',
                'e.tipo AS tipo_evento',
                'e.periodo_inicio',
                'e.periodo_fim',
                'e.is_urgente'
            ])
            ->join('grupo_promotorias AS gp', 'gp.municipios_id', '=', 'm.id')
            ->join('promotorias AS p', 'p.grupo_promotoria_id', '=', 'gp.id')
            ->join('promotores AS pr', 'p.promotor_id', '=', 'pr.id')
            ->leftJoin('eventos AS e', function($join) use ($periodoId) {
                $join->on('e.promotoria_id', '=', 'p.id')
                     ->where('e.periodo_id', '=', $periodoId);
            })
            ->orderBy('m.nome')
            ->orderBy('p.nome')
            ->orderBy('e.periodo_inicio')
            ->get();
    }
}