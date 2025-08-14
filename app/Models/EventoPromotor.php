<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoPromotor extends Model
{
	use HasFactory;
	
	protected $table = 'evento_promotor';
	
	protected $fillable = [
		'evento_id',
		'promotor_id',
		'tipo',
		'data_inicio_designacao',
		'data_fim_designacao',
		'ordem',
		'observacoes',
	];
	
	protected $casts = [
		'data_inicio_designacao' => 'date',
		'data_fim_designacao' => 'date',
	];
	
	public function evento(): BelongsTo
	{
		return $this->belongsTo(Evento::class);
	}
	
	public function promotor(): BelongsTo
	{
		return $this->belongsTo(Promotor::class);
	}
} 