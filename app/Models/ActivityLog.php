<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'message',
        'context',
        'user_id',
        'user_name',
        'user_email',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'action',
        'model_type',
        'model_id',
        'periodo_id',
        'old_values',
        'new_values',
        'session_id',
    ];

    protected $casts = [
        'context' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    // Não usar updated_at
    public $timestamps = false;

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com período
     */
    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    /**
     * Scope para filtrar por nível
     */
    public function scopeLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope para filtrar por ação
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por modelo
     */
    public function scopeForModel($query, string $modelType, int $modelId = null)
    {
        $query = $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    /**
     * Método estático para criar log facilmente
     */
    public static function createLog(
        string $level,
        string $message,
        array $context = [],
        string $action = null,
        Model $model = null,
        int $periodoId = null
    ): self {
        $user = auth()->user();
        
        return self::create([
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'periodo_id' => $periodoId,
            'session_id' => session()->getId(),
            'old_values' => $context['old_values'] ?? null,
            'new_values' => $context['new_values'] ?? null,
        ]);
    }
} 