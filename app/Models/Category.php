<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function stories(){
        return $this->belongsToMany(Story::class,'category_stories');
    }
//    public function stories(){
//        return $this->hasMany(Story::class,'category_id');
//    }
}
