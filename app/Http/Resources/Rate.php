<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
class Rate extends JsonResource
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
            'story_id' => $this->story_id,
            'story_title' => $this->story_title,
            'author'=>$this->auhthor,
            'rating'=>$this->rating,
            'description'=>$this->description,

        ];
    }
}
