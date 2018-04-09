<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Resources\Json\JsonResource;

class ClubResource extends JsonResource
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
            'image' => new ImageResource(Image::findOrFail($this->image_id)),
            'title' => $this->title,
            'depict' => $this->depict,
            'detail' => $this->detail,
            'redirect_uri' => $this->redirect_uri,
            'sort' => $this->sort,
            'created_at' => (string)$this->created_at,
        ];
    }
}
