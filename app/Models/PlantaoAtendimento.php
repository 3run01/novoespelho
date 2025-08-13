<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantaoAtendimento extends Model
{
    use HasFactory;

    protected $table = 'plantao_atendimento';

    protected $fillable = [
        'periodo_id',
        'municipio_id',
        'nome',
        'observacoes',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fim' => 'date',
    ];

    /**
     * Relacionamento com o período
     */
    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    /**
     * Relacionamento com o município
     */
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    /**
     * Relacionamento com múltiplos promotores através da tabela pivot
     */
    public function promotores()
    {
        return $this->belongsToMany(Promotor::class, 'plantao_promotor', 'plantao_atendimento_id', 'promotor_id')
                    ->withPivot([
                        'data_inicio_designacao',
                        'data_fim_designacao',
                        'ordem',
                        'tipo_designacao',
                        'status'
                    ])
                    ->withTimestamps();
    }

    /**
     * Relacionamento com o espelho
     */
    public function espelho()
    {
        return $this->belongsTo(Espelho::class);
    }

    /**
     * Scope para plantões ativos
     */
    public function scopeAtivo($query)
    {
        return $query->whereHas('promotores', function ($q) {
            $q->where('status', 'ativo')
              ->where('data_inicio_designacao', '<=', now())
              ->where('data_fim_designacao', '>=', now());
        });
    }

    /**
     * Adicionar promotor ao plantão
     */
    public function adicionarPromotor($promotorId, $dataInicio, $dataFim, $ordem = 1, $tipo = 'titular')
    {
        $this->promotores()->attach($promotorId, [
            'data_inicio_designacao' => $dataInicio,
            'data_fim_designacao' => $dataFim,
            'ordem' => $ordem,
            'tipo_designacao' => $tipo,
            'status' => 'ativo'
        ]);
    }

    /**
     * Remover promotor do plantão
     */
    public function removerPromotor($promotorId)
    {
        $this->promotores()->detach($promotorId);
    }
}
