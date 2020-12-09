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
            'total chapter'=>$this->chapters()->count(),
        ];
    }
}
