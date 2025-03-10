<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $fillable = [
        'user_id',
        'merchant_name',
        'type_business',
    ];

    /**
     * Define relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
