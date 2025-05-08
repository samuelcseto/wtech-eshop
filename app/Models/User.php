<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
    
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    /**
     * Get the user's addresses.
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
    
    /**
     * Get the user's default address.
     */
    public function defaultAddress()
    {
        return $this->hasMany(UserAddress::class)->where('is_default', true)->first();
    }
    
    /**
     * Get the user's address (maintained for backward compatibility).
     * @deprecated Use defaultAddress() or addresses() instead.
     */
    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    /**
     * Get the user's cart.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    
    /**
     * Get the user's active cart.
     */
    public function activeCart()
    {
        return $this->hasOne(Cart::class)->latest();
    }
}
