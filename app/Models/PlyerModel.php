<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlyerModel extends Model
{
    protected $table = 'players';

    protected $fillable = [
        'player_id',
        'player_name',
        'role',
        'rank',
        'money',
        'race',
        'last_active',
    ];
}
