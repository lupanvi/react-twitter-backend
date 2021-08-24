<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;
use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\QueryDsl;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, Searchable, QueryDsl;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar_path',
        'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'phone',
        'email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified' => 'boolean'
    ];

    /**
     * User tweets
     *
     * @return Collection
     */
    public function tweets()
    {
        return $this->hasMany(Tweet::class)->latest('updated_at');
    }
    
    /**
     * Override sendEmailVerificationNotification to allow queue notification
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {                
        $this->notify(new VerifyEmail());
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,            
            'username' => $this->username,
        ];
    }
}
