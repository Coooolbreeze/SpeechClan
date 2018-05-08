<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'id' => $this->id,
            'image' => new ImageResource(Image::findOrFail($this->image_id)),
            'sort' => $this->sort,
            'publish' => (bool)$this->publish,
            'type' => $this->convertType($this->type)
        ];
    }

    private function convertType($value)
    {
        $type = [
            1 => '首页',
            2 => '关于我们',
            3 => '课程介绍',
            4 => '导师风采',
            5 => '学员风采',
            6 => '演讲帮'
        ];
        return $type[$value];
    }
}
