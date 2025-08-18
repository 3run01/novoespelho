<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'periodo_inicio',
        'periodo_fim',
        'status',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fim' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Quando um período é criado, ele sempre inicia como "em_processo_publicacao"
        static::creating(function ($periodo) {
            $periodo->status = 'em_processo_publicacao';
        });
    }

    /**
     * Publica este período e arquiva o período anteriormente publicado
     */
    public function publicar()
    {
        \DB::transaction(function () {
            static::where('status', 'publicado')->update(['status' => 'arquivado']);
            
            $this->update(['status' => 'publicado']);
        });
    }
}