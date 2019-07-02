<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model 
{

    protected $table = 'settings';
    public $timestamps = true;
    protected $fillable = array('phone', 'email', 'about_app', 'facebook_link', 'twitter_link', 'youtube_link', 'instagram_link', 'whatsapp_link', 'google_link');

}