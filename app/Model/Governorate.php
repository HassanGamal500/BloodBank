<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model 
{

    protected $table = 'governorates';
    public $timestamps = true;
    protected $fillable = array('name');

    public function cities()
    {
        return $this->hasMany('App\Model\City');
    }

    public function clients()
    {
        return $this->belongsToMany('App\Model\Client');
    }

}