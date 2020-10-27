<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $table='chapters';
    public function chapters()
    {
        return $this->belongsTo(Story::class,'story_id');
    }
}
