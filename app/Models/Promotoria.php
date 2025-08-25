<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotoria extends Model
{
    use HasFactory;

    protected $table = 'promotorias';
    
    protected $fillable = [
        'nome',
        'promotor_id',
        'grupo_promotoria_id',
        'competencia',
        'titularidade_promotor_data_inicio',
        'titularidade_promotor_data_final',
        'vacancia_data_inicio'
    ];

    public function grupoPromotoria(): BelongsTo
    {
        return $this->belongsTo(GrupoPromotoria::class, 'grupo_promotoria_id');
    }

    public function promotorTitular(): BelongsTo
    {
        return $this->belongsTo(Promotor::class, 'promotor_id');
    }

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class, 'promotoria_id');
    }
}