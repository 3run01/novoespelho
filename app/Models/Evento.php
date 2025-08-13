<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 
        'tipo', 
        'periodo_inicio', 
        'periodo_fim', 
        'is_urgente',
        'promotoria_id',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fim' => 'date',
        'is_urgente' => 'boolean'
    ];

    /**
     * Relacionamento com a promotoria.
     */
    public function promotoria(): BelongsTo
    {
        return $this->belongsTo(Promotoria::class);
    }

    /**
     * Relacionamento com espelhos que contém este evento.
     */
    public function espelhos(): BelongsToMany
    {
        return $this->belongsToMany(Espelho::class, 'espelho_evento')
                    ->withPivot(['ordem', 'observacoes_evento'])
                    ->withTimestamps();
    }

    /**
     * Relacionamento muitos-para-muitos com promotores através da tabela pivot.
     */
    public function promotores(): BelongsToMany
    {
        return $this->belongsToMany(Promotor::class, 'evento_promotor')
                    ->withPivot([
                        'tipo',
                        'data_inicio_designacao',
                        'data_fim_designacao',
                        'ordem',
                        'observacoes'
                    ])
                    ->withTimestamps()
                    ->orderBy('ordem');
    }

    /**
     * Método para adicionar promotor ao evento.
     */
    public function adicionarPromotor($promotorId, $dados = [])
    {
        $dadosPadrao = [
            'tipo' => 'titular',
            'data_inicio_designacao' => $this->periodo_inicio,
            'data_fim_designacao' => $this->periodo_fim,
            'ordem' => $this->promotores()->count() + 1
        ];

        $dados = array_merge($dadosPadrao, $dados);

        $this->promotores()->attach($promotorId, [
            'tipo' => $dados['tipo'],
            'data_inicio_designacao' => $dados['data_inicio_designacao'],
            'data_fim_designacao' => $dados['data_fim_designacao'],
            'ordem' => $dados['ordem'],
            'observacoes' => $dados['observacoes'] ?? null
        ]);
    }

    /**
     * Método para remover promotor do evento.
     */
    public function removerPromotor($promotorId)
    {
        $this->promotores()->detach($promotorId);
    }

    /**
     * Scope para eventos ativos.
     */
    public function scopeAtivo($query)
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Scope para eventos urgentes.
     */
    public function scopeUrgente($query)
    {
        return $query->where('is_urgente', true);
    }

    /**
     * Scope para eventos por período de datas.
     */
    public function scopePorPeriodo($query, $dataInicio, $dataFim = null)
    {
        $query->where('periodo_inicio', '>=', $dataInicio);
        
        if ($dataFim) {
            $query->where('periodo_fim', '<=', $dataFim);
        }
        
        return $query;
    }

    /**
     * Scope para eventos com promotores.
     */
    public function scopeComPromotores($query)
    {
        return $query->whereHas('promotores');
    }

    /**
     * Método para obter o período pai através do espelho.
     */
    public function getPeriodoPai()
    {
        return $this->espelhos()->first()?->periodo;
    }

    /**
     * Accessor para periodo_id - busca do espelho pai
     */
    public function getPeriodoIdAttribute()
    {
        return $this->getPeriodoPai()?->id;
    }

    /**
     * Relacionamento virtual com período através do espelho
     */
    public function periodo()
    {
        return $this->getPeriodoPai();
    }
}