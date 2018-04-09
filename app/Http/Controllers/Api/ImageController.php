<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/9
 * Time: 14:26
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\ImageResource;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends ApiController
{
    public function store(Request $request)
    {
        $path = $request->file('file')->store('images', 'public');

        $res = Image::create([
            'url' => $path
        ]);

        return $this->success(new ImageResource($res));
    }
}