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
            'categoryId'=>$this->category_id,
            'name' => $this->name,
            'url' => $this->url,
        ];
    }
}
