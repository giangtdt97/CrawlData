<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Story extends JsonResource
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
            'id'=>$this->id,
            'name' => $this->name,
            'author'=>$this->author,
            'total chapter'=>$this->chapters()->count(),
            'image'=>$this->thumbnail_img,
            'created_date'=>$this->created_at,
        ];
    }
}
