<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenAuthentication extends Model
{
    protected $table = 'token_authentication';

    protected $fillable = ['user_id', 'user_token', 'type', 'service', 'otp_code'];

    public $timestamps = true;
}