<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $table = 'stories';

//    public function categories()
//    {
//        return $this->belongsToMany(Category::class, 'category_stories');
//    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'story_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_stories');
    }
    public function rates()
    {
        return $this->hasOne(Rate::class,'story_id');
    }
}
