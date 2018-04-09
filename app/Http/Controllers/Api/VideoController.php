<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/9
 * Time: 16:46
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends ApiController
{
    public function store(Request $request)
    {
        $path = $request->file('file')->store('videos', 'public');

        $res = Video::create([
            'url' => $path
        ]);

        return $this->success(new VideoResource($res));
    }
}