<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppelIa extends Model
{
    protected $fillable = ['user_id', 'contexte', 'modele', 'reussi'];

    protected function casts(): array
    {
        return ['reussi' => 'boolean'];
    }
}