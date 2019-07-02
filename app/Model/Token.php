<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'tokens';
    public $timestamps = true;
    protected $fillable = array('client_id', 'token', 'type');

    public function clients()
    {
        return $this->belongsTo('App\Model\Client');
    }

}
