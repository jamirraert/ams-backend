<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasApiTokens;

    protected $fillable = ['role'];
}
