<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{

    protected $table = 'clients';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'birth_of_date', 'phone', 'password', 'blood_type_id', 'city_id', 'pin_code');

    public function tokens()
    {
        return $this->hasMany('App\Model\Token');
    }

    public function requests()
    {
        return $this->hasMany('App\Model\Order');
    }

    public function bloodType()
    {
        return $this->belongsTo('App\Model\BloodType');
    }

    public function bloodTypes()
    {
        return $this->belongsToMany('App\Model\BloodType', 'blood_type_client', 'client_id');
    }

    public function posts()
    {
        return $this->belongsToMany('App\Model\Post');
    }

    public function governorates()
    {
        return $this->belongsToMany('App\Model\Governorate', 'client_governorate', 'client_id');
    }

    public function cities()
    {
        return $this->belongsTo('App\Model\City');
    }

    public function notifications()
    {
        return $this->belongsToMany('App\Model\Notification')->withPivot('is_read');
    }

    protected $hidden = [
        'password', 'api_token',
    ];

}