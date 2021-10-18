<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dob',
        'sex',
        'country',
        'state',
        'city',
        'address',
        'phone',
        'about',
        'avatar',
        'role_id',
        'permissions',
        'instagram',
        'facebook',
        'twitter',
        'linkedin',
        'github',
        'youtube'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'dob' => $this->dob,
            'avatar' => $this->avatar,
            'permissions' =>$this->permissions ? $this->getPermissionsNames(array_map('intval', explode(',', $this->permissions))) : array_column(json_decode(json_encode($this->role->permissions), true), 'name')
        ];
    }

    public function providers(){
        return $this->hasMany(Provider::class,'user_id','id');
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function getPermissionsNames($idsArray){
        return Permission::whereIn('id',$idsArray)->get()->pluck('name');
    }
}
