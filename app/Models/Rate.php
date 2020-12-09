<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    public function stories()
    {
        return $this->hasOne(Story::class,'story_id');
    }
}
