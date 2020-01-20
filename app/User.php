<?php

namespace App;

use Auth;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = Auth::user()->name;
            $model->updated_by = Auth::user()->name;
        });
        static::updating(function($model)
        {
            $model->updated_by = Auth::user()->name;
        });
    }

    public function getUserPermissions()
    {
        $access = DB::table('users')
            ->join('user_roles','user_roles.user_id','=','users.id')
            ->join('roles','roles.id','=','user_roles.role_id')
            ->join('role_permissions','role_permissions.role_id','=','user_roles.role_id')
            ->join('permissions','permissions.id','=','role_permissions.permission_id')
            ->where('users.id',Auth::user()->id)
            ->select('users.name','roles.id as role_id','roles.name as role_name','permissions.id as permission_id','permissions.name as permission_name')
            ->get();

        return $access;
    }
}
