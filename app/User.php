<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;

    protected $dates =['bolcked_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','lastname', 'email', 'username', 'password', 'avatar','blocked', 'blocked_at'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

// public function setPasswordAttribute($password)
    // {
    //     $this->attributes['password'] =  Hash::make($password);
    // }

    public function getAvatarAttribute($avatar){
        if (is_null($avatar)) return '#';
        else return $avatar;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
    * @param
    * $this es una instancia del usuario actual
    */
    public function scopePermitidos($query)
    {
        return $query->with('roles');
        //dd(auth()->user()->can('view', $this));

        if (auth()->user()->can('view', $this)){ // busca la política e UserPolicy, pasar instancia
            return $query; // retorna el query builder sin restricciones
        }else{
            return $query->where('id', auth()->id());
        }
    }

    // public function getDeletedAtAttribute($value)
    // {

    //     return "2019-02-26";
    //     //return Carbon::now()->toDateTimeString();

    //     return is_null($value)
    //         ? null
    //         : Carbon::$value->format('Y-m-d');
    // }

    // public function setDeletedAtAttribute($deleted_at)
    // {
    //     $this->attributes['deleted_at'] = $deleted_at
    //         ? Carbon::createFromFormat('Y-m-d', $deleted_at)
    //         : null;
    // }


    // creamos la relación uno a uno, un usario tendrá una o varias fotos
    public function fotos()
    {
        return $this->hasMany(Avatar::class);
    }

    /**
    *   Reescribimos método de CanResetPassword
    */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
