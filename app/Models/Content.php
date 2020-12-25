<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
protected $table='contents';
    public function chapters()
    {
        return $this->hasOne(Chapter::class,'id','id');
    }
}
