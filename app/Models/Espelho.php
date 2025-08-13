<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Espelho extends Model
{
    use HasFactory;

    protected $table = 'espelhos';

    protected $fillable = [
        'periodo_id',
        'plantao_atendimento_id',
        'grupo_promotorias_id',
        'municipio_id',
        'nome',
        'observacoes',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Relacionamento com período
     */
    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    /**
     * Relacionamento com plantão de atendimento
     */
    public function plantaoAtendimento(): BelongsTo
    {
        return $this->belongsTo(PlantaoAtendimento::class);
    }

    /**
     * Relacionamento com grupo de promotorias
     */
    public function grupoPromotorias(): BelongsTo
    {
        return $this->belongsTo(GrupoPromotoria::class);
    }

    /**
     * Relacionamento com município
     */
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    /**
     * Relacionamento muitos-para-muitos com eventos
     */
    public function eventos(): BelongsToMany
    {
        return $this->belongsToMany(Evento::class, 'espelho_evento')
                    ->withPivot(['ordem', 'observacoes_evento'])
                    ->withTimestamps()
                    ->orderBy('ordem');
    }

    /**
     * Scope para espelhos ativos
     */
    public function scopeAtivo($query)
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Scope para espelhos por período
     */
    public function scopePorPeriodo($query, $periodoId)
    {
        return $query->where('periodo_id', $periodoId);
    }

    /**
     * Scope para espelhos por município
     */
    public function scopePorMunicipio($query, $municipioId)
    {
        return $query->where('municipio_id', $municipioId);
    }
}
