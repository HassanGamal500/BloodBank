<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model 
{

    protected $table = 'blood_types';
    public $timestamps = true;

    public function clients()
    {
        return $this->belongsToMany('App\Model\Client');
    }

}