<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'api_token',
        'phone',
        'email',
        'password',
        'referral',
        'pin',
        'current_session_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * Ensure session X3391 
     * 
     * @return null
     */
    public function updateSessionId($sessionId)
    {
        $this->current_session_id = $sessionId;
        $this->save();
    }
    
    /**
     * Initialize transactions relationship.
     * 
     * @return array<string, string>
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referral', 'username');
    }
    
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referral', 'username');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function pins()
    {
        return $this->hasMany(Pin::class);
    }
}
