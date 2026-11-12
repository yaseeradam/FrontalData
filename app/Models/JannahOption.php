<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JannahOption extends Model
{
    protected $fillable = ['opt_key', 'opt_value'];
    
    protected $table = "options";
}
