<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'historico'; 
    protected $fillable = ['users_id', 'detalhes', 'modificado'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}