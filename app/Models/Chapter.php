<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $table='chapters';
    public function stories()
    {
        return $this->belongsTo(Story::class,'story_id','story_id');
    }
    public function contents()
    {
        return $this->hasOne(Content::class,'chapter_id');
    }

}
