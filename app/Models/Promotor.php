<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promotor extends Model
{
    use HasFactory;
    
    protected $table = 'promotores'; 
    
    protected $fillable = [
        'nome', 
        'cargo',
        'zona_eleitoral',
        'numero_da_zona_eleitoral',
        'periodo_inicio',
        'periodo_fim',
        'tipo',
        'is_substituto',
        'observacoes',
    ];

    protected $casts = [
        'is_substituto' => 'boolean',
        'zona_eleitoral' => 'boolean',
        'periodo_inicio' => 'date',
        'periodo_fim' => 'date',
    ];

    /**
     * Relacionamento muitos-para-muitos com eventos através da tabela pivot.
     */
    public function eventos(): BelongsToMany
    {
        return $this->belongsToMany(Evento::class, 'evento_promotor')
                    ->withPivot([
                        'tipo',
                        'data_inicio_designacao',
                        'data_fim_designacao',
                        'ordem',
                        'observacoes',
                    ])
                    ->withTimestamps()
                    ->orderBy('ordem');
    }

    /**
     * Relacionamento com períodos através da tabela pivot.
     */
    public function periodos(): BelongsToMany
    {
        return $this->belongsToMany(Periodo::class, 'evento_promotor')
                    ->withPivot([
                        'evento_id',
                        'tipo',
                        'data_inicio_designacao',
                        'data_fim_designacao',
                        'observacoes',
                    ])
                    ->withTimestamps();
    }

    /**
     * Scope para promotores por tipo.
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para promotores substitutos.
     */
    public function scopeSubstituto($query)
    {
        return $query->where('is_substituto', true);
    }

    /**
     * Scope para promotores de zona eleitoral.
     */
    public function scopeZonaEleitoral($query)
    {
        return $query->where('zona_eleitoral', true);
    }
}