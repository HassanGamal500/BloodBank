<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model 
{

    protected $table = 'posts';
    public $timestamps = true;
    protected $fillable = array('title', 'body', 'image', 'category_id');

    public function clients()
    {
        return $this->belongsToMany('App\Model\Post');
    }

    public function favorites()
    {
        return $this->belongsToMany('App\Model\Favorite');
    }

    public function categories()
    {
        return $this->belongsTo('App\Model\Category');
    }

}