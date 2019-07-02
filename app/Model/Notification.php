<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model 
{

    protected $table = 'notifications';
    public $timestamps = true;
    protected $fillable = array('title', 'content', 'order_id');

    public function orders()
    {
        return $this->belongsTo('App\Model\Order');
    }

    public function clients()
    {
        return $this->belongsToMany('App\Model\Client');
    }

}