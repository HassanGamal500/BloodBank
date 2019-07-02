<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = array('patient_name', 'phone', 'blood_type_id', 'number_of_bags', 'hospital_name', 'latitude', 'longitude', 'governorate_id', 'city_id', 'notice','client_id');

    public function bloodTypes()
    {
        return $this->hasMany('App\Model\BloodType');
    }

    public function city()
    {
        return $this->belongsTo('App\Model\City');
    }

    public function notifications()
    {
        return $this->hasMany('App\Model\Notification');
    }

    public function client()
    {
        return $this->belongsTo('App\Model\Client');
    }

}