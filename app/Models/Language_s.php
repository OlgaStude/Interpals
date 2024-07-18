<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language_s extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
    ];
}
