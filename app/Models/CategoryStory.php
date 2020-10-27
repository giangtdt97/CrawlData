<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryStory extends Model
{
    protected $table='category_stories';
    protected $fillable=['category_id','story_id'];
}
