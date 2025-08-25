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
    
    /**
     * Obtém o texto legível do status
     */
    public function getStatusTextoAttribute()
    {
        $statusMap = [
            'em_processo_publicacao' => 'Em Processo',
            'publicado' => 'Publicado',
            'arquivado' => 'Arquivado'
        ];
        
        return $statusMap[$this->status] ?? $this->status;
    }
    
    /**
     * Obtém a classe CSS para o status
     */
    public function getStatusClasseAttribute()
    {
        $statusClasses = [
            'em_processo_publicacao' => 'bg-yellow-100 text-yellow-800',
            'publicado' => 'bg-green-100 text-green-800',
            'arquivado' => 'bg-gray-100 text-gray-800'
        ];
        
        return $statusClasses[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}