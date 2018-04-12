<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => new ImageResource(Image::findOrFail($this->image_id)),
            'video' => ['url' => $this->video_url],
            'sort' => $this->sort,
            'created_at' => $this->created_at,
        ];
    }
}
