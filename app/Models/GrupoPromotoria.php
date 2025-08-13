<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoPromotoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'competencia',
        'municipios_id'
    ];

    protected $casts = [
        'competencia' => 'string'
    ];

    public function promotorias()
    {
        return $this->hasMany(Promotoria::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipios_id');
    }

    public function promotores()
    {
        return $this->hasManyThrough(Promotor::class, Promotoria::class);
    }
}
