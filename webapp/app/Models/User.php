<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'chatwork_id', 
        'employee_number',
        'authority',
        'birthday',
        'entry_day',
        'gender',
        'country',
        'created_at',
        'updated_at',
        'deleted_at'
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


    public function order()
    {
        return $this->hasOne('\App\Models\Orders');
    }

    public function isOnlyAdministrator()
    {
        $not_auth_employee = User::where('authority', '=', 0)
                                 ->orderBy('id', 'asc')
                                 ->get();
        $all_employee = User::all();

        if (($not_auth_employee->count() + 1) == $all_employee->count()) {
            return True;
        }

        return False;
    }
}
