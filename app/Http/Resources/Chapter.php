<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class Chapter extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'chapter_id'=>$this->id,
            'story_id'=>$this->story_id,
            'title' => $this->title,
            'created_at'=>$this->created_at,
            'contents'=>Crypt::encrypt($this->content),
        ];
    }
}
