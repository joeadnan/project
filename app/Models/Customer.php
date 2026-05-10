<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Override;

class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory;
    /**
     * fillable
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be hidden for arrays
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $date
     * @return void
     */
    public function getCreatedAtAttribute($date)
    {
        $value = Carbon::parse($date)->locale('id');
        return $value->translatedFormat('l, j F Y');
    }
    /**
     * Get the identifier that will be stored
     * @return mixed
     */
    #[Override]
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
      public function getJWTCustomClaims()
    {
        return [];
    }
}
