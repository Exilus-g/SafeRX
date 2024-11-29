<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'main_user_id',
        'name',
        'email',
        'password',
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

    public function diagrams()
    {
        return $this->hasMany(Diagram::class);
    }

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function errorTypes()
    {
        return $this->hasMany(ErrorType::class);
    }

    public function factors()
    {
        return $this->hasMany(Factor::class);
    }

    public function staffInvolveds()
    {
        return $this->hasMany(StaffInvolved::class);
    }
    public function nodes()
    {
        return $this->hasMany(Node::class);
    }

    public function errors()
    {
        return $this->hasMany(Error::class);
    }
    public function mainUser()
    {
        return $this->belongsTo(User::class, 'main_user_id');
    }

    public function collaborators()
    {
        return $this->hasMany(User::class, 'main_user_id');
    }
}
